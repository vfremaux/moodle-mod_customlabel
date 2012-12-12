<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_sectionheading extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'sectionheading';
        $this->fields = array();        
        
        $field = new StdClass;
        $field->name = 'heading';
        $field->size = 80;
        $field->type = 'textfield';
        $this->fields['heading'] = $field;

        $field = new StdClass;
        $field->name = 'shortdesc';
        $field->type = 'textarea';
        $this->fields['shortdesc'] = $field;

    }
}
 
?>