<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_authordata extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'authordata';
        $this->fields = array();

        $field->name = 'authors';
        $field->type = 'textfield';
        $field->size = 80;
        $this->fields['authors'] = $field;

        unset($field);
        $field->name = 'contact';
        $field->type = 'textfield';
        $field->size = 60;
        $this->fields['contact'] = $field;

        unset($field);
        $field->name = 'organization';
        $field->type = 'textfield';
        $field->size = 120;
        $this->fields['organization'] = $field;

        unset($field);
        $field->name = 'contributors';
        $field->type = 'textfield';
        $field->size = 80;
        $this->fields['contributors'] = $field;

        unset($field);
        $field->name = 'version';
        $field->type = 'textfield';
        $field->size = 40;
        $this->fields['version'] = $field;

    }
}
 
?>