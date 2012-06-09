<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_worktodo extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'worktodo';
        $this->fields = array();
        $this->allowedpageformats = 'page';
        
        $field->name = 'worktodo';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['worktodo'] = $field;

		$field = new Stdclass;
        $field->name = 'estimatedworktime';
        $field->type = 'textfield';
        $field->size = 10;
        $this->fields['estimatedworktime'] = $field;

        if ($fieldid = get_field('customlabel_mtd_type', 'id', 'code', 'WORKTYPE')){

			$field = new Stdclass;
	        $field->name = 'worktype';
	        $field->type = 'vdatasource';
	        $field->source = 'dbfieldkeyed';
	        $field->table = 'customlabel_mtd_value';
	        $field->field = 'value';
	        $field->key = 'code';
	        $field->select = " typeid = $fieldid ";
	        // $field->multiple = 'multiple';
	        $this->fields['worktype'] = $field;
	    } else {
	    	echo "no field for WORKTYPE ";
	    }

      	if ($fieldid = get_field('customlabel_mtd_type', 'id', 'code', 'WORKEFFORT')){

			$field = new Stdclass;
	        $field->name = 'workeffort';
	        $field->type = 'vdatasource';
	        $field->source = 'dbfieldkeyed';
	        $field->table = 'customlabel_mtd_value';
	        $field->field = 'value';
	        $field->key = 'code';
	        $field->select = " typeid = $fieldid ";
	        // $field->multiple = 'multiple';
	        $this->fields['workeffort'] = $field;
	    } else {
	    	echo "no field for WORKEFFORT ";
	    }

    	if ($fieldid = get_field('customlabel_mtd_type', 'id', 'code', 'WORKMODE')){

			$field = new Stdclass;
	        $field->name = 'workmode';
	        $field->type = 'vdatasource';
	        $field->source = 'dbfieldkeyed';
	        $field->table = 'customlabel_mtd_value';
	        $field->field = 'value';
	        $field->key = 'code';
	        $field->select = " typeid = $fieldid ";
	        // $field->multiple = 'multiple';
	        $this->fields['workmode'] = $field;
	    } else {
	    	echo "no field for WORKMODE ";
	    }
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

		/*
        // remove all old classification
        delete_records('customlabel_course_metadata', 'courseid', $COURSE->id);

        // add updated worktype    
        $cc->course = $COURSE->id;
        if (!empty($this->data->worktype)){
            if (is_array($this->data->worktype)){
                foreach($this->data->worktype as $worktype){
                    $cc->value = $worktype;
                    if (!insert_record('customlabel_course_metadata', $cc)){
                        notice("Could not classify course");
                    }
                }
            } else {
                $cc->value = $this->data->worktype;
                insert_record('customlabel_course_metadata', $cc);
            }
        }
        */

        $this->data->headerimage = $CFG->wwwroot.'/mod/customlabel/type/worktodo/thumb.jpg';
        if (is_array(@$this->data->worktype)) $this->data->worktype = implode(', ',@$this->data->worktype); 
        if (is_array(@$this->data->workeffort)) $this->data->workeffort = implode(', ',@$this->data->workeffort); 
        if (is_array(@$this->data->workmode)) $this->data->workmode = implode(', ',@$this->data->workmode); 
    }
}
 
?>