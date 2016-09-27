<?php

require_once($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
 *
 *
 */

class customlabel_type_authordata extends customlabel_type {

    public $nbauthor = 3;

    function __construct($data) {
        global $USER;

        parent::__construct($data);
        $this->type = 'authordata';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'tablecaption';
        $field->type = 'textfield';
        $this->fields['tablecaption'] = $field;

        for ($i = 1 ; $i <= $this->nbauthor ; $i++) {
            $field = new StdClass;
            $field->name = 'author'.$i;
            $field->type = 'textfield';
            $this->fields['author'.$i] = $field;

            $field = new StdClass;
            $field->name = 'thumb'.$i;
            $field->type = 'filepicker';
            $field->destination = 'url';
            $field->default = '';
            $this->fields['thumb'.$i] = $field;
        }

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
        $field->itemid = 0;
        $this->fields['contributors'] = $field;
    }
    
    function postprocess_data($course = null) {        
        for ($i = 1; $i < $this->nbauthor; $i++) {
            
            $thumb = $this->get_file_url('thumb'.$i);

            if ($thumb) {
                $this->data->{'thumb'.$i} = $thumb->out();
            }
        }
    }
}

