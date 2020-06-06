<?php
global $CLI_VMOODLE_PRECHECK;

define('CLI_SCRIPT', true);
define('CACHE_DISABLE_ALL', true);
$CLI_VMOODLE_PRECHECK = true; // Force first config to be minimal.

require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');

if (!isset($CFG->dirroot)) {
    die ('$CFG->dirroot must be explicitely defined in moodle config.php for this script to be used');
}

require_once($CFG->dirroot.'/lib/clilib.php'); // Cli only functions.

// CLI options.
list($options, $unrecognized) = cli_get_params(
    array(
        'help' => false,
        'host' => false,
        'dryrun' => false,
        'course' => false,
    ),
    array(
        'h' => 'help',
        'H' => 'host',
        'D' => 'dryrun',
        'C' => 'course',
    )
);

// Display help.
if (!empty($options['help'])) {

    echo "
Fixes courseclassifier metadata indexing in customlabel content.

Options:
    -h, --help              Print out this help
    --host                  the hostname
    --dryrun                Changes nothing in db, just tell
    --course                Just process this course

    \$ sudo -u www-data /usr/bin/php mod/customlabel/cli/fix_customlabel_content.php [--host=http://myvhost.mymoodle.org]
    ";


    // Exit with error unless we're showing this because they asked for it.
    exit(empty($options['help']) ? 1 : 0);
}

// Now get cli options.

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error("Not recognized options ".$unrecognized);
}

if (!empty($options['host'])) {
    // Arms the vmoodle switching.
    echo('Arming for '.$options['host']."\n"); // mtrace not yet available.
    define('CLI_VMOODLE_OVERRIDE', $options['host']);
}

// Replay full config whenever. If vmoodle switch is armed, will switch now config.
if ($CLI_VMOODLE_PRECHECK == false) {
    // First pass stopeed config to vmoodle trap.
    require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php'); // Global moodle config file.
}
echo('Config check : playing for '.$CFG->wwwroot."\n");

mtrace("Courseclassifiers convert tool starting...");
mtrace("-------------");
mtrace('');

require_once($CFG->dirroot.'/mod/customlabel/xlib.php');
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

if (!empty($options['course'])) {
    $courses = $DB->get_records('course', ['id' => $options['course']]);
} else {
    $courses = $DB->get_records('course', []);
}

$module = $DB->get_record('modules', ['name' => 'customlabel']);

if (!empty($options['dryrun'])) {
    echo "SIMULATE";
}

if ($courses) {
    foreach ($courses as $course) {

        $cms = $DB->get_records('course_modules', ['course' => $course->id, 'module' => $module->id]);
        if ($cms) {
            foreach ($cms as $cm) {

                $alreadyprocessed = [];

                $customlabelrec = $DB->get_record('customlabel', ['id' => $cm->instance]);
                $customlabel = customlabel_load_class($customlabelrec);
                if ($customlabelrec->labelclass == 'courseclassifier') {
                    $content = json_decode(base64_decode($customlabelrec->content));
                    foreach ($content as $fieldname => $value) {

                        if (preg_match('/option$/', $fieldname)) {
                            // These is virtual option field. Pass values to direct field.
                            $directfieldname = preg_replace('/option$/', '', $fieldname);
                            if (!empty($value)) {
                                $idarrs = explode(',', $value);
                                $content->$directfieldname = $idarrs;
                                unset($content->$fieldname);
                                $alreadyprocessed[] = $fieldname;
                            }

                            continue;
                        }

                        if (in_array($fieldname, $alreadyprocessed)) {
                            continue;
                        }

                        if (!array_key_exists($fieldname, $customlabel->fields)) {
                            continue;
                        }

                        if ($value == '_qf__force_multiselect_submission') {
                            $content->$fieldname = '';
                            continue;
                        }

                        if ($customlabel->fields[$fieldname]->type == 'datasource') {

                            if (is_string($value)) {
                                $values = explode(',', $value);
                                $idvalues = [];
                                foreach ($values as $v) {
                                    $sql = "
                                        SELECT
                                            mtv.id as mtvid,
                                            LOWER(mtt.code) as code
                                        FROM
                                            {customlabel_course_metadata} mtd,
                                            {customlabel_mtd_value} mtv,
                                            {customlabel_mtd_type} mtt
                                        WHERE
                                            mtd.valueid = mtv.id AND
                                            mtv.typeid = mtt.id AND
                                            mtd.courseid = ? AND
                                            mtd.cmid = ? AND
                                            LOWER(mtt.code) = ? AND
                                            mtv.value = ?
                                    ";
                                    if ($translatedmtd = $DB->get_record_sql($sql, [$course->id, $cm->id, $fieldname, $v])) {
                                        $idvalues[] = "$translatedmtd->mtvid";
                                    }
                                }
                                $content->$fieldname = $idvalues;
                            } else {
                                $content->$fieldname = $value;
                            }
                        }
                    }

                    $jsoncontent = json_encode($content);
                    $customlabelrec->content = base64_encode($jsoncontent);

                    if (empty($options['dryrun'])) {
                        $DB->update_record('customlabel', $customlabelrec);
                    } else {
                        echo "Output content {$course->id} {$cm->id}:\n$jsoncontent\n";
                    }
                }
            }
        }
    }
} else {
    mtrace("No courses found");
}

mtrace("All done.");