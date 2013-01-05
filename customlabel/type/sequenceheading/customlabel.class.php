<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_sequenceheading extends customlabel_type{

    function __construct($data){
		global $CFG;

        parent::__construct($data);
        $this->type = 'sequenceheading';
        $this->fields = array();        
        
        $field = new StdClass;
        $field->name = 'heading';
        $field->size = 80;
        $field->type = 'textfield';
        $this->fields['heading'] = $field;

        $field = new StdClass;
        $field->name = 'shortdesc';
        $field->type = 'textarea';
        $this->fields['shortdesc'] = $field;

		$field = new StdClass();
        $field->name = 'imageurl';
        $field->type = 'textfield';
        $field->size = 60;
        if (!is_file($CFG->dirroot.'/theme/'.current_theme().'/pix/customlabel_icons/defaultsequenceheading.jpg')){
	        $field->default = $CFG->wwwroot.'/mod/customlabel/type/sequenceheading/defaultsequenceheading.jpg';
	    } else {
	    	$field->default = $CFG->wwwroot.'/theme/'.current_theme().'/pix/customlabel_icons/defaultsequenceheading.jpg';
	    }
        $this->fields['imageurl'] = $field;

		$field = new StdClass();
        $field->name = 'overimagetext';
        $field->type = 'textfield';
        $field->size = 20;
        $this->fields['overimagetext'] = $field;

		$field = new StdClass();
        $field->name = 'imageposition';
        $field->type = 'list';
        $field->options = array('none', 'left', 'right');
        $field->default = 'left';
        $this->fields['imageposition'] = $field;

		$field = new StdClass();
        $field->name = 'verticalalign';
        $field->type = 'list';
        $field->options = array('top', 'middle', 'bottom');
        $field->default = 'top';
        $this->fields['verticalalign'] = $field;
    }

    /**
    * If exists, this method can process local alternative values for
    * realizing the template, after all standard translations have been performed. 
    * Type information structure and application context dependant.
    */
    function postprocess_data($course = null){
        global $CFG;

        // get virtual fields from course title.
        $imageurl = (empty($this->data->imageurl)) ? $this->fields['imageurl']->default : $this->data->imageurl ;
        if ($this->data->verticalalignoption == 'bottom'){
        	$valign = "50% 100%";
        	$valigncontent = "bottom";
        	$padding = 'padding-bottom:20px;';
        } elseif ($this->data->verticalalignoption == 'middle'){
        	$valign = "50% 50%";
        	$valigncontent = "middle";
        	$padding = '';
        } else {
        	$valign = "50% 0%";
        	$valigncontent = "top";
        	$padding = 'padding-top:20px;';
        }
    	if ($this->data->imagepositionoption == 'left'){
	        $this->data->imageL = "<td width=\"100\" class=\"custombox-icon-left sequenceheading\" align=\"center\" valign=\"$valigncontent\" style=\"$padding background:url({$imageurl}) $valign no-repeat transparent\">{$this->data->overimagetext}</td>";
	        $this->data->imageR = '';
	    } else if ($this->data->imagepositionoption == 'right'){
	        $this->data->imageL = '';
	        $this->data->imageR = "<td width=\"100\" class=\"custombox-icon-right sequenceheading\" align=\"center\" valign=\"$valigncontent\" style=\"$padding background:url({$imageurl}) $valign no-repeat transparent\">{$this->data->overimagetext}</td>";
	    } else {
	        $this->data->imageL = '';
	        $this->data->imageR = '';
	    }
    }
}
 
?>