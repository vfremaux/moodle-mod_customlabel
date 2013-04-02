<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_contactpoint extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'contactpoint';
        $this->fields = array();
        /* $this->allowedpageformats = 'page'; */
        
        $field->name = 'instructions';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['instructions'] = $field;

		$field = new Stdclass;
        $field->name = 'contacttype';
        $field->type = 'list';
        $field->options = array('any', 'anywritten', 'mail', 'phone', 'onlinevocal', 'chat', 'meeting', 'facetoface');
        $this->fields['contacttype'] = $field;
    }

	// sample of what do do if relation to course qualification
	/*
    function on_delete(){
    	global $COURSE;
        // remove all old classification
        delete_records('customlabel_course_metadata', 'courseid', $COURSE->id);
    }

    function pre_update(){
    	global $COURSE;
        // remove all old classification when leaving this type
        delete_records('customlabel_course_metadata', 'courseid', $COURSE->id);
    }
    
    */
    function postprocess_data($course = null){
        global $CFG, $COURSE;
        
    }
}
 
?>