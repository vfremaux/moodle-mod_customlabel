<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_coursedata extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'coursedata';
        $this->fields = array();

		$field = new StdClass;
        $field->name = 'tablecaption';
        $field->type = 'textfield';
        $this->fields['tablecaption'] = $field;

		$field = new StdClass;
        $field->name = 'showgoals';
        $field->type = 'choiceyesno';
        $this->fields['showgoals'] = $field;

		$field = new StdClass;
        $field->name = 'goals';
        $field->type = 'textarea';
        $this->fields['goals'] = $field;

		$field = new StdClass;
        $field->name = 'showobjectives';
        $field->type = 'choiceyesno';
        $this->fields['showobjectives'] = $field;

		$field = new StdClass;
        $field->name = 'objectives';
        $field->type = 'textarea';
        $this->fields['objectives'] = $field;

		$field = new StdClass;
        $field->name = 'showconcepts';
        $field->type = 'choiceyesno';
        $this->fields['showconcepts'] = $field;

		$field = new StdClass;
        $field->name = 'concepts';
        $field->type = 'textarea';
        $field->size = 80;
        $this->fields['concepts'] = $field;

		$field = new StdClass;
        $field->name = 'showduration';
        $field->type = 'choiceyesno';
        $this->fields['showduration'] = $field;

		$field = new StdClass;
        $field->name = 'duration';
        $field->type = 'textfield';
        $field->size = 80;
        $this->fields['duration'] = $field;

		$field = new StdClass;
        $field->name = 'showteachingorganization';
        $field->type = 'choiceyesno';
        $this->fields['showteachingorganization'] = $field;

		$field = new StdClass;
        $field->name = 'teachingorganization';
        $field->type = 'textarea';
        $field->size = 80;
        $this->fields['teachingorganization'] = $field;

		$field = new StdClass;
        $field->name = 'showprerequisites';
        $field->type = 'choiceyesno';
        $this->fields['showprerequisites'] = $field;

		$field = new StdClass;
        $field->name = 'prerequisites';
        $field->type = 'textarea';
        $this->fields['prerequisites'] = $field;

		$field = new StdClass;
        $field->name = 'showfollowers';
        $field->type = 'choiceyesno';
        $this->fields['showfollowers'] = $field;

		$field = new StdClass;
        $field->name = 'followers';
        $field->type = 'textarea';
        $this->fields['followers'] = $field;
    }

    /**
    *
    *
    */
    function preprocess_data(){
        global $COURSE, $CFG;

        $this->data->coursecode = str_replace("'", "\\'", $COURSE->idnumber);

    }
}

?>