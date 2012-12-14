<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_courseheading extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'courseheading';
        $this->fields = array();        
        
        $field = new StdClass;
        $field->name = 'showdescription';
        $field->type = 'choiceyesno';
        $this->fields['showdescription'] = $field;

        $field = new StdClass;
        $field->name = 'showidnumber';
        $field->type = 'choiceyesno';
        $this->fields['showidnumber'] = $field;

        $field = new StdClass;
        $field->name = 'showcategory';
        $field->type = 'choiceyesno';
        $this->fields['showcategory'] = $field;
        
    }

    /**
    * If exists, this method can process local alternative values for
    * realizing the template, after all standard translations have been performed. 
    * Type information structure and application context dependant.
    */
    function postprocess_data($course = null){
        global $CFG, $COURSE, $DB;

        if (is_null($course)) $course = &$COURSE;
        
        // get virtual fields from course title.
        $this->data->courseheading = str_replace("'", "\\'", $course->fullname);
        if (@$this->data->showdescription){
	        $this->data->coursedesc = '<div class="custombox-description courseheading">'.str_replace("'", "\\'", $course->summary).'</div>';
	    }
        if (@$this->data->showidnumber){
	        $this->data->idnumber = '<div class="custombox-idnumber courseheading">['.$course->idnumber.']</div>';
	    }
        if (@$this->data->showcategory){
        	$cat = $DB->get_record('course_categories', array('id' => $course->category));
	        $this->data->category = '<div class="custombox-category courseheading">'.$cat->name.'</div>';
	    }
    }
}
 
?>