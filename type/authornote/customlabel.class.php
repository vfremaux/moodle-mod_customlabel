<?php

require_once ($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
*
*
*/

class customlabel_type_authornote extends customlabel_type{

    function __construct($data){
        parent::__construct($data);
        $this->type = 'authornote';
        $this->fields = array();
        
		$field = new StdClass;
        $field->name = 'authornote';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['authornote'] = $field;

		$field = new StdClass;
        $field->type = 'choiceyesno';
        $field->name = 'initiallyvisible';
        $field->default = 1;
        $this->fields['initiallyvisible'] = $field;
    }

    function postprocess_data($course = null){
        global $CFG;
        $customid = @$CFG->custom_unique_id + 1;

        $this->data->customid = $customid;
        set_config('custom_unique_id', $customid);
        
    }
}
 
?>