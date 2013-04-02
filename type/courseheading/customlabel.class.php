<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_courseheading extends customlabel_type{

    function __construct($data){
    	global $CFG, $COURSE;
    	
        parent::__construct($data);
        $this->type = 'courseheading';
        $this->fields = array();        

        $field = new StdClass;
        $field->name = 'showdescription';
        $field->type = 'choiceyesno';
        $this->fields['showdescription'] = $field;

        $field = new StdClass;
        $field->name = 'showshortname';
        $field->type = 'choiceyesno';
        $this->fields['showshortname'] = $field;

        $field = new StdClass;
        $field->name = 'showidnumber';
        $field->type = 'choiceyesno';
        $this->fields['showidnumber'] = $field;

        $field = new StdClass;
        $field->name = 'showcategory';
        $field->type = 'choiceyesno';
        $this->fields['showcategory'] = $field;
        
		$field = new StdClass;
        $field->name = 'imageurl';
        $field->type = 'textfield';
        $field->size = 60;
        if (!is_file($CFG->dirroot.'/theme/'.current_theme().'/pix/customlabel_icons/defaultcourseheading.jpg')){
	        $field->default = $CFG->wwwroot.'/mod/customlabel/type/courseheading/defaultheading.jpg';
	    } else {
	        $field->default = $CFG->wwwroot.'/theme/'.current_theme().'/pix/customlabel_icons/defaultcourseheading.jpg';
	    }
        $this->fields['imageurl'] = $field;

		$field = new StdClass();
        $field->name = 'overimagetext';
        $field->type = 'textfield';
        $field->size = 20;
        $field->default = $COURSE->shortname;
        $this->fields['overimagetext'] = $field;

		$field = new StdClass();
        $field->name = 'imageposition';
        $field->type = 'list';
        $field->options = array('none', 'left', 'right');
        $field->default = 'none';
        $this->fields['imageposition'] = $field;

		$field = new StdClass;
        $field->name = 'moduletype';
        $field->type = 'textfield';
        $field->size = 40;
        $field->default = get_string('trainingmodule', 'customlabel');
        $this->fields['moduletype'] = $field;
    }

    /**
    * If exists, this method can process local alternative values for
    * realizing the template, after all standard translations have been performed. 
    * Type information structure and application context dependant.
    */
    function postprocess_data($course = null){
        global $CFG, $COURSE;

        if (is_null($course)) $course = &$COURSE;
        
        // get virtual fields from course title.
        $this->data->courseheading = str_replace("'", "\\'", $course->fullname);
        $this->data->coursedesc = str_replace("'", "\\'", $course->summary);
        $this->data->idnumber = $course->idnumber;
        $this->data->shortname = $course->shortname;
        $imageurl = (empty($this->data->imageurl)) ? $this->fields['imageurl']->default : $this->data->imageurl ;
    	if ($this->data->imagepositionoption == 'left'){
	        $this->data->imageL = "<td width=\"100\" class=\"custombox-icon-left courseheading\" align=\"center\" style=\"background:url({$imageurl}) 50% 50% no-repeat transparent\">{$this->data->overimagetext}</td>";
	        $this->data->imageR = '';
	    } else if ($this->data->imagepositionoption == 'right'){
	        $this->data->imageL = '';
	        $this->data->imageR = "<td width=\"100\" class=\"custombox-icon-right courseheading\" align=\"center\" style=\"background:url({$imageurl}) 50% 50% no-repeat transparent\">{$this->data->overimagetext}</td>";
	    } else {
	        $this->data->imageL = '';
	        $this->data->imageR = '';
	    }
    	$cat = get_record('course_categories', 'id', $course->category);
        $this->data->category = $cat->name;
    }
}
 
?>