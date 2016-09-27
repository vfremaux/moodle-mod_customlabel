<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_information extends customlabel_type {

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'information';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'informationtext';
        $field->type = 'textarea';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['informationtext'] = $field;
    }

    function preprocess_data() {
    }
}

