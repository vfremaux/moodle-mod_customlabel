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
 * @package mod-customlabel
 * @category mod
 * @author Valery Fremaux for Pairformance/TAO
 * @date 15/07/2008
 *
 * TODO : check if there is not a legacy post install function in module API
 */
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
        $basetypedir = $CFG->dirroot."/mod/customlabel/type";

        // these may be disabled from config.php setup
        $disabledtypes = @$config->disabled;

        $classdir = opendir($basetypedir);
        while ($entry = readdir($classdir)) {
            if (preg_match("/^[.!]/", $entry)) continue; // Ignore what needs to be ignored.
            if (!is_dir($basetypedir.'/'.$entry)) continue; // Ignore real files.
            if (preg_match('/^CVS$/', $entry)) continue; // Ignore versionning files.
            if ($entry == 'NEWTYPE') continue; // Discard plugin prototype.
            if ($ignoredisabled && preg_match('/\\b'.$entry.'\\b/', $disabledtypes)) continue;
            if (!is_null($context) && (has_capability('customlabeltype/'.$entry.':addinstance', $context) || ($context->contextlevel == CONTEXT_SYSTEM))) {
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
        $data = new StdClass; // reset old serialized data
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
        $customlabels = $DB->get_records_select('customlabel', " course = ? AND labelclass = ? ", array($course->id, $labelclassname));
        if ($customlabels) {
            foreach ($customlabels as $customlabel) {
                customlabel_regenerate($customlabel, $labelclassname, $course);
            }
        }
    }
}