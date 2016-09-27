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
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 *
 * TODO : check if there is not a legacy post install function in module API
 */

defined('MOODLE_INTERNAL') || die();

if (!isset($CFG->classification_type_table)) {
    set_config('classification_type_table', 'customlabel_mtd_type');
    set_config('classification_value_table', 'customlabel_mtd_value');
    set_config('classification_value_type_key', 'typeid');
    set_config('classification_constraint_table', 'customlabel_mtd_constraint');
    set_config('course_metadata_table', 'customlabel_course_metadata');
    set_config('course_metadata_value_key', 'valueid');
    set_config('course_metadata_course_key', 'courseid');
}

/**
 * returns all available classes for a customlabel
 * @uses $CFG
 * @param int $context if a context is given, filters out any type that is not allowed against 
 *                     roles held by the current user. Returns all types otherwise.
 * @param bool $ignoredisabled 
 * @param $outputmode if unset will get an associative array, if set to 'names' will provide an array of names
 * @return a sorted array of class definitions as objects or an array of class names
 */
function customlabel_get_classes($context = null, $ignoredisabled = true, $outputmode = false) {
    global $CFG;

    $config = get_config('customlabel');

    static $classes = array();
    static $classarr = array();

    $classnames = array();
    if (empty($classes)) {
        $basetypedir = $CFG->dirroot.'/mod/customlabel/type';

        // These may be disabled from config.php setup.
        $disabledtypes = @$config->disabled;

        $classdir = opendir($basetypedir);
        while ($entry = readdir($classdir)) {
            if (preg_match("/^[.!]/", $entry)) {
                continue; // Ignore what needs to be ignored.
            }
            if (!is_dir($basetypedir.'/'.$entry)) {
                continue; // Ignore real files.
            }
            if (preg_match('/^CVS$/', $entry)) {
                continue; // Ignore versionning files.
            }
            if ($entry == 'NEWTYPE') {
                continue; // Discard plugin prototype.
            }
            if ($ignoredisabled && preg_match('/\\b'.$entry.'\\b/', $disabledtypes)) {
                continue;
            }
            if (!is_null($context) &&
                    (has_capability('customlabeltype/'.$entry.':addinstance', $context) ||
                            ($context->contextlevel == CONTEXT_SYSTEM))) {
                $obj = new StdClass;
                $obj->id = $entry;
                $classnames[] = $entry;
                $obj->name = get_string('typename', 'customlabeltype_'.$entry);
                $obj->family = get_string('family', 'customlabeltype_'.$entry);
                if (empty($obj->family)) $obj->family = 'default';
                $classes[] = $obj;
                $classarr[$obj->id] = $obj->name;
            }
        }
    }

    if ($outputmode == 'names') {
        return $classnames;
    }

    if ($outputmode) {
        return $classarr;
    }

    // Sort result against localized names.
    $function = create_function('$a, $b', 'return strnatcmp($a->name, $b->name);');
    uasort($classes, $function);

    return $classes;
}

/**
 * fetches all area names in all customlabel types for backup. Note
 * that one area name could serve for different purposes in distinct 
 * instances of distinct types. This should not affectthe backup files
 * discrimination as keyed by the context id.
 */
function customlabel_get_fileareas() {
    global $CFG;

    $basetypedir = $CFG->dirroot.'/mod/customlabel/type';

    $areas = array();

    $classdir = opendir($basetypedir);
    while ($entry = readdir($classdir)) {
        if (preg_match("/^[.!]/", $entry)) {
            continue; // Ignore what needs to be ignored.
        }
        if (!is_dir($basetypedir.'/'.$entry)) {
            continue; // Ignore real files.
        }
        if (preg_match('/^CVS$/', $entry)) {
            continue; // Ignore versionning files.
        }
        if ($entry == 'NEWTYPE') {
            continue; // Discard plugin prototype.
        }
        require_once($basetypedir.'/'.$entry.'/customlabel.class.php');
        $classname = 'customlabel_type_'.$entry;
        $class = new $classname(null);
        foreach ($class->fields as $f) {
            if ($f->type == 'filepicker') {
                if (!in_array($f->name, $areas)) {
                    $areas[] = $f->name;
                }
            }
        }
    }

    return $areas;
}

/**
 * makes an instance of the customlabel description object
 * @param object $customlabel a customlabel record from the database
 * @param boolean $quiet if true, will be silent when failing finding the class reference
 * @return an instanciated classed object, loaded with the data in the record.
 * @uses $CFG
 */
function customlabel_load_class($customlabel, $quiet = false) {
    global $CFG, $OUTPUT;

    if (is_null($customlabel)) {
        print_error('errorclassloading', 'customlabel');
    }

    $classfile = $CFG->dirroot."/mod/customlabel/type/{$customlabel->labelclass}/customlabel.class.php";
    if (file_exists($classfile)) {
        include_once($classfile);
        $constructorfunction = "customlabel_type_{$customlabel->labelclass}";
        $instance = new $constructorfunction($customlabel, $customlabel->labelclass, @$customlabel->processedcontent);
        return $instance;
    } else {
        if (!$quiet) {
            print_object($customlabel);
        }
        if (debugging()) echo $OUTPUT->notification('errorfailedloading', 'customlabel', $customlabel->labelclass);
        return null;
    }
}

/**
 * preprocesses for content serialization
 * @param object $customlabel
 * @return the filtered object
 */
function customlabel_stripslashes_fields($customlabel) {

    // Unprotects single quote in fields.
    $customlabelarray = get_object_vars($customlabel);
    if ($customlabelarray) {
        foreach ($customlabelarray as $key => $value) {
            $customlabel->{$key} = str_replace("\\'", "'", $customlabel->{$key});
            $customlabel->{$key} = str_replace("\\\"", "\"", $customlabel->{$key});
        }
    }
    return $customlabel;
}


/**
 * preprocesses for content serialization
 * @param object $customlabel
 * @return the filtered object
 */
function customlabel_addslashes_fields($customlabel) {

    // Protects single quote in fields.
    $customlabelarray = get_object_vars($customlabel);
    if ($customlabelarray) {
        foreach ($customlabelarray as $key => $value) {
            if ($key == 'content') {
                $customlabel->{$key} = str_replace("\\", "\\\\", $customlabel->{$key});
                $customlabel->{$key} = str_replace("'", "\\'", $customlabel->{$key});
            }
        }
    }
    return $customlabel;
}

/**
 * Stores a filepicker uploaded single file int a filearea
 * Note that itemids are not used, as there can be only one
 * file instance avalaible per module level context.
 */
function customlabel_save_draft_file(&$customlabel, $filearea) {
    global $USER;

    static $fs;

    if (empty($fs)) {
        $fs = get_file_storage();
    }

    $usercontext = context_user::instance($USER->id);
    $context = context_module::instance($customlabel->coursemodule);

    $fileareagroupname = $filearea.'group';

    if (!empty($customlabel->$fileareagroupname)) {
        $fileareagroup = (array)$customlabel->$fileareagroupname;

        // Check for file deletion request.
        if (isset($fileareagroup['clear'.$filearea])) {
            $fs->delete_area_files($context->id, 'mod_customlabel', $filearea);
            return;
        }

        if (!isset($fileareagroup[$filearea])) {
            return;
        }

        $filepickeritemid = $fileareagroup[$filearea];

        $customlabel->$filearea = 0;
        if (!$fs->is_area_empty($usercontext->id, 'user', 'draft', $filepickeritemid, true)) {
            file_save_draft_area_files($filepickeritemid, $context->id, 'mod_customlabel', $filearea, 0);
        }
    }
}

/**
 * Convert encoded URLs in $text from the @@PLUGINFILE@@/... form to an actual URL.
 * @see lib/filedir.php
 *
 * We need customise that because our text has @@FILETAGS@@ from distinct itemids
 *
 * @category files
 * @global stdClass $CFG
 * @param string $text The content that may contain ULRs in need of rewriting.
 * @param string $file The script that should be used to serve these files. pluginfile.php, draftfile.php, etc.
 * @param int $contextid This parameter and the next two identify the file area to use.
 * @param string $component
 * @param string $filearea helps identify the file area.
 * @param int $itemid helps identify the file area.
 * @param array $options text and file options ('forcehttps'=>false)
 * @return string the processed text.
 */
function customlabel_file_rewrite_pluginfile_urls($text, $file, $contextid, $component, $filearea, $itemid, array $options = null) {
    global $CFG;

    $options = (array)$options;
    if (!isset($options['forcehttps'])) {
        $options['forcehttps'] = false;
    }

    if (!$CFG->slasharguments) {
        $file = $file . '?file=';
    }

    $baseurl = "$CFG->wwwroot/$file/$contextid/$component/$filearea/";

    if ($itemid !== null) {
        $baseurl .= "$itemid/";
    }

    if ($options['forcehttps']) {
        $baseurl = str_replace('http://', 'https://', $baseurl);
    }

    return preg_replace('/@@PLUGINFILE(\\:\\:|\\%3A\\%3A)'.$itemid.'@@\//i', $baseurl, $text);
}

/**
 * Convert the draft file area URLs in some content to @@PLUGINFILE@@ tokens
 * ready to be saved in the database. Normally, this is done automatically by
 * {@link file_save_draft_area_files()}.
 *
 * We need customise that because our text has @@FILETAGS@@ from distinct itemids
 *
 * @category files
 * @param string $text the content to process.
 * @param int $draftitemid the draft file area the content was using.
 * @param bool $forcehttps whether the content contains https URLs. Default false.
 * @return string the processed content.
 */
function customlabel_file_rewrite_urls_to_pluginfile($text, $draftitemid, $fielditemid, $forcehttps = false) {
    global $CFG, $USER;

    $usercontext = context_user::instance($USER->id);

    $wwwroot = $CFG->wwwroot;
    if ($forcehttps) {
        $wwwroot = str_replace('http://', 'https://', $wwwroot);
    }

    // Relink embedded files if text submitted - no absolute links allowed in database!
    $pattern = "!$wwwroot/draftfile.php/$usercontext->id/user/draft/$draftitemid/!i";
    $text = preg_replace($pattern, '@@PLUGINFILE::'.$fielditemid.'@@/', $text);

    if (strpos($text, 'draftfile.php?file=') !== false) {
        $matches = array();
        $pattern = "!$wwwroot/draftfile.php\?file=%2F{$usercontext->id}%2Fuser%2Fdraft%2F{$draftitemid}%2F[^'\",&<>|`\s:\\\\]+!iu";
        preg_match_all($pattern, $text, $matches);
        if ($matches) {
            foreach ($matches[0] as $match) {
                $replace = str_ireplace('%2F', '/', $match);
                $text = str_replace($match, $replace, $text);
            }
        }
        $pattern= "!$wwwroot/draftfile.php?file=/$usercontext->id/user/draft/$draftitemid/!i";
        $text = preg_replace($pattern, '@@PLUGINFILE::'.$fielditemid.'@@/', $text);
    }

    return $text;
}

/**
 * Processes a single customlabel by recalculating the content
 * @param ref $customlabel a customlabel record
 * @param string $labelclassname the real class of the element. 
 * @param string $course the current complete course record
 *
 */
function customlabel_regenerate(&$customlabel, $labelclassname, &$course) {
    global $DB;

    mtrace("\tprocessing customlabel $customlabel->id");
    // Renew the template.

    // Fake unpacks object's load.
    $data = json_decode(base64_decode($customlabel->content));
    if (is_null($data)) {
        $data = new StdClass;
    }
    if (!is_object($data)) {
        $data = new StdClass; // Reset old serialized data.
    };

    // Realize a pseudo update.
    $data->content = $customlabel->content;
    $data->labelclass = $labelclassname; // Fixes broken serialized contents.
    if (!isset($data->title)) {
        // Fixes broken serialized contents.
        $data->title = '';
    }
    $instance = customlabel_load_class($data);
    $instance->preprocess_data();
    $instance->process_form_fields();
    $instance->process_datasource_fields();
    $instance->postprocess_data($course);
    $customlabel->processedcontent = $instance->make_content('', $course); // This realizes the template.
    $customlabel->timemodified = time();
    $result = $DB->update_record('customlabel', $customlabel);
    mtrace("\tfinished customlabel $customlabel->id");
}

/**
 * Use by customlabel updater or by manual mass regeneration tools.
 * This may be needed if an administrator changes the elements templates
 * and need to recalculate all the course elements to use this change.
 * This function operzates in one single course
 * @param int $course the course id where to operate
 * @param mixed $labelclasses 'all' or an array of class names to operate in the course.
 */
function customlabel_course_regenerate(&$course, $labelclasses = '') {
    global $DB;

    if ($labelclasses == 'all') {
        // Take them all.
        $labelclasses = customlabel_get_classes(context_system::instance(), false, 'names');
    }

    foreach ($labelclasses as $labelclassname) {
        mtrace("   processing class '$labelclassname'");
        $select = " course = ? AND labelclass = ? ";
        $customlabels = $DB->get_records_select('customlabel', $select, array($course->id, $labelclassname));
        if ($customlabels) {
            foreach ($customlabels as $customlabel) {
                customlabel_regenerate($customlabel, $labelclassname, $course);
            }
        }
    }
}