<?php

require_once ($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
*
*
*/

class customlabel_type_commentbox extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'commentbox';
        $this->fields = array();
        
        $field = new StdClass;
        $field->name = 'comment';
        $field->type = 'textarea';
        $this->fields['comment'] = $field;
    }
}
 
