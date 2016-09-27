<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_method extends customlabel_type {

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'method';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'methodtext';
        $field->type = 'textarea';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['methodtext'] = $field;

    }
}

