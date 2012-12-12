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
    }
}
 
?>