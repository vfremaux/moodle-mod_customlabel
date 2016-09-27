<?php

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 * this defines a set of fields. You just need defining fields and add them to the class,
 * then make an HTML template that uses <%%fieldname%%> calls, using style classing, and
 * finally add a customlabel.css within the same directory
 */
class customlabel_type_formule extends customlabel_type {

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'formule';
        $this->fields = array();

        $field = new StdClass();
        $field->name = 'smalltext';
        $field->type = 'textfield';
        $field->maxlength = 80;
        $this->fields['smalltext'] = $field;

        $field = new StdClass();
        $field->name = 'parag';
        $field->type = 'textarea';
        $field->rows = 5;
        $field->cols = 40;
        $this->fields['parag'] = $field;

        $field = new StdClass();
        $field->name = 'list';
        $field->type = 'list';
        $field->options = array('opt1', 'opt2'); // this can be changed to whatever any menu_list
        $this->fields['list'] = $field;

        $field = new StdClass();
        $field->name = 'listmultiple[]';
        $field->type = 'list';
        $field->options = array('opt1', 'opt2'); // this can be changed to whatever any menu_list
        $field->multiple = 1;
        $field->size = 5;
        $this->fields['listmultiple'] = $field;

        $field = new StdClass();
        $field->name = 'lockedfield';
        $field->type = 'textfield';
        $field->maxlength = 80;
        $field->fullaccess = 0;
        $field->default = get_string('lockedsample', 'customlabel');
        $this->fields['lockedfield'] = $field;
    }
}
 
