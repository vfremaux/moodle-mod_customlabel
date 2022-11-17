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
 * This function is not implemented in this plugin, but is needed to mark
 * the vf documentation custom volume availability.
 */
function customlabel_supports_feature($feature = null, $getsupported = false) {
    global $CFG;
    static $supports;

    if (!during_initial_install()) {
        $config = get_config('customlabel');
    }

    if (!isset($supports)) {
        $supports = array(
            'pro' => array(
                'api' => array('ws'),
                'types' => array('remotecontent','cssadditions','localdokuwikicontent','learningindicators','verticalspacer'),
            ),
            'community' => array(
            ),
        );
    }

    if ($getsupported) {
        return $supports;
    }

    // Check existance of the 'pro' dir in plugin.
    if (is_dir(__DIR__.'/pro')) {
        if ($feature == 'emulate/community') {
            return 'pro';
        }
        if (empty($config->emulatecommunity)) {
            $versionkey = 'pro';
        } else {
            $versionkey = 'community';
        }
    } else {
        $versionkey = 'community';
    }

    if (empty($feature)) {
        // Just return version.
        return $versionkey;
    }

    list($feat, $subfeat) = explode('/', $feature);

    if (!array_key_exists($feat, $supports[$versionkey])) {
        return false;
    }

    if (!in_array($subfeat, $supports[$versionkey][$feat])) {
        return false;
    }

    return $versionkey;
}

/*
 * Make the name (printable in course summary) from real content of the label
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
        case FEATURE_IDNUMBER: {
            return true;
        }
        case FEATURE_GROUPS: {
            return false;
        }
        case FEATURE_GROUPINGS: {
            return false;
        }
        case FEATURE_GROUPMEMBERSONLY: {
            return true;
        }
        case FEATURE_MOD_INTRO: {
            return false;
        }
        case FEATURE_COMPLETION_TRACKS_VIEWS: {
            return false;
        }
        case FEATURE_COMPLETION_HAS_RULES: {
            return true;
        }
        case FEATURE_GRADE_HAS_GRADE: {
            return false;
        }
        case FEATURE_GRADE_OUTCOMES: {
            return false;
        }
        case FEATURE_MOD_ARCHETYPE: {
            return MOD_ARCHETYPE_RESOURCE;
        }
        case FEATURE_BACKUP_MOODLE2: {
            return true;
        }
        case FEATURE_NO_VIEW_LINK: {
            return true;
        }
        default: {
            return null;
        }
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
        $name = get_string('modulename', 'customlabel');
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
function customlabel_add_instance($customlabelrec) {
    global $DB;

    $customlabelrec->name = customlabel_get_name($customlabelrec);
    if (!isset($customlabelrec->intro)) {
        $customlabelrec->intro = '';
    }
    $customlabelrec->introformat = 0;

    $customlabelrec->processedcontent = '';

    $instance = customlabel_load_class($customlabelrec);
    $instance->coursemodule = $customlabelrec->coursemodule;

    $customlabeldata = customlabel_process_fields($customlabelrec, $instance);
    $instance->data = $customlabeldata;

    /*
     * this saves into readable data information about which legacy type to use
     * if this record is restored on a platform that do not implement the actual labelclass.
     */
    $customlabelrec->fallbacktype = ''.@$instance->fallbacktype;

    $customlabelrec->content = base64_encode(json_encode($customlabeldata));
    $customlabelrec->timemodified = time();

    $instance->pre_update();
    $customlabelid = $DB->insert_record('customlabel', $customlabelrec);
    $instance->post_update();

    return $customlabelid;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will update an existing instance with new data.
 */
function customlabel_update_instance($customlabelrec) {
    global $DB;

    // Check if type changed.
    $oldinstance = $DB->get_record('customlabel', array('id' => $customlabelrec->instance));
    $oldinstance->coursemodule = $customlabelrec->coursemodule;
    $typechanged = false;

    if ($oldinstance->labelclass != $customlabelrec->labelclass) {
        $instance = customlabel_load_class($oldinstance, true);
        $instance->pre_update();
        $typechanged = true;
        $customlabelrec->content = '';
        $customlabelrec->name = $customlabelrec->labelclass.'_'.$customlabelrec->coursemodule;
        $customlabelrec->fallbacktype = @$instance->fallbacktype;
    }

    $customlabelrec->introformat = 0;
    $customlabelrec->timemodified = time();
    $customlabelrec->id = $customlabelrec->instance;

    // We make a true clone to process it from incoming data.
    $updatedinstance = customlabel_load_class($customlabelrec);
    $customlabelrec->fallbacktype = ''.@$updatedinstance->fallbacktype;

    $customlabeldata = customlabel_process_fields($customlabelrec, $updatedinstance);
    $customlabeldata->instance = $customlabelrec->instance;

    $customlabelrec->content = base64_encode(json_encode($customlabeldata));
    $updatedinstance->data = $customlabeldata;

    if ($typechanged) {
        // Instance has changed of type in the meanwhile.
        $updatedinstance->pre_update();
    }

    $result = $DB->update_record('customlabel', $customlabelrec);

    if ($result) {
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
    $customlabel->coursemodule = $cm->id; // Will be known as cmid in instance.
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
    global $CFG, $DB;
    static $instances = array();

    if (!in_array($coursemodule->instance, $instances)) {
        $fields = 'id, labelclass, intro, title, name, content, processedcontent';
        if ($customlabel = $DB->get_record('customlabel', array('id' => $coursemodule->instance), $fields)) {
            $customlabel->coursemodule = $coursemodule->id;
            // Check label subtype is still installed.
            if (!is_dir($CFG->dirroot.'/mod/customlabel/type/'.$customlabel->labelclass)) {
                return;
            }
            $instance = customlabel_load_class($customlabel, $customlabel->labelclass);
            if (!empty($instance->hasamd)) {
                $instance->require_js();
            }
            $instances[$coursemodule->instance] = $instance;
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

function customlabel_cm_info_dynamic(&$cminfo) {
    global $DB;

    $iscminfo = (get_class($cminfo) == 'cminfo') || (get_class($cminfo) == 'cm_info');

    // Apply role restriction here.
    if (!$customlabel = $DB->get_record('customlabel', array('id' => $cminfo->instance))) {
        return;
    }

    $customlabel->coursemodule = $cminfo->id;
    $instance = customlabel_load_class($customlabel, true);

    if (!customlabel_type::module_is_visible($cminfo, $customlabel)) {
        if ($iscminfo) {
            $cminfo->set_no_view_link();
            $cminfo->set_content('');
            $cminfo->set_user_visible(false);
        }
        return;
    }

    if ($iscminfo) {
        $cminfo->set_no_view_link();
        $cminfo->set_extra_classes('label'); // Important, or customlabel WILL NOT be deletable in topic/week course.
    }
}

/**
 * This function makes a last post process of the cminfo information
 * for module info caching in memory when course displays. Here we
 * can tweek some information to force cminfo behave like some label kind
 * @see : Page format use the pageitem.php strategy for dealing with the
 * content display rules.
 * @todo : reevaluate strategy. this may still be used for improving standard formats.
 */
function customlabel_cm_info_view(&$cminfo) {
    global $DB, $PAGE, $CFG, $COURSE, $OUTPUT, $USER;

    global $customlabelscriptsloaded;
    static $customlabelcssloaded = array();
    static $customlabelamdloaded = array();

    $config = get_config('customlabel');

    // Specific > 3.5
    $iscminfo = (get_class($cminfo) == 'cminfo') || (get_class($cminfo) == 'cm_info');
    if (!$iscminfo) {
        $cms = get_fast_modinfo($COURSE, $USER->id);
        $cminfo = $cms->get_cm($cminfo->id);
        $iscminfo = true;
    }

    // Improve page format by testing if in current visble page.
    if ($COURSE->format == 'page') {
        $current = \format\page\course_page::get_current_page($COURSE->id);
        if (!$DB->record_exists('format_page_items', array('cmid' => $cminfo->id, 'pageid' => $current->id))) {
            return;
        }
    }

    // Apply role restriction here.
    if (!$customlabel = $DB->get_record('customlabel', array('id' => $cminfo->instance))) {
        return;
    }

    $customlabel->coursemodule = $cminfo->id;
    $instance = customlabel_load_class($customlabel, true);

    // Load some js scripts once.
    if (!$customlabelscriptsloaded) {
        $PAGE->requires->js_call_amd('mod_customlabel/customlabel', 'init');
        $customlabelscriptsloaded = true;

    }

    if (!in_array($customlabel->labelclass, $customlabelamdloaded)) {
        if (!empty($instance->hasamd)) {
            $instance->require_js();
        }
        $customlabelamdloaded[] = $customlabel->labelclass;
    }

    $context = context_module::instance($cminfo->id);
    $content = '';

    if (!in_array($customlabel->labelclass, $customlabelcssloaded)) {
        $cssurl = '/mod/customlabel/typestyle.php?type='.$customlabel->labelclass;
        $cssurl .= '&theme='.$PAGE->theme->name;
        if (!$PAGE->requires->is_head_done()) {
            $PAGE->requires->css($cssurl);
        } else {
            // Late loading.
            // Less clean but no other way in some cases.
            $csslink = '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.$cssurl.'" />'."\n";
            // Print it directly as some filtering may drop those links sometimes.
            echo $csslink;
        }
        $customlabelcssloaded[] = $customlabel->labelclass;
    }

    if (!is_dir($CFG->dirroot.'/mod/customlabel/type/'.$customlabel->labelclass)) {
        if ($CFG->debug == DEBUG_DEVELOPER) {
            $cminfo->set_content('<div class="error"> Missing type (not installed) : '.$customlabel->labelclass.' </div>');
        }
        return;
    }

    if (!customlabel_type::module_is_visible($cminfo, $customlabel)) {
        return;
    }

    // Specific >= 3.5
    $info = optional_param('info', '', PARAM_TEXT);
    $gettingmoduleupdate = in_array($info, array('core_course_get_module', 'core_course_edit_module'));
    global $FULLME;
    $ispluginfile = preg_match('/pluginfile/', $FULLME);
    $istogglecompletion = preg_match('/togglecompletion/', $FULLME);

    if (!$ispluginfile && (($PAGE->pagetype != 'course-modedit') && !AJAX_SCRIPT && !$istogglecompletion) || $gettingmoduleupdate) {

        // In edit form, some race conditions between theme and rendering goes wrong when not admin...
        try {
            $instance->preprocess_data();
            $instance->process_form_fields();
            $instance->process_datasource_fields();
            $instance->postprocess_data();
            $instance->postprocess_icon();
            $instance->data->labelclass = $customlabel->labelclass;
            $template = 'customlabeltype_'.$customlabel->labelclass.'/template';
            $instance->data->skin = $config->defaultskin;

            $themename = $PAGE->theme->name;
            $override = get_config('theme_'.$themename, 'customlabelskin');
            if (!empty($override)) {
                $instance->data->skin = $override;
            }

            $content .= $OUTPUT->render_from_template($template, $instance->data);

        } catch (Exception $e) {
            assert(1);
            // Quiet any exception here. Resolve case of Editing Teachers.
        }
    }

    $context = context_module::instance($cminfo->id);
    foreach ($instance->fields as $field) {
        if ($field->type == 'editor' || $field->type == 'textarea') {
            if (!isset($field->itemid) || is_null($field->itemid)) {
                $message = 'Course element textarea subfield needs explicit itemid in definition ';
                $message .= $customlabel->labelclass.'::'.$field->name;
                throw new coding_exception($message);
            }
            $content = customlabel_file_rewrite_pluginfile_urls($content, 'pluginfile.php',
                                                                             $context->id, 'mod_customlabel', 'contentfiles',
                                                                             $field->itemid);
        }
    }

    // Disable url form of the course module representation.
    $cminfo->set_content($content);
    $cminfo->set_extra_classes('label'); // Important, or customlabel WILL NOT be deletable in topic/week course.
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
    global $USER, $DB;

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
function customlabel_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    global $CFG, $DB;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    if (!$customlabel = $DB->get_record('customlabel', array('id' => $cm->instance))) {
        return false;
    }
    $customlabel->coursemodule = $cm->id;

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

    customlabel_load_class($customlabel);

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

/**
 * Obtains the automatic completion state for this customlabel based on any conditions
 * in customlabel settings.
 *
 * @global object
 * @global object
 * @param object $course Course
 * @param object $cm Course-module
 * @param int $userid User ID
 * @param bool $type Type of comparison (or/and; can be used as return value if no conditions)
 * @return bool True if completed, false if not. (If no conditions, then return
 *   value depends on comparison type)
 */
function customlabel_get_completion_state($course, $cm, $userid, $type) {
    global $DB;

    // Get customlabel details.
    if (!($instance = $DB->get_record('customlabel', array('id' => $cm->instance)))) {
        throw new Exception("Can't find customlabel {$cm->instance}");
    }

    $instance->coursemodule = $cm->id;
    $customlabel = customlabel_load_class($instance);

    $result = $type; // Default return value.
    if ($instance->completion1enabled) {
        $params = array('userid' => $userid, 'customlabelid' => $cm->instance);
        $ud = $DB->get_field('customlabel_user_data', 'completion1', $params);
        $value = ($ud == $customlabel->complete_value(1));
        if ($type == COMPLETION_AND) {
            $result = $result && $value;
        } else {
            $result = $result || $value;
        }
    }

    if ($instance->completion2enabled) {
        $params = array('userid' => $userid, 'customlabelid' => $cm->instance);
        $ud = $DB->get_field('customlabel_user_data', 'completion2', $params);
        $value = ($ud == $customlabel->complete_value(2));
        if ($type == COMPLETION_AND) {
            $result = $result && $value;
        } else {
            $result = $result || $value;
        }
    }

    if ($instance->completion3enabled) {
        $params = array('userid' => $userid, 'customlabelid' => $cm->instance);
        $ud = $DB->get_field('customlabel_user_data', 'completion3', $params);
        $value = ($ud == $customlabel->complete_value(3));
        if ($type == COMPLETION_AND) {
            $result = $result && $value;
        } else {
            $result = $result || $value;
        }
    }

    return $result;
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * This function will remove all posts from the specified forum
 * and clean up any related data.
 *
 * @global object
 * @global object
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function customlabel_reset_userdata($data) {
    global $CFG, $DB;
    require_once($CFG->dirroot.'/rating/lib.php');

    $componentstr = get_string('modulenameplural', 'customlabel');
    $status = array();

    if (!empty($data->reset_customlabel_all)) {
        $params = array($data->courseid);
        $labels = $DB->get_records('customlabel', array('course' => $data->courseid));
        if (!$labels) {
            return array();
        }
    } else if (!empty($data->reset_customlabel_types)) {
        $labels = array();
        foreach ($data->reset_customlabel_types as $type) {
            $labels = $DB->get_records('customlabel', array('course' => $data->courseid, 'labelclass' => $type));
            $labels += $labels;
        }
    } else {
        return array();
    }

    $labelids = array_keys($labels);
    list($insql, $inparams) = $DB->get_in_or_equal($labelids);

    $sql = "
        UPDATE
            {customlabel_user_data}
        SET
            completion1 = 0,
            completion2 = 0,
            completion3 = 0
        WHERE
            customlabelid $insql
    ";

    $DB->execute($sql, $inparams);
    $status[] = array('component' => $componentstr, 'item' => get_string('userstatesreset', 'customlabel'), 'error' => false);

    if (!empty($data->reset_customlabel_completions)) {

        $module = $DB->get_record('modules', array('name' => 'customlabel'));
        $select = "
            instance $insql AND
            module = ?
        ";
        $inparams[] = $module->id;

        $course = $DB->get_record('course', array('id' => $data->id));
        $completion = new completion_info($course);

        $cms = $DB->get_records_select('course_modules', $select, $inparams);
        if ($cms) {
            foreach ($cms as $cm) {
                $completion->delete_all_state($cm);
            }
        }
    }

    return $status;
}

/**
 * Called by course/reset.php
 *
 * @param $mform form passed by reference
 */
function customlabel_reset_course_form_definition(&$mform) {
    global $CFG;

    $mform->addElement('header', 'cistomlabelheader', get_string('modulenameplural', 'customlabel'));

    $mform->addElement('checkbox', 'reset_customlabel_all', get_string('resetall', 'customlabel'));

    $mform->addElement('checkbox', 'reset_customlabel_completions', get_string('withcompletions', 'customlabel'));

    $classes = customlabel_get_classes(context_system::instance(), true, false);

    // Filter out those who do not deal with completion at all.
    $types = array();
    foreach ($classes as $c) {
        $classfile = $CFG->dirroot."/mod/customlabel/type/{$c->id}/customlabel.class.php";
        if (!file_exists($classfile)) {
            continue;
        }
        include_once($classfile);
        $classname = 'customlabel_type_'.$c->id;

        if (method_exists($classname, 'add_completion_rules')) {
            $types[$c->id] = $c->name;
        }
    }

    $options = array('multiple' => 'multiple');
    $mform->addElement('select', 'reset_customlabel_types', get_string('resetlabeltypes', 'customlabel'), $types, $options);
    $mform->setAdvanced('reset_customlabel_types');
    $mform->disabledIf('reset_customlabel_types', 'reset_customlabel_all', 'checked');
}

/**
 * Course reset form defaults.
 * @return array
 */
function customlabel_reset_course_form_defaults($course) {
    return array('reset_customlabel_all' => 1);
}

/**
 * This function allows the tool_dbcleaner to register integrity checks
 */
function customlabel_dbcleaner_add_keys() {
    global $DB;

    $customlabelmoduleid = $DB->get_field('modules', 'id', array('name' => 'customlabel'));

    $keys = array(
        array('customlabel', 'course', 'course', 'id', ''),
        array('customlabel', 'id', 'course_modules', 'instance', ' module = '.$customlabelmoduleid.' '),
        array('customlabel_mtd_value', 'typeid', 'customlabel_mtd_type', 'id', ''),
        array('customlabel_course_metadata', 'courseid', 'course', 'id', ''),
        array('customlabel_course_metadata', 'valueid', 'customlabel_mtd_value', 'id', ''),
        array('customlabel_mtd_constraints', 'value1', 'customlabel_mtd_value', 'id', ''),
        array('customlabel_mtd_constraints', 'value2', 'customlabel_mtd_value', 'id', ''),
    );

    return $keys;
}