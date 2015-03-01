<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_seealso extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'seealso';
        $this->fields = array();
        
        $field = new StdClass;
        $field->name = 'seealso';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['seealso'] = $field;
    }
    
    function postprocess_data($course = null) {
        global $CFG;

        $this->data->headerimage = $CFG->wwwroot.'/mod/customlabel/type/seealso/thumb.jpg';
    }
}
 
