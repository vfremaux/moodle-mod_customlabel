<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_keypoints extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'keypoints';
        $this->fields = array();

        $storeddata = json_decode(base64_decode(@$this->data->content));
        
        $keypointnum = (!empty($storeddata->keypointnum)) ? $storeddata->keypointnum : 3 ;
        
        $field = new StdClass;        
        $field->name = 'keypointnum';
        $field->type = 'textfield';
        $field->size = 4;
        $field->default = 3;
        $this->fields['keypointnum'] = $field;

        for ($i = 0 ; $i < $keypointnum; $i++) {
            $field = new StdClass;
            $field->name = 'keypointitem'.$i;
            $field->type = 'textarea';
            $field->size = 60;
            $this->fields['keypointitem'.$i] = $field;
        }
    }
    
    function preprocess_data($course = null) {
        global $CFG;

        $this->data->keypointslist = "<ul class=\"customlabel keypoints\">\n";
        for ($i = 0 ; $i < $this->data->keypointnum; $i++) {
            $key = 'keypointitem'.$i;
            $this->data->keypointslist .= (isset($this->data->$key)) ? '<li>'.$this->data->$key."</li>\n" : '' ;
        }
        $this->data->keypointslist .= "</ul>\n";
    }
}
 
