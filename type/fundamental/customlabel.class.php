<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_fundamental extends customlabel_type {

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'fundamental';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'fundamentaltext';
        $field->type = 'textarea';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['fundamentaltext'] = $field;
    }

}

