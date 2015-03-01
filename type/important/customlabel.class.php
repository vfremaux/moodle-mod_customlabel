<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_important extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'important';
        $this->fields = array();
        
        $field = new StdClass;
        $field->name = 'importantnote';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['importantnote'] = $field;
    }
}
 
