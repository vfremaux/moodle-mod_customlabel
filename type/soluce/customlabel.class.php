<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_soluce extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'soluce';
        $this->fields = array();
        
        $field = new StdClass();
        $field->name = 'soluce';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['soluce'] = $field;

        $field = new StdClass();
        $field->type = 'choiceyesno';
        $field->name = 'initiallyvisible';
        $field->default = 1;
        $this->fields['initiallyvisible'] = $field;
    }
    
    function preprocess_data($course = null) {
        global $CFG;
        
        $customid = @$CFG->custom_unique_id + 1;

        $this->data->initialcontrolimage = ($this->data->initiallyvisible) ? $CFG->wwwroot.'/mod/customlabel/pix/minus.gif' : $CFG->wwwroot.'/mod/customlabel/pix/plus.gif' ;
        $this->data->wwwroot = $CFG->wwwroot;
        $this->data->customid = $customid;
        set_config('custom_unique_id', $customid);
    }
}
 
