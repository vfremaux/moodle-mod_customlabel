<?php  // $Id: lib.php,v 1.6 2012-06-19 11:46:40 vf Exp $

/// Library of functions and constants for module label

// disabled length limitation for labels
// define("LABEL_MAX_NAME_LENGTH", 50);

if (!isset($CFG->classification_type_table)){
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

/**
* includes and requires
*/
require_once ($CFG->dirroot.'/mod/customlabel/locallib.php');
// include "debugging.php";


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
    switch($feature) {
        case FEATURE_IDNUMBER:                return false;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_GROUPMEMBERSONLY:        return true;
        case FEATURE_MOD_INTRO:               return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_NO_VIEW_LINK:            return true;

        default: return null;
    }
}

/**
 * @param object $customlabel
 * @return string
 */
function customlabel_get_name($customlabel) {

    $name = format_string($customlabel->name, true);

    if (empty($name)) {
        // arbitrary name
        $name = get_string('modulename','customlabel');
    }

    return $name;
}

/**
* Given an object containing all the necessary data, 
* (defined by the form in mod.html) this function 
* will create a new instance and return the id number 
* of the new instance.
*
*/
function customlabel_add_instance($customlabel) {
    global $CFG, $DB;

    $customlabel->name = customlabel_get_name($customlabel);
    if (!isset($customlabel->intro)) $customlabel->intro = '';
    $customlabel->introformat = 0;

	/*    
	$modcontext = context_module::instance($this->_cm->id);
	$maxfiles = 99;                // TODO: add some setting
	$maxbytes = $COURSE->maxbytes; // TODO: add some setting	
	$editoroptions = array('trusttext' => true, 'subdirs' => false, 'maxfiles' => $maxfiles, 'maxbytes' => $maxbytes, 'context' => $modcontext);
	*/
	$customlabel->processedcontent = '';
    $instance = customlabel_load_class($customlabel);
    $customlabeldata = new StdClass();
    foreach($instance->fields as $field){
    	if (!isset($customlabel->{$field->name})) $customlabel->{$field->name} = @$_POST[$field->name]; // odd thing when bouncing
    	if (preg_match('/editor|textarea/',$field->type)){
    		/*
			$draftid_editor = file_get_submitted_draft_itemid($field->name.'_editor');
			$customlabel->{$field->name} = file_save_draft_area_files($draftid_editor, $modcontext->id, 'mod_customlabel', $field->name, $customlabel->id, array('subdirs' => true), $customlabel->{$field->name});
 	    	$customlabel = file_postupdate_standard_editor($customlabel, $field->name, $editoroptions, $modcontext, 'mod_customlabel', $field->name, $customlabel->id);
 	    	*/
 	    	$editorname = $field->name.'_editor';
    		if (!isset($customlabel->$editorname)){
    			$customlabel->{$field->name} = $_POST[$editorname]; // odd thing when bouncing
    		} else {
    			$customlabel->{$field->name} = $customlabel->$editorname; // odd thing when bouncing
    		}
   		}
    	$customlabeldata->{$field->name} = @$customlabel->{$field->name};
    	unset($customlabel->{$field->name});
    }

    // this saves into readable data information about which legacy type to use
    // if this record is restored on a platform that do not implement the actual labelclass.
    $customlabel->fallbacktype = ''.@$instance->fallbacktype;
    
    $customlabel->content = base64_encode(json_encode($customlabeldata));
    $instance->data = $customlabeldata; // load data into instance
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
    global $CFG, $USER, $DB;
    
    // check if type changed
    $oldinstance = $DB->get_record('customlabel', array('id' => $customlabel->instance));
	$typechanged = false;
    
    if ($oldinstance->labelclass != $customlabel->labelclass){
		$instance = customlabel_load_class($oldinstance, true);
    	$instance->pre_update();
    	$typechanged = true;
        $customlabel->content = '';
        $customlabel->name = '';
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
    }

	/*
	$modcontext = context_module::instance($customlabel->coursemodule);
	$maxfiles = 99;                // TODO: add some setting
	$maxbytes = $COURSE->maxbytes; // TODO: add some setting	
	$editoroptions = array('trusttext' => true, 'subdirs' => false, 'maxfiles' => $maxfiles, 'maxbytes' => $maxbytes, 'context' => $modcontext);
    */
    
	$customlabel->introformat = 0;
    $customlabel->timemodified = time();
    $customlabel->id = $customlabel->instance;
    $customlabel->processedcontent = '';
    
    print_object($customlabel);

    $instance = customlabel_load_class($customlabel);
	$customlabel->fallbacktype = ''.@$instance->fallbacktype;
    $customlabeldata = new StdClass();
    foreach($instance->fields as $field){
    	if (preg_match('/editor|textarea/',$field->type)){
    		/*
			$draftid_editor = file_get_submitted_draft_itemid($field->name.'_editor');
			$customlabel->{$field->name} = file_save_draft_area_files($draftid_editor, $modcontext->id, 'mod_customlabel', $field->name, $customlabel->id, array('subdirs' => true), $customlabel->{$field->name});
 	    	$customlabel = file_postupdate_standard_editor($customlabel, $field->name, $editoroptions, $modcontext, 'mod_customlabel', $field->name, $customlabel->id);
 	    	*/
 	    	$editorname = $field->name.'_editor';
    		if (!isset($customlabel->$editorname)){
    			$customlabel->{$field->name} = $_POST[$editorname]; // odd thing when bouncing
    		} else {
    			$customlabel->{$field->name} = $customlabel->$editorname; // odd thing when bouncing
    		}
   		}
    	$customlabeldata->{$field->name} = @$customlabel->{$field->name};
    	unset($customlabel->{$field->name});

    	if ($field->type == 'list'){
			$customlabeldata->{$field->name} = $_POST[$field->name]; // odd thing when bouncing
    	}
    }
    
    $customlabel->content = base64_encode(json_encode($customlabeldata));
	$instance->data = $customlabeldata;
	$processedcontent = $instance->make_content();
	$customlabel->processedcontent = $processedcontent;
    $result = $DB->update_record('customlabel', $customlabel);
    if ($result && $typechanged){
    	// instance has changed of type in the meanwhile
    	$instance->post_update();
    }

    return $result;
}

/**
* Given an ID of an instance of this module, 
* this function will permanently delete the instance 
* and any data that depends on it.  
*
*/
function customlabel_delete_instance($id) {
	global $DB;
	
    if (! $customlabel = $DB->get_record('customlabel', array('id' => "$id"))) {
        return false;
    }

    // call subtype delete handler

	$instance = customlabel_load_class($customlabel, true);
	$instance->on_delete();

	// delete the module 
	
    $result = true;

    if (! $DB->delete_records('customlabel', array('id' => "$customlabel->id"))) {
        $result = false;
    }

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
    
    if ($customlabel = $DB->get_record('customlabel', array('id' => $coursemodule->instance), 'id, labelclass, intro, title, name, content, processedcontent')) {

        $instance = customlabel_load_class($customlabel, $customlabel->labelclass);

        $info = new stdClass();
        $info->name = $customlabel->name;
        $info->extra = '';
        // $customcontent = json_decode(base64_decode($customlabel->content));
        $info->extra = urlencode($customlabel->title);
        return $info;
    } else {
        return null;
    }
}

/**
* this function makes a last post process of the cminfo information
* for module info caching in memory when course displays. HEre we
* can tweek some information to force cminfo behave like some label kind
*/
function customlabel_cm_info_dynamic(&$cminfo){
	global $DB, $PAGE, $CFG;

	static $customlabelscriptsloaded = false;
	static $customlabelcssloaded = array();

    // load some js sripts once
    if (!$customlabelscriptsloaded){
	    $PAGE->requires->js('/mod/customlabel/js/custombox.js', true);
	    $customlabelscriptsloaded = true;
	}
	
	// $bt = debug_backtrace();
	// print_r($bt);
	// print_object(debug_backtrace());
        
	// apply role restriction here
	$block = null;
	if (customlabel_is_hidden_byrole($block, $cminfo->id)){
		// set no chance to see anything from it
		$cminfo->set_no_view_link();
		$cminfo->set_content('');
		$cminfo->visible = 0;
		$cminfo->visibleold = 0;
		return;
	}
	
	if ($customlabel = $DB->get_record('customlabel', array('id' => $cminfo->instance))){
		//
		$cssurl = '/mod/customlabel/type/'.$customlabel->labelclass.'/customlabel.css';
		if (!$PAGE->requires->is_head_done()){
			$PAGE->requires->css($cssurl);
		} else {
			// late loading
			// less clean but no other way in some cases
			echo "<link rel=\"stylesheet\" href=\"{$CFG->wwwroot}{$cssurl}\" />\n";
		}

		// disable url form of the course module representation
		$cminfo->set_no_view_link();
		$cminfo->set_content($customlabel->processedcontent);
		$cminfo->set_extra_classes('label'); // important, or customlabel WILL NOT be deletable in topic/week course
	}

	// print_object($cminfo);
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
* TODO : check relevance
*
*/
/*
function customlabel_get_types() {
    $types = array();

    $type = new stdClass();
    $type->modclass = MOD_CLASS_RESOURCE;
    $type->type = 'customlabel';
    $type->typestr = get_string('resourcetypecustomlabel', 'customlabel');
    $types[] = $type;

    return $types;
}
*/

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
**/

/**
* Returns a XML fragment with the stored metadata and the type information
*
*/
function customlabel_get_xml($clid){
    global $CFG, $DB;
    
    if ($customlabel = $DB->get_record('customlabel', array('id' => "$clid"))){
        $content = json_decode(base64_decode($customlabel->content));
        $xml = "<datablock>\n";
        $xml .= "\t<instance>\n";
        $xml .= "\t\t<labeltype>{$customlabel->labelclass}</labeltype>\n";
        $xml .= "\t\t<title>{$customlabel->title}</title>\n";
        $xml .= "\t\t<timemodified>{$customlabel->timemodified}</timemodified>\n";
        $xml .= "\t</instance>\n";        
        $xml .= "\t<content>\n";
        foreach($content as $field => $value){
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
* @param $cmid, an aleternative to give directly the course module id
*/
function customlabel_is_hidden_byrole(&$block, $cmid = 0){
    global $COURSE, $CFG, $USER, $DB;

	// some admin situation needs view all    
    if (has_capability('moodle/site:config', context_system::instance())) return false;
    
    $hidden = false;
    if (!is_null($block)){
	    $pageitem = $DB->get_record('format_page_items', array('id' => $block->pageitemid)); 
	} else {
		$pageitem = new StdClass();
		$pageitem->cmid = $cmid;
	}
    if ($pageitem->cmid){
        $sql = "
            SELECT 
                c.*
            FROM 
                {course_modules} cm,
                {modules} m,
                {customlabel} c                        
           WHERE
                cm.instance = c.id AND
                cm.module = m.id AND
                m.name = 'customlabel' AND
                cm.id = {$pageitem->cmid}
        ";
        $customlabel = $DB->get_record_sql($sql);
        if ($customlabel){
            $coursecontext = context_course::instance($COURSE->id);
            // get faked role if role_switch is used
            if (isset($USER->access['rsw'][$coursecontext->path])){
                $roleids = array($USER->access['rsw'][$coursecontext->path]);
            } else {
                $roleassigns = get_user_roles($coursecontext, $USER->id, false);
                if (!$roleassigns){
                    $roleassigns = get_user_roles($coursecontext, $USER->id, true);
                }
                $roleids = array();
                foreach($roleassigns as $assign){
                    if (!in_array($assign->roleid, $roleids)) $roleids[] = $assign->roleid;
                }
            }
            $hidden = true;
            $cfgkey = "customlabel_{$customlabel->labelclass}_hiddenfor";
            $hiddenforroles = explode(',', @$CFG->{$cfgkey});
            
            if (empty($CFG->{$cfgkey})){
                // no restriction applies 
                return false;
            }
            
            if (empty($roleids)){
                $guestroleid = $DB->get_field('role', 'id', array('shortname' => 'guest'));
                $roleids[] = $guestroleid;
            }
            
            foreach($roleids as $aroleid){
                if (!in_array($aroleid, $hiddenforroles)){
                    $hidden = false;
                    return $hidden;
                }
            }
        }
    }
    return $hidden;
}

?>