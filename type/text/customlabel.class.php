<?php

require_once ($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
*
*
*/

class customlabel_type_text extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'text';
        $this->fields = array();
        
        $field = new StdClass;
        $field->name = 'textcontent';
        $field->type = 'textarea';
        $field->lines = 20;
        $this->fields['textcontent'] = $field;
    }
    
    function preprocess_data() {
    }
}
 
