<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_tipsandtricks extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'tipsandtricks';
        $this->fields = array();
        
        $field->name = 'tipsandtricks';
        $field->type = 'textarea';
        $this->fields['tipsandtricks'] = $field;
    }

    function postprocess_data($course = null){
        global $CFG;

        $this->data->headerimage = $CFG->wwwroot.'/mod/customlabel/type/tipsandtricks/thumb.jpg';
    }
}
 
?>