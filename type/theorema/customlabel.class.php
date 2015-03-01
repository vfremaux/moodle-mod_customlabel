<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_theorema extends customlabel_type {

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'theorema';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'theorema';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['theorema'] = $field;

        if (!isset($data->corollarynum)) {
            // second chance, get it from stored data
            $storeddata = json_decode(base64_decode(@$this->data->content));
            $subdefsnum = (!empty($storeddata->corollarynum)) ? $storeddata->corollarynum : 0;
        } else {
            $subdefsnum = $data->corollarynum;
        }

        $field = new StdClass;
        $field->name = 'corollarynum';
        $field->type = 'textfield';
        $field->size = 4;
        $field->default = 0;
        $this->fields['corollarynum'] = $field;

        for ($i = 0 ; $i < $subdefsnum; $i++) {
            $field = new StdClass;
            $field->name = 'corollary'.$i;
            $field->type = 'textarea';
            $field->size = 60;
            $this->fields['corollary'.$i] = $field;
        }

        $field = new StdClass;
        $field->name = 'showdemonstration';
        $field->type = 'choiceyesno';
        $this->fields['showdemonstration'] = $field;

        $field = new StdClass;
        $field->name = 'demonstration';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['demonstration'] = $field;
    }

    function preprocess_data() {

        $this->data->corollarylist = "<ul class=\"customlabel-corollaries theorema\">\n";
        for ($i = 0 ; $i < $this->data->corollarynum; $i++) {
            $key = 'corollary'.$i;
            $title = get_string('corollary', 'customlabeltype_theorema').' '.($i + 1).' ';
            $this->data->corollarylist .= (isset($this->data->$key)) ? "<li><i>{$title} :</i> {$this->data->$key}</li>\n" : '';
        }
        $this->data->corollarylist .= "</ul>\n";
    }
}

