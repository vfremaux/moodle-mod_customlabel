<?php

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 *
 *
 */

class customlabel_type_pedagogicadvice extends customlabel_type {

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'pedagogicadvice';
        $this->fields = array();
        
        $field = new StdClass;
        $field->name = 'advice';
        $field->type = 'textarea';
        $this->fields['advice'] = $field;

        $field = new StdClass;
        $field->type = 'choiceyesno';
        $field->name = 'initiallyvisible';
        $field->default = 1;
        $this->fields['initiallyvisible'] = $field;
    }

    function preprocess_data($course = null) {
        global $CFG;

        $customid = @$CFG->custom_unique_id + 1;
        set_config('custom_unique_id', $customid);

        $this->data->customid = $customid;

        $this->data->initialcontrolimage = ($this->data->initiallyvisible) ? $CFG->wwwroot.'/mod/customlabel/pix/minus.gif' : $CFG->wwwroot.'/mod/customlabel/pix/plus.gif' ;
        // For passing to javascript.

        $this->data->wwwroot = $CFG->wwwroot;
    }
}
 
