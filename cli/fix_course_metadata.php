<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     mod_customlabel
 *
 * @copyright   2010 onwards Valery Fremaux {valery.fremaux@gmail.com}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $clivmoodleprecheck;

define('CLI_SCRIPT', true);
define('CACHE_DISABLE_ALL', true);
$clivmoodleprecheck = true; // Force first config to be minimal.

require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');

if (!isset($CFG->dirroot)) {
    die ('$CFG->dirroot must be explicitely defined in moodle config.php for this script to be used');
}

require_once($CFG->dirroot.'/lib/clilib.php'); // Cli only functions.

// CLI options.
list($options, $unrecognized) = cli_get_params(
    [
        'help' => false,
        'host' => false,
    ],
    [
        'h' => 'help',
        'H' => 'host',
    ]
);

// Display help.
if (!empty($options['help'])) {

    echo "
Fixes course metadata from code increment < 0014 to > 0014. Course metadata classification needs
having originating cmid registered. Check all courses and : 
- If no classifier label : deletes metadata
- If single classifier label : reassigns metadata to the classifier cmid.
- If multiple classifier labels : Undecidable. Report case only.

Options:
    -h, --help              Print out this help
    --host                  the hostname

    \$ sudo -u www-data /usr/bin/php mod/customlabel/cli/fix_course_metadata.php [--host=http://myvhost.mymoodle.org]
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
if ($clivmoodleprecheck == false) {
    // First pass stopeed config to vmoodle trap.
    require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php'); // Global moodle config file.
}
echo('Config check : playing for '.$CFG->wwwroot."\n");

mtrace("Metadata cleanup tool starting...");
mtrace("-------------");
mtrace('');

require_once($CFG->dirroot.'/mod/customlabel/xlib.php');

$courses = $DB->get_records('course', []);

if ($courses) {
    foreach ($courses as $course) {
        $mtds = $DB->get_records('customlabel_course_metadata', ['courseid' => $course->id]);
        if ($mtds) {
            $assignedmtds = [];
            $unassignedmtds = [];
            foreach ($mtds as $mtd) {
                if ($mtd->cmid) {
                    $assignedmtds[] = $mtd->id;
                } else {
                    $unassignedmtds[] = $mtd->id;
                }
            }

            if (empty($unassignedmtds)) {
                mtrace("### No unassigned mtds, all's good in course $course->id ({$course->shortname}). skipping...");
                continue;
            }

            $classifiers = customlabel_get_all_classifiers($course->id);

            if (!$classifiers) {
                mtrace("Removing course metadata in course $course->id ({$course->shortname})");
                $DB->delete_records('customlabel_course_metadata', ['courseid' => $course->id]);
            } else if (count($classifiers) == 1) {
                if (empty($assignedmtds)) {
                    mtrace("Fixing course classifier in course $course->id ({$course->shortname})");
                    $classifier = array_shift($classifiers);
                    $cm = get_coursemodule_from_instance('customlabel', $classifier->id);
                    $DB->set_field('customlabel_course_metadata', 'cmid', $cm->id, ['courseid' => $course->id, 'cmid' => 0]);
                } else {
                    mtrace("Removing supplementary unassigned course metadata in course $course->id ({$course->shortname})");
                    $DB->delete_records('customlabel_course_metadata', ['courseid' => $course->id, 'cmid' => 0]);
                }
            } else {
                mtrace("Undecidable situation : multipleclassifiers on course $course->id ({$course->shortname})");
            }
        } else {
            mtrace("### No metadata found in course $course->id ({$course->shortname}).Skipping...");
        }
    }
} else {
    mtrace("No courses found");
}

mtrace("All done.");
