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
 * Library of functions and constants for module label.
 *
 * disabled length limitation for labels
 * define("LABEL_MAX_NAME_LENGTH", 50);
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
 * make the name (printable in course summary) from real content of the label
 * @param string $customlabel
 * @param array $data an associative array containing the data
 */

require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

/**
 * @uses FEATURE_IDNUMBER
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if module supports feature, false if not, null if doesn't know
 */
function customlabel_supports($feature) {
    switch ($feature) {
        case FEATURE_IDNUMBER:
            return false;
        case FEATURE_GROUPS:
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_GROUPMEMBERSONLY:
            return true;
        case FEATURE_MOD_INTRO:
            return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_NO_VIEW_LINK:
            return true;
        default:
            return null;
    }
}

/**
 * @param object $customlabel
 * @return string
 */
function customlabel_get_name($customlabel) {

    $name = format_string($customlabel->name, true);

    if (empty($name)) {
        // Arbitrary name.
        $name = get_string('modulename','customlabel');
    }

    return $name;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will create a new instance and return the id number
 * of the new instance.
 * @param object $customlabel
 * @return int instance id when created
 */
function customlabel_add_instance($customlabel) {
    global $DB;

    $customlabel->name = customlabel_get_name($customlabel);
    if (!isset($customlabel->intro)) {
        $customlabel->intro = '';
    }
    $customlabel->introformat = 0;

    $customlabel->processedcontent = '';

    $instance = customlabel_load_class($customlabel);
    $customlabeldata = new StdClass();

    $context = context_module::instance($customlabel->coursemodule);

    foreach ($instance->fields as $field) {
        $fieldname = $field->name;
        if (!isset($customlabel->{$field->name})) {
            $customlabel->{$field->name} = @$_REQUEST[$field->name]; // Odd thing when bouncing.
        }

        if ($field->type == 'date') {
            $m = $customlabel->{$field->name}['month'];
            $d = $customlabel->{$field->name}['day'];
            $y = $customlabel->{$field->name}['year'];
            $timestamp = mktime(0, 0, 0, $m, $d, $y);
            $customlabel->{$field->name} = $timestamp;
        }

        if ($field->type == 'datetime') {
            $h = $customlabel->{$field->name}['hour'];
            $m = $customlabel->{$field->name}['min'];
            $s = $customlabel->{$field->name}['sec'];
            $mo = $customlabel->{$field->name}['month'];
            $d = $customlabel->{$field->name}['day'];
            $y = $customlabel->{$field->name}['year'];
            $timestamp = mktime($h, $m, $s, $mo, $d, $y);
            $customlabel->{$field->name} = $timestamp;
        }

        if (preg_match('/editor|textarea/', $field->type)) {
            $editorname = $fieldname.'_editor';
            if (!isset($customlabel->$editorname)) {
                $editordata = $_REQUEST[$editorname]; // Odd thing when bouncing.
            } else {
                $editordata = $customlabel->$editorname; // Odd thing when bouncing.
            }
            // Saves all embdeded images or files into elements in a single text area.
            file_save_draft_area_files($editordata['itemid'], $context->id, 'mod_customlabel', 'contentfiles', $field->itemid);
            $t = $editordata['text'];
            $customlabel->$fieldname = customlabel_file_rewrite_urls_to_pluginfile($t, $editordata['itemid'], $field->itemid);
        }

        if ($field->type == 'filepicker') {
            customlabel_save_draft_file($customlabel, $field->name);
        }

        $customlabeldata->{$fieldname} = @$customlabel->{$fieldname};
        unset($customlabel->{$fieldname});
    }

    /*
     * this saves into readable data information about which legacy type to use
     * if this record is restored on a platform that do not implement the actual labelclass.
     */
    $customlabel->fallbacktype = ''.@$instance->fallbacktype;

    $customlabel->content = base64_encode(json_encode($customlabeldata));
    $instance->data = $customlabeldata; // Load data into instance.
    $customlabel->processedcontent = $instance->make_content();
    $customlabel->timemodified = time();
    return $DB->insert_record('customlabel', $customlabel);
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will update an existing instance with new data.
 */
function customlabel_update_instance($customlabel) {
    global $DB;

    // Check if type changed.
    $oldinstance = $DB->get_record('customlabel', array('id' => $customlabel->instance));
    $typechanged = false;

    if ($oldinstance->labelclass != $customlabel->labelclass) {
        $instance = customlabel_load_class($oldinstance, true);
        $instance->pre_update();
        $typechanged = true;
        $customlabel->content = '';
        $customlabel->name = $customlabel->labelclass.'_'.$customlabel->coursemodule;
        $customlabel->fallbacktype = @$instance->fallbacktype;
    } else {
        $customlabel->safecontent = base64_encode(json_encode($customlabel));
        $customlabel->content = json_encode($customlabel); // (force old storage to clear when recoded to safe mode)
        $instance = customlabel_load_class($customlabel);
        $instance->preprocess_data();
        $instance->process_form_fields();
        $instance->process_datasource_fields();
        $instance->postprocess_data();
        $customlabel->name = $instance->title;
        $customlabel->fallbacktype = @$instance->fallbacktype;
        $instance->posttemplate_data();
    }

    $customlabel->introformat = 0;
    $customlabel->timemodified = time();
    $customlabel->id = $customlabel->instance;
    $customlabel->processedcontent = '';

    // We make a true clone to process it from incoming data.
    $updatedinstance = customlabel_load_class($customlabel);
    $customlabel->fallbacktype = ''.@$updatedinstance->fallbacktype;

    $context = context_module::instance($customlabel->coursemodule);

    $customlabeldata = new StdClass();
    $customlabeldata->instance = $customlabel->instance;
    foreach ($updatedinstance->fields as $field) {
        $fieldname = $field->name;
        if (preg_match('/editor|textarea/', $field->type)) {
            // Editors need special processing for embedded links and images.
            $editorname = $fieldname.'_editor';
            if (!isset($customlabel->$editorname)) {
                $editordata = @$_POST[$editorname]; // Odd thing when bouncing.
            } else {
                $editordata = $customlabel->$editorname; // Odd thing when bouncing.
            }

            // Saves all embdeded images or files into elements in a single text area from editordata.
            file_save_draft_area_files($editordata['itemid'], $context->id, 'mod_customlabel', 'contentfiles', 0 + @$field->itemid);
            $customlabel->$fieldname = customlabel_file_rewrite_urls_to_pluginfile($editordata['text'], $editordata['itemid'], 0 + @$field->itemid);
        }

        if ($field->type == 'filepicker') {
            customlabel_save_draft_file($customlabel, $field->name);
        }

        $customlabeldata->{$field->name} = @$customlabel->{$field->name};
        unset($customlabel->{$field->name});

        if (preg_match('/datasource$/', $field->type)) {
            $fieldoption = $field->name.'option';
            if (property_exists($field, 'multiple') && $field->multiple) {
                if (!empty($customlabel->{$fieldoption})) {
                    $customlabeldata->{$fieldoption} = implode(',', $customlabel->{$fieldoption});
                }
            } else {
                $customlabeldata->{$fieldoption} = @$customlabel->{$fieldoption};
            }
            unset($customlabel->{$fieldoption});
        }

        if ($field->type == 'list') {
            $customlabeldata->{$field->name} = $_POST[$field->name]; // Odd thing when bouncing.
        }
    }

    $customlabel->content = base64_encode(json_encode($customlabeldata));
    $updatedinstance->data = $customlabeldata;
    $processedcontent = $updatedinstance->make_content();
    $customlabel->processedcontent = $processedcontent;

    $result = $DB->update_record('customlabel', $customlabel);
    if ($result && $typechanged) {
        // Instance has changed of type in the meanwhile.
        $updatedinstance->post_update();
    }

    return $result;
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 */
function customlabel_delete_instance($id) {
    global $DB;

    if (! $customlabel = $DB->get_record('customlabel', array('id' => "$id"))) {
        return false;
    }
    $cm = get_coursemodule_from_instance('customlabel', $customlabel->id);
    $context = context_module::instance($cm->id);

    // Call subtype delete handler.

    $instance = customlabel_load_class($customlabel, true);
    if ($instance) {
        $instance->on_delete();
    }

    // Delete the module.

    $result = true;

    if (! $DB->delete_records('customlabel', array('id' => $customlabel->id))) {
        $result = false;
    }

    $fs = get_file_storage();
    $fs->delete_area_files($context->id, 'mod_customlabel');

    return $result;
}

/**
 * Returns the users with data in one resource
 * (NONE, but must exist on EVERY mod !!)
 */
function customlabel_get_participants($customlabelid) {
    return false;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 */
function customlabel_get_coursemodule_info($coursemodule) {
    global $CFG, $DB, $COURSE;
    static $instances = array();

    if (!in_array($coursemodule->instance, $instances)) {
        if ($customlabel = $DB->get_record('customlabel', array('id' => $coursemodule->instance), 'id, labelclass, intro, title, name, content, processedcontent')) {
    
            // Check label subtype is still installed.
            if (!is_dir($CFG->dirroot.'/mod/customlabel/type/'.$customlabel->labelclass)) {
                course_delete_module($coursemodule->id);
                customlabel_delete_instance($customlabel->id);
                rebuild_course_cache($COURSE->id);
                return;
            }
            $instances[$coursemodule->instance] = customlabel_load_class($customlabel, $customlabel->labelclass);
        } else {
            return null;
        }
    }

    $info = new stdClass();
    $info->name = $customlabel->name;
    $info->extra = '';
    $info->extra = urlencode($instances[$coursemodule->instance]->title);
    return $info;
}

/**
 * This function makes a last post process of the cminfo information
 * for module info caching in memory when course displays. Here we
 * can tweek some information to force cminfo behave like some label kind
 * @see : Page format use the pageitem.php strategy for dealing with the 
 * content display rules.
 * @todo : reevaluate strategy. this may still be used for improving standard formats.
 */
function customlabel_cm_info_dynamic(&$cminfo) {
    global $DB, $PAGE, $CFG, $COURSE;

    static $customlabelscriptsloaded = false;
    static $customlabelcssloaded = array();

    // Load some js scripts once.
    if (!$customlabelscriptsloaded) {
        $PAGE->requires->js('/mod/customlabel/js/custombox.js', true);
        $customlabelscriptsloaded = true;
    }

    // Improve page format by testing if in current visble page.
    if ($COURSE->format == 'page') {
        $current = course_page::get_current_page($COURSE->id);
        if (!$DB->record_exists('format_page_items', array('cmid' => $cminfo->id, 'pageid' => $current->id))) {
            return;
        }
    }

    // debug_trace('standard view for CL '.$cminfo->id. '=> '.$cminfo->instance);

    // Apply role restriction here.
    if ($customlabel = $DB->get_record('customlabel', array('id' => $cminfo->instance))) {

        $context = context_module::instance($cminfo->id);

        $cssurl = '/mod/customlabel/type/'.$customlabel->labelclass.'/customlabel.css';
        $content = '';
        if (!$PAGE->requires->is_head_done()) {
            $PAGE->requires->css($cssurl);
        } else {
            // Late loading.
            // Less clean but no other way in some cases.
            $content .= '<link rel="stylesheet" href="'.$CFG->wwwroot.$cssurl.'" />'."\n";
        }

        if (!is_dir($CFG->dirroot.'/mod/customlabel/type/'.$customlabel->labelclass)) {
            return;
        }

        $instance = customlabel_load_class($customlabel, true);

        if (!customlabel_type::module_is_visible($cminfo, $customlabel)) {
            $cminfo->set_no_view_link();
            $cminfo->set_content('');
            $cminfo->set_user_visible(false);
            return;
        }

        $context = context_module::instance($cminfo->id);
        $fileprocessedcontent = $customlabel->processedcontent;
        foreach ($instance->fields as $field) {
            if ($field->type == 'editor' || $field->type == 'textarea') {
                if (!isset($field->itemid) || is_null($field->itemid)) {
                    throw new coding_exception('Course element textarea subfield needs explicit itemid in definition '.$customlabel->labelclass.'::'.$field->name);
                }
                $fileprocessedcontent = customlabel_file_rewrite_pluginfile_urls($fileprocessedcontent, 'pluginfile.php', $context->id, 'mod_customlabel', 'contentfiles', $field->itemid);
            }
        }

        $content .= '<div class="customlabel-'.$customlabel->labelclass.'">'.$fileprocessedcontent.'</div>';

        // Disable url form of the course module representation.
        $cminfo->set_no_view_link();
        $cminfo->set_content($content);
        $cminfo->set_extra_classes('label'); // Important, or customlabel WILL NOT be deletable in topic/week course.
    }
}

/**
 *
 *
 */
function customlabel_get_view_actions() {
    return array();
}

/**
 *
 *
 */
function customlabel_get_post_actions() {
    return array();
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function customlabel_reset_userdata($data) {
    return array();
}

/**
 * Other valuable API functions
 */

/**
 * Returns a XML fragment with the stored metadata and the type information
 *
 */
function customlabel_get_xml($clid) {
    global $DB;

    if ($customlabel = $DB->get_record('customlabel', array('id' => "$clid"))) {
        $content = json_decode(base64_decode($customlabel->content));
        $xml = "<datablock>\n";
        $xml .= "\t<instance>\n";
        $xml .= "\t\t<labeltype>{$customlabel->labelclass}</labeltype>\n";
        $xml .= "\t\t<title>{$customlabel->title}</title>\n";
        $xml .= "\t\t<timemodified>{$customlabel->timemodified}</timemodified>\n";
        $xml .= "\t</instance>\n";
        $xml .= "\t<content>\n";
        foreach ($content as $field => $value) {
            $xml .= "\t\t<{$field}>";
            $xml .= "$value";
            $xml .= "</$field>\n";
        }
        $xml .= '\t</content>';
        $xml .= '</datablock>';
        return $xml;
    }
    return '';
}

/**
 * @see course format page
 * allows role conditional display depending on customlabel type
 * @param $block in page format, course module id is found in page_module instance.
 * @param $cmid, an alternative to give directly the course module id
 */
function customlabel_is_hidden_byrole(&$block, $cmid = 0) {
    global $COURSE, $USER, $DB;

    // Some admin situation needs view all.
    if (has_capability('moodle/site:config', context_system::instance(), $USER->id, false)) {
        return false;
    }

    $capability = 'customlabeltype/'.$customlabel->labelclass.':view';

    // Trap the unlogged or guest case.
    $guestrole = $DB->get_record('roles', array('name' => 'guest'));
    if ($rolesforcap = get_roles_with_capability($capability, CAP_ALLOW)) {
        $allowedroleids = array_keys($rolesforcap);
        if (in_array($guestrole, $allowedroleids)) {
            // This module cannot be hidden at all as viewable even by unconnected people.
            return false;
        }
    }

    // Normal cases.
    $cm = get_coursemodule_from_id('customlabel', $cmid);
    $customlabel = $DB->get_record('customlabel', array('id' => $cm->instance));
    $context = context_module::instance($cmid);
    $res = !has_capability($capability, $context);
    return $res;
}

/*
 * Serves files for customlabel micromodels
 * array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - just send the file
 */
function customlabel_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $CFG, $DB;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    if (!$customlabel = $DB->get_record('customlabel', array('id' => $cm->instance))) {
        return false;
    }

    if ($course->format == 'page') {
        /*
         * In page format, some pages may not require login. Just check the customlabel
         * is accessible to the user (no mater pages are or not).
         */
        require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');
        if (!customlabel_type::module_is_visible($cm, $customlabel)) {
            return false;
        }
    } else {
        require_course_login($course, true, $cm);
    }

    $instance = customlabel_load_class($customlabel);

    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/$context->id/mod_customlabel/{$filearea}/$relativepath";
    $file = $fs->get_file_by_hash(sha1($fullpath));
    if (!$file || $file->is_directory()) {
        return false;
    }

    // Nasty hack because we do not have file revisions in customlabels yet.
    $lifetime = 0 + @$CFG->filelifetime;
    if ($lifetime > 60 * 10) {
        $lifetime = 60 * 10;
    }

    // Finally send the file.
    send_stored_file($file, $lifetime, 0, $forcedownload, $options);
}
