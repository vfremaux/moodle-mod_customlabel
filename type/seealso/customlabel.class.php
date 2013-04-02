<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_seealso extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'seealso';
        $this->fields = array();
        
        $field->name = 'seealso';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['seealso'] = $field;
    }
    
}
 
?>