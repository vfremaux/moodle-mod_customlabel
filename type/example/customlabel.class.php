<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_example extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'example';
        $this->fields = array();

        $field = new StdClass;        
        $field->name = 'example';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['example'] = $field;
    }
    
    function postprocess_data($course = null) {
        global $CFG;

        $this->data->headerimage = $CFG->wwwroot.'/mod/customlabel/type/example/thumb.jpg';
    }
}
 
