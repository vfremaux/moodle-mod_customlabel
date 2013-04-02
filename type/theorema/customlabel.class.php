<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_theorema extends customlabel_type{

    function __construct($data){
    	global $CFG;
    	
        parent::__construct($data);
        $this->type = 'theorema';
        $this->fields = array();
        
		$field = new StdClass;
        $field->name = 'theorematext';
        $field->type = 'textarea';
        $field->rows = 20;
        $this->fields['theorematext'] = $field;

		if (!isset($data->corollarynum)){
			// second chance, get it from stored data
			$storeddata = ($CFG->usesafestorage) ? json_decode(base64_decode(@$this->data->safecontent)) : json_decode(@$this->data->content) ;
	        $subdefsnum = (!empty($storeddata->corollarynum)) ? $storeddata->corollarynum : 0 ;
	    } else {
	    	$subdefsnum = $data->corollarynum;
	    }

		$field = new StdClass;        
        $field->name = 'corollarynum';
        $field->type = 'textfield';
        $field->size = 4;
        $field->default = 0;
        $this->fields['corollarynum'] = $field;

		for($i = 0 ; $i < $subdefsnum; $i++){
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
    
    function preprocess_data(){

		$this->data->corollarylist = "<ul class=\"customlabel-corollaries theorema\">\n";
		for ($i = 0 ; $i < $this->data->corollarynum; $i++){		
			$key = 'corollary'.$i;
			$title = get_string('corollary'.$i, 'customlabel');
	        $this->data->corollarylist .= (isset($this->data->$key)) ? "<li class=\"custombox-corollary theorema\"><span class=\"custombox-corollary-caption theorema\">{$title}:</span><br/> {$this->data->$key}</li>\n" : '' ;
		}
		$this->data->corollarylist .= "</ul>\n";
		if ($this->data->showdemonstration){
			$this->data->rowspan = 3 + $this->data->corollarynum;
		} else {
			$this->data->rowspan = 2 + $this->data->corollarynum;
		}
    }
}
 
?>