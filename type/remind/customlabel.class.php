<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_remind extends customlabel_type {

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'remind';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'remindtext';
        $field->type = 'textarea';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['remindtext'] = $field;

    }

    function preprocess_data() {
    }
}

