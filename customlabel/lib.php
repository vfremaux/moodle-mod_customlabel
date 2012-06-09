<?php  // $Id: lib.php,v 1.5 2011-07-12 21:42:39 vf Exp $

/// Library of functions and constants for module label

// TAO : disabled length limitation for labels
// define("LABEL_MAX_NAME_LENGTH", 50);

/**
* make the name (printable in course summary) from real content of the label
* @param string $customlabel
* @param array $data an associative array containing the data
*/

/**
* includes and requires
*/
require_once ($CFG->dirroot.'/mod/customlabel/locallib.php');
require_once($CFG->libdir.'/pear/HTML/AJAX/JSON.php');
// include "debugging.php";


/**
* Given an object containing all the necessary data, 
* (defined by the form in mod.html) this function 
* will create a new instance and return the id number 
* of the new instance.
*
*/
function customlabel_add_instance($customlabel) {
    global $CFG;
    
    $customlabel->name = '';
    $customlabel->safecontent = base64_encode(json_encode($customlabel));
    $customlabel->content = json_encode($customlabel);
    $instance = customlabel_load_class($customlabel);
    $instance->preprocess_data();
    $instance->process_form_fields();
    $instance->process_datasource_fields();
    $instance->postprocess_data();
    $customlabel->name = $instance->get_name();
    $customlabel->timemodified = time();
    $customlabel = customlabel_addslashes_fields($customlabel);
    $customlabel->usesafe = 1;
    
    return insert_record('customlabel', $customlabel);
}

/**
* Given an object containing all the necessary data, 
* (defined by the form in mod.html) this function 
* will update an existing instance with new data.
*/
function customlabel_update_instance($customlabel) {
    global $CFG, $USER;
    
    $oldinstance = get_record('customlabel', 'id', $customlabel->instance);
	$typechanged = false;
    
    if ($oldinstance->labelclass != $customlabel->labelclass){
    	$instance = customlabel_load_class($oldinstance, true);
    	$instance->pre_update();
    	$typechanged = true;
        $customlabel->content = '';
        $customlabel->name = '';
        $customlabel->usesafe = 1;
    } else {
        $customlabel->safecontent = base64_encode(json_encode($customlabel));
        $customlabel->content = json_encode($customlabel); // (force old storage to clear when recoded to safe mode)
        $customlabel->usesafe = 1;
        $instance = customlabel_load_class($customlabel);
        $instance->preprocess_data();
        $instance->process_form_fields();
        $instance->process_datasource_fields();
        $instance->postprocess_data();
        $customlabel->name = $instance->get_name();
    }
    $customlabel->timemodified = time();
    $customlabel->id = $customlabel->instance;

    $customlabel = customlabel_addslashes_fields($customlabel);
    $result = update_record('customlabel', $customlabel);
    
    if ($result && $typechanged){
    	// instance has changed of type in the meanwhile
    	$instance->post_update();
    }

    // needed to update modinfo NOT NEEDED ANY MORE
    // rebuild_course_cache();
    
    return $result;
}

/**
* Given an ID of an instance of this module, 
* this function will permanently delete the instance 
* and any data that depends on it.  
*
*/
function customlabel_delete_instance($id) {

    if (! $customlabel = get_record('customlabel', 'id', "$id")) {
        return false;
    }

    // call subtype delete handler

	$instance = customlabel_load_class($customlabel, true);
	$instance->on_delete();

	// delete the module 
	
    $result = true;

    if (! delete_records('customlabel', 'id', "$customlabel->id")) {
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
    global $CFG;
    
    if ($customlabel = get_record('customlabel', 'id', $coursemodule->instance, '', '', '', '', 'id, content, title, name')) {
        $info = new object();
        $name = $customlabel->name;
        $name = str_replace("'", "&quot;", $name); // fixes a serialisation bug on quote
        $info->name = urlencode($name);
        $info->extra = '';
        if (!empty($customlabel->safecontent) && !empty($CFG->customlabel_usesafestorage)){
            $customcontent = json_decode(base64_decode($customlabel->safecontent));
            $info->extra = urlencode($customlabel->title);
        } elseif (!empty($customlabel->content)){
            $customcontent = json_decode($customlabel->content);
            $info->extra = urlencode($customlabel->title);
        }
        return $info;
    } else {
        return null;
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
* TODO : check relevance
*
*/
function customlabel_get_types() {
    $types = array();

    $type = new object();
    $type->modclass = MOD_CLASS_RESOURCE;
    $type->type = 'customlabel';
    $type->typestr = get_string('resourcetypecustomlabel', 'customlabel');
    $types[] = $type;

    return $types;
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
**/

/**
* Returns a XML fragment with the stored metadata and the type information
*
*/
function customlabel_get_xml($clid){
    global $CFG;
    
    if ($customlabel = get_record('customlabel', 'id', "$clid")){
        
        
        if (!empty($CFG->customlabel_usesafecontent) && $customlabel->usesafe){
	        $content = json_decode(base64_decode($customlabel->safecontent));
	    } else {
	        $content = json_decode($customlabel->content);
	    }
        
        // print_object($customlabel);
        
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
    global $COURSE;
    global $CFG;
    global $USER;

    $hidden = false;
    if (!is_null($block)){
	    $pageitem = get_record('format_page_items', 'id', $block->pageitemid); 
	} else {
		$pageitem->cmid = $cmid;
	}
    if ($pageitem->cmid){
        $sql = "
            SELECT 
                c.*
            FROM 
                {$CFG->prefix}course_modules cm,
                {$CFG->prefix}modules m,
                {$CFG->prefix}customlabel c                        
           WHERE
                cm.instance = c.id AND
                cm.module = m.id AND
                m.name = 'customlabel' AND
                cm.id = {$pageitem->cmid}
        ";
        $customlabel = get_record_sql($sql);
        if ($customlabel){
            $coursecontext = get_context_instance(CONTEXT_COURSE, $COURSE->id);
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
            $cfgkey = "list_customlabel_{$customlabel->labelclass}_hiddenfor";
            $hiddenforroles = explode(',', @$CFG->{$cfgkey});
            
            if (empty($CFG->{$cfgkey})){
                // no restriction applies 
                return false;
            }
            
            if (empty($roleids)){
                $guestroleid = get_field('role', 'id', 'shortname', 'guest');
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