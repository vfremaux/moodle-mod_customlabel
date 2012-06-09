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
        $field->name = 'goals';
        $field->type = 'textarea';
        $this->fields['goals'] = $field;

        unset($field);
        $field->name = 'objectives';
        $field->type = 'textarea';
        $this->fields['objectives'] = $field;

		/*
        unset($field);
        $field->name = 'concepts';
        $field->type = 'textarea';
        $field->size = 80;
        $this->fields['concepts'] = $field;
        */

        unset($field);
        $field->name = 'duration';
        $field->type = 'textfield';
        $field->size = 80;
        $this->fields['duration'] = $field;

        unset($field);
        $field->name = 'teachingorganization';
        $field->type = 'textarea';
        $field->size = 80;
        $this->fields['teachingorganization'] = $field;

        unset($field);
        $field->name = 'prerequisite';
        $field->type = 'textarea';
        $this->fields[] = $field;

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

    /// have to save back datasource information within tables

        // remove all old classification
        delete_records('customlabel_course_metadata', 'courseid', $COURSE->id);

        // add updated learning method
        $cc->courseid = $COURSE->id;
        if (!empty($this->data->learningmethod)){
            if (is_array($this->data->learningmethod)){
                foreach($this->data->learningmethod as $method){
                    $cc->value = $method;
                    if (!insert_record('customlabel_course_metadata', $cc)){
                        notice("Could not classify course");
                    }
                }
            } else {
                $cc->value = $this->data->learningmethod;
                if (!insert_record('customlabel_course_metadata', $cc)){
                    notice("Could not classify course");
                }
            }
        }
    }
}

?>