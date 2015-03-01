<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_localgoals extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'localgoals';
        $this->fields = array();
        
        $field = new StdClass;
        $field->name = 'localgoals';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['localgoals'] = $field;
    }
    
    function postprocess_data($course = null) {
        global $CFG;

        $this->data->headerimage = $CFG->wwwroot.'/mod/customlabel/type/localgoals/thumb.jpg';
    }
}
 
