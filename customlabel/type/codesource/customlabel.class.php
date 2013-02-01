<?php

include $CFG->dirroot."/mod/customlabel/type/customtype.class.php";

/**
*
*
*/

class customlabel_type_codesource extends customlabel_type{

    function customlabel_type_codesource(){
        $this->type = 'codesource';
        $this->fields = array();
        
        unset($field);
        $field->name = 'code';
        $field->type = 'list';
        $field->options = array('c++', 'c#', 'css', 'delphi', 'java','javascript','php','python','ruby','sql','vb','html','xml'); // this can be changed to whatever any menu_list
        $this->fields['code'] = $field;
        
        
        unset($field);
        $field->name = 'texte';
        $field->type = 'textarea';
        $field->rows = 10;
        $field->cols = 60;
        $this->fields['texte'] = $field;


    }
}
 
?>