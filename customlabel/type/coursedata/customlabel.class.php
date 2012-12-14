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

        unset($field);
        $field->name = 'tablecaption';
        $field->type = 'textfield';
        $this->fields['tablecaption'] = $field;

        unset($field);
        $field->name = 'showgoals';
        $field->type = 'choiceyesno';
        $this->fields['showgoals'] = $field;

        unset($field);
        $field->name = 'goals';
        $field->type = 'textarea';
        $this->fields['goals'] = $field;

        unset($field);
        $field->name = 'showobjectives';
        $field->type = 'choiceyesno';
        $this->fields['showobjectives'] = $field;

        unset($field);
        $field->name = 'objectives';
        $field->type = 'textarea';
        $this->fields['objectives'] = $field;

        unset($field);
        $field->name = 'showconcepts';
        $field->type = 'choiceyesno';
        $this->fields['showconcepts'] = $field;

        unset($field);
        $field->name = 'concepts';
        $field->type = 'textarea';
        $field->size = 80;
        $this->fields['concepts'] = $field;

        unset($field);
        $field->name = 'showduration';
        $field->type = 'choiceyesno';
        $this->fields['showduration'] = $field;

        unset($field);
        $field->name = 'duration';
        $field->type = 'textfield';
        $field->size = 80;
        $this->fields['duration'] = $field;

        unset($field);
        $field->name = 'showteachingorganization';
        $field->type = 'choiceyesno';
        $this->fields['showteachingorganization'] = $field;

        unset($field);
        $field->name = 'teachingorganization';
        $field->type = 'textarea';
        $field->size = 80;
        $this->fields['teachingorganization'] = $field;

        unset($field);
        $field->name = 'showprerequisites';
        $field->type = 'choiceyesno';
        $this->fields['showprerequisites'] = $field;

        unset($field);
        $field->name = 'prerequisites';
        $field->type = 'textarea';
        $this->fields['prerequisites'] = $field;

        unset($field);
        $field->name = 'showfollowers';
        $field->type = 'choiceyesno';
        $this->fields['showfollowers'] = $field;

        unset($field);
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