<?php

require_once($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
 *
 *
 */

class customlabel_type_authordata extends customlabel_type {

    function __construct($data) {
        global $USER;

        parent::__construct($data);
        $this->type = 'authordata';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'tablecaption';
        $field->type = 'textfield';
        $this->fields['tablecaption'] = $field;

        $field = new StdClass;
        $field->name = 'author1';
        $field->type = 'textfield';
        $this->fields['author1'] = $field;

        $field = new StdClass;
        $field->name = 'thumb1';
        $field->type = 'filepicker';
        $field->destination = 'url';
        $field->default = '';
        $this->fields['thumb1'] = $field;

        $field = new StdClass;
        $field->name = 'author2';
        $field->type = 'textfield';
        $this->fields['author2'] = $field;

        $field = new StdClass;
        $field->name = 'thumb2';
        $field->type = 'filepicker';
        $field->destination = 'url';
        $field->default = '';
        $this->fields['thumb2'] = $field;

        $field = new StdClass;
        $field->name = 'author3';
        $field->type = 'textfield';
        $this->fields['author3'] = $field;

        $field = new StdClass;
        $field->name = 'thumb3';
        $field->type = 'filepicker';
        $field->destination = 'url';
        $field->default = '';
        $this->fields['thumb3'] = $field;

        $field = new StdClass;
        $field->name = 'showinstitution';
        $field->type = 'choiceyesno';
        $this->fields['showinstitution'] = $field;

        $field = new StdClass;
        $field->name = 'institution';
        $field->type = 'textfield';
        $field->default = @$USER->institution;
        $this->fields['institution'] = $field;

        $field = new StdClass;
        $field->name = 'showdepartment';
        $field->type = 'choiceyesno';
        $this->fields['showdepartment'] = $field;

        $field = new StdClass;
        $field->name = 'department';
        $field->type = 'textfield';
        $field->default = @$USER->department;
        $this->fields['department'] = $field;

        $field = new StdClass;
        $field->name = 'showcontributors';
        $field->type = 'choiceyesno';
        $this->fields['showcontributors'] = $field;

        $field = new StdClass;
        $field->name = 'contributors';
        $field->type = 'textarea';
        $this->fields['contributors'] = $field;

    }
}

