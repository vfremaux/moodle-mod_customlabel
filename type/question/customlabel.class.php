<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_question extends customlabel_type {

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'question';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'questiontext';
        $field->type = 'textarea';
        $field->rows = 20;
        $field->itemid = 0;
        $this->fields['questiontext'] = $field;

        $field = new StdClass;
        $field->name = 'hint';
        $field->type = 'textarea';
        $field->rows = 20;
        $field->itemid = 1;
        $this->fields['hint'] = $field;

        $field = new StdClass;
        $field->name = 'hintinitiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 0;
        $this->fields['hintinitiallyvisible'] = $field;

        $field = new StdClass;
        $field->name = 'answertext';
        $field->type = 'textarea';
        $field->rows = 20;
        $field->itemid = 2;
        $this->fields['answertext'] = $field;

        $field = new StdClass;
        $field->name = 'initiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 0;
        $this->fields['initiallyvisible'] = $field;
    }

    function preprocess_data() {
        global $CFG;

        $customid = @$CFG->custom_unique_id + 1;

        $this->data->initialcontrolimage = ($this->data->initiallyvisible) ? $CFG->wwwroot.'/mod/customlabel/pix/minus.gif' : $CFG->wwwroot.'/mod/customlabel/pix/plus.gif' ;
        $this->data->hintinitialcontrolimage = ($this->data->hintinitiallyvisible) ? $CFG->wwwroot.'/mod/customlabel/pix/minus.gif' : $CFG->wwwroot.'/mod/customlabel/pix/plus.gif' ;
        $this->data->wwwroot = $CFG->wwwroot;
        $this->data->customid = $customid;
        set_config('custom_unique_id', $customid);
    }
}

