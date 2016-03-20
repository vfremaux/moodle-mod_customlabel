<?php

require_once ($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
*
*
*/

class customlabel_type_text extends customlabel_type {

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'text';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'textcontent';
        $field->type = 'textarea';
        $field->itemid = 0;
        $field->lines = 20;
        $field->default = '';
        $this->fields['textcontent'] = $field;

        $field = new StdClass;
        $field->name = 'readmorecontent';
        $field->type = 'textarea';
        $field->itemid = 1;
        $field->lines = 20;
        $field->default = '';
        $this->fields['readmorecontent'] = $field;

        $field = new StdClass;
        $field->name = 'initiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 1;
        $this->fields['initiallyvisible'] = $field;
    }

    function preprocess_data() {
        global $CFG;

        $customid = @$CFG->custom_unique_id + 1;
        if (!empty($this->data->initiallyvisible)) {
            $this->data->initialstring = get_string('readless', 'customlabeltype_text');
        } else {
            $this->data->initialstring = get_string('readmore', 'customlabeltype_text');
        }
        $this->data->customid = $customid;
        set_config('custom_unique_id', $customid);
    }
}
 
