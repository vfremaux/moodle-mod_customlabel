<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_definition extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'definition';
        $this->fields = array();
        
        $field = new StdClass;
        $field->name = 'definition';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['definition'] = $field;

        if (!isset($data->subdefsnum)) {
            // second chance, get it from stored data
            $storeddata = json_decode(base64_decode(@$this->data->content));            
            $subdefsnum = (!empty($storeddata->subdefsnum)) ? $storeddata->subdefsnum : 0 ;
        } else {
            $subdefsnum = $data->subdefsnum;
        }

        $field = new StdClass;
        $field->name = 'subdefsnum';
        $field->type = 'list';
        $field->options = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10');
        $field->straightoptions = true;
        $this->fields['subdefsnum'] = $field;

        for ($i = 0 ; $i < $subdefsnum; $i++) {
            $field = new StdClass;
            $field->name = 'subdef'.$i;
            $field->type = 'textarea';
            $field->size = 60;
            $this->fields['subdef'.$i] = $field;
        }
    }
    
    function preprocess_data() {

        $this->data->hassubdeflist = 0;
        $this->data->subdeflist = "<ul class=\"customlabel-subdefinition definition\">\n";
        for ($i = 0 ; $i < $this->data->subdefsnum; $i++) {        
            $key = 'subdef'.$i;
            $this->data->subdeflist .= (isset($this->data->$key)) ? '<li>'.$this->data->$key."</li>\n" : '' ;
            $this->data->hassubdeflist = 1;
        }
        $this->data->subdeflist .= "</ul>\n";
    }
}
 
