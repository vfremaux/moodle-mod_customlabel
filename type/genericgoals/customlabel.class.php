<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_genericgoals extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'genericgoals';
        $this->fields = array();
        
		$field = new StdClass();        
        $field->name = 'goals';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['goals'] = $field;
    }
    
    function postprocess_data($course = null){
        global $CFG;

        $this->data->sideimage = $CFG->wwwroot.'/mod/customlabel/type/'.$this->type.'/thumb.jpg';
    }
}
 
?>