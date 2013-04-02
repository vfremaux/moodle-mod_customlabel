<?php

/**
* @package mod-customlabel
* @category mod
* @author Valery Fremaux
* @date 02/12/2007
*
* A generic class for collecting all that is common to all elements
*/

// TODO : check if there is not a legacy post install function in module API
if (!isset($CFG->classification_type_table)){
	set_config('classification_type_table', 'customlabel_mtd_type');
	set_config('classification_value_table', 'customlabel_mtd_value');
	set_config('classification_value_type_key', 'typeid');
	set_config('classification_constraint_table', 'customlabel_mtd_constraint');
	set_config('course_metadata_table', 'customlabel_course_metadata');
	set_config('course_metadata_value_key', 'valueid');
	set_config('course_metadata_course_key', 'courseid');
}

class customlabel_type{
    var $title;
    var $type;
    var $fields;
    var $data;
    var $fullaccess;
    
    /**
    * A customlabel has a type
    * A custom label is made of fields objects within an array. A field
    * determines the nature of the data and the way it can be input.
    */
    function __construct($data){
       $this->title = $data->title;
       $this->type = 'undefined';
       $this->fields = array();
       $this->data = clone($data);
       $this->fullaccess = true;
    }
    
    /**
    * makes a suitable options list for available options
    * for a list field.
    */
    function get_options($fieldname){
        if (!array_key_exists($fieldname, $this->fields)){
            return array();
        }

        if (empty($this->fields[$fieldname]->options)){
            return array();
        }
        
        //get all code / translations for the option list
        $options = array();
        foreach($this->fields[$fieldname]->options as $option){
        	if (@$this->fields[$fieldname]->straightoptions){
        		$options[$option] = $option;
        	} else {
	            $options[$option] = get_string($option, 'customlabel');
	        }
        }
        return $options;
    }

    /**
    * makes a suitable options list for an external datasource.
    * @return an array of displayable options
    */
    function get_datasource_options(&$field){
        global $CFG, $USER, $COURSE;
        
        $options = array();
        
        switch($field->source){
            case 'dbfieldkeyed':{
                $table = $CFG->prefix.$field->table;
                $fieldname = $field->field;
                $fieldkey = (empty($field->key)) ? 'id' : $field->key ;
                $select = (!empty($field->select)) ? " WHERE {$field->select} " : '' ;
                $ordering = (!empty($field->ordering)) ? " ORDER BY $field->ordering " : '' ;
                
                $sql = "
                    SELECT 
                        `$fieldkey`, 
                        `$fieldname`
                    FROM
                        `$table`
                        $select
                    $ordering
                ";
                $options = get_records_sql_menu($sql);
            }
            break; 
            case 'dbfieldkey':{
                $table = $CFG->prefix.$field->table;
                $fieldname = $field->field;
                $select = (!empty($field->select)) ? " WHERE {$field->select} " : '' ;
                $ordering = (!empty($field->ordering)) ? " ORDER BY $field->ordering " : '' ;
                
                $sql = "
                    SELECT DISTINCT
                        id,
                        `$fieldname`
                    FROM
                        `$table`
                        $select
                    $ordering
                ";
                $keys = get_records_sql_menu($sql);
                
                $domain = (empty($field->domain)) ? 'customlabel' : $field->domain ;
                foreach(array_values($keys) as $key){
                    $options[$key] = get_string($key, $domain);
                }
            }
            break; 
            case 'function':{
                if (!empty($field->file) && file_exists($CFG->dirroot.$field->file)){
                    include_once $CFG->dirroot.$field->file;
                }
                $functionname = $field->function;
                $options = $functionname();
            }
            break; 
        }        

        return $options;
    }
    
    /**
    *
    *
    */
    function get_datasource_values($field, $values){
        global $CFG, $USER, $COURSE;
        
        if (is_array($values))
            $valuelist = implode("','", $values);
        else 
            $valuelist = $values;
        
        $table = $CFG->prefix.$field->table;
        $fieldname = $field->field;
        $fieldkey = (empty($field->key)) ? 'id' : $field->key ;
        $select = " WHERE `$fieldkey` IN ('$valuelist') ";

        $output = array();

        $sql = "
            SELECT 
                `{$fieldkey}`, 
                `{$fieldname}`
            FROM
                `$table`
                $select
        ";
        $results = get_records_sql_menu($sql);
        if ($results){
            foreach($results as $result){
                $output[] = $result;
            }
        }
        
        return $output;
    }
    
    /**
    * preprocesses data values before standard internal transforms are operated.
    * will deal with incoming values from the mod.html form.
    * Usual customlabels will not overload this function, unless
    * some form information must be fetched based on form results. 
    * It can be used for storing collected information in additional
    * locations in the database.
    *
    */
    function preprocess_data(){
    }

    /**
    * postprocesses data values to get definitive data object.
    * Usual customlabels will not overload this function, unless
    * some form information must be fetched or computed after
    * internal standard transforms have been processed. 
    *
    */
    function postprocess_data(){
    }

    /**
    *
    *
    */
    function get_name($lang='') {
        // $textlib = textlib_get_instance();
    
        $this->data->currenttheme = current_theme();
        $this->data->title = $this->title;        
        
        $name = $this->make_template($lang);
    
        if (empty($name)) {
            // arbitrary name
            $name = "customlabel{$customlabel->instance}";
        }
    
        return $name;
    }
    
    /**
    * realizes the template (the standard way is to compile content fields 
    * in a HTML template. 
    */
    function make_template($lang=''){
        global $CFG, $USER;
        
        $content = '';
        
        if (!empty($lang)){
             $languages[] = $lang;
        } else {
            if (strpos(@$CFG->textfilters, 'filter/multilang') !== false){ // we have multilang
                $languages = array_keys(get_list_of_languages());
            } else {
                $languages[] = current_language();
            }
        }
        $content = '';
        foreach($languages as $lang){
        	$multilang = substr($lang, 0 , 2);
            $template = customlabel_type::get_template($this->type, $lang);
            
            if (!$template && !empty($CFG->defaultlang)){
                $template = customlabel_type::get_template($this->type, $CFG->defaultlang);
            }

            if ($template){
                $contentlang = "<span class=\"multilang\" lang=\"$multilang\" >";
                $contentlang .= $this->process_conditional($template);
                $contentlang .= "</span>";
                $contentlang = str_replace("'", "\\'", $contentlang);
                if (!empty($this->data)){
                	$CFG->multilang_target_language = $lang;
                    foreach($this->data as $key => $value){
                        $contentlang = str_replace("<%%{$key}%%>", filter_string($value), $contentlang);
                    }
                    // cleanup any unused tags
                    $contentlang = preg_replace("/<%%.*?%%>/", '', $contentlang);
                	unset($CFG->multilang_target_language);
                }
            } else {
                $contentlang = "<span class=\"multilang\" lang=\"$multilang\" >";
                $contentlang .= get_string('nocontentforthislanguage', 'customlabel');
                $contentlang .= "</span>";
            }
            $content .= $contentlang;
        }
        
        return $content;
    }
    
    /**
    * post processes fields for rendering in templates
    */
    function process_form_fields(){
    
        // assembles multiple list answers
        foreach($this->fields as $key => $field){
            $name = str_replace('[]', '', $field->name);
            if (preg_match("/list$/", $field->type)){
                if (@$field->multiple){
    
                    if (!function_exists('get_string_for_list')){
                        function get_string_for_list(&$a){
                            $a = get_string($a, 'customlabel');
                        }
                    }
    
                    $valuearray = @$this->data->{$name};
                    if (is_array($valuearray)){
                        if (!empty($valuearray)){
                            array_walk($valuearray, 'get_string_for_list');
                            if ($field->type == 'vlist')
                                $this->data->{$name} = implode('<br/>', $valuearray);
                            else
                                $this->data->{$name} = implode(', ', $valuearray);
                        }
                    } else {
                        if (!empty($this->data->{$name})){
                            $this->data->{$name} = get_string($this->data->{$name}, 'customlabel');
                        }
                    }
                } else {
                    $name = $field->name;
                    $nameoption = "{$name}option";
                    if (!empty($this->data->{$name})){
	                    $this->data->{$nameoption} = $this->data->{$name};
	                    $this->data->{$name} = get_string($this->data->{$name}, 'customlabel');
	                } else {
	                    $this->data->{$nameoption} = '';
	                    $this->data->{$name} = '';
	                }
                }
                $this->data->{$name} = str_replace("'", "\\'", @$this->data->{$name});
            }
        }
    }

    /**
    * post processes datasource fields for rendering in templates
    */
    function process_datasource_fields(){
    
        // assembles multiple list answers
        foreach($this->fields as $key => $field) {

            // check string domain if inexistant
            $domain = (empty($field->domain)) ? 'customlabel' : $field->domain ;
            $sep = ($field->type == 'vdatasource') ? '<br/>' : ', ' ;

            if (preg_match("/datasource$/", $field->type)) {
                if (@$field->multiple){    
                    $name = str_replace('[]', '', $field->name);
                    $valuearray = @$this->data->{$name};
                    if (is_array($valuearray)){
                        if (!empty($valuearray)){
                            if ($field->source == 'dbfieldkeyed'){
                                $this->data->{$name} = implode($sep, $this->get_datasource_values($field, $valuearray));
                            }
                            else if ($field->source == 'dbfieldkey'){
                                foreach($valuearray as $value){
                                    $valuearraystr[] = get_string($value, $domain);
                                }
                                $this->data->{$name} = implode($sep, $valuearraystr);
                            }
                            else if ($field->source == 'function'){
                                if (!empty($field->source) && file_exists($CFG->dirroot.$field->source)){
                                    include_once $CFG->dirroot.$field->source;
                                }
                                $functionname = $field->function;
                                $this->data->{$name} = $functionname($valuearray);
                            }
                        }
                    } else {
                        if (!empty($this->data->{$name})){
                            $this->data->{$name} = get_string($this->data->{$name}, 'customlabel');
                        }
                    }
                } else {
                    $name = $field->name;
                    $nameoption = "{$name}option";
                    $this->data->{$nameoption} = @$this->data->{$name};
                    if ($field->source == 'dbfieldkeyed') {
                        $this->data->{$name} = $this->get_datasource_values($field, @$this->data->{$name});
                    } else if ($field->source == 'dbfieldkey') {
                        $this->data->{$name} = get_string($this->data->{$name}, $domain);
                    } else if ($field->source == 'function') {
                        // function must have an optional first argument that can be scalar or array
                        if (!empty($field->source) && file_exists($CFG->dirroot.$field->source)){
                            include_once $CFG->dirroot.$field->source;
                        }
                        $functionname = $field->function;
                        $this->data->{$name} = $functionname(@$this->data->{$name});
                    }
                }
                $this->data->{$name} = str_replace("'", "\\'", @$this->data->{$name});
            }
        }
    }

    function get_xml(){
        global $CFG;

        require_once $CFG->libdir."/pear/HTML/AJAX/JSON.php";

        $content = json_decode($this->data->content);
        
        $xml = "<datablock>\n";
        $xml .= "\t<instance>\n";
        $xml .= "\t\t<labeltype>{$this->type}</labeltype>\n";
        $xml .= "\t\t<title>{$this->title}</title>\n";
        $xml .= "\t</instance>\n";        
        $xml .= "\t<content>\n";
        foreach($this->fields as $field){
            $fieldvalue = '';
            $fieldname = $field->name;
            $xml .= "\t\t<{$fieldname}>";
            if (preg_match("/list$/", $field->type) && !empty($field->multiple)){
                if (is_array(@$content->{$fieldname})){
                    $fieldvalue = implode (',', $content->{$fieldname});
                }
            } else {
                $fieldvalue = @$content->{$fieldname};
                $fieldvalue = str_replace("\\'", "'", $fieldvalue);
            }
            $xml .= $fieldvalue;
            $xml .= "</$fieldname>\n";
        }
        $xml .= "\t</content>\n";        
        $xml .= '</datablock>';
        return $xml;        
        
        return '';
    }

	function on_delete(){
	}

    /**
    * loads a template and caches it in static database for reuse
    */
    static function get_template($type, $lang){
    	global $CFG;

    	static $templates;

        if (!isset($templates[$type][$lang])){
        	// allow override of templates using theme localisation
        	if (is_file($CFG->dirroot .'/theme/'.current_theme()."/mod/customlabel/type/{$type}/{$lang}/template.tpl")){
            	$templatefile = $CFG->dirroot .'/theme/'.current_theme()."/mod/customlabel/type/{$type}/{$lang}/template.tpl";
        	} else {
            	$templatefile = $CFG->dirroot ."/mod/customlabel/type/{$type}/{$lang}/template.tpl";
        	}
            if (file_exists($templatefile)){
                $templates[$type][$lang] = implode('', file($templatefile));
            } else {
                return null;
            }
        }
        return $templates[$type][$lang];         
    }
    
    function pre_update(){
    }

    function post_update(){
    }

    function process_conditional($template){
    	
    	if (!preg_match('/<%if /', $template)) return $template; // quick return for unconditional templates
    	
        $search = '/(.*?)<%if %%(.*?)%%\s+%>(.*?)<%endif\s+%>(.*)$/is';
        $buffer = '';
        $matches = array();
        $matches[4] = '';
    	while (preg_match($search, $template, $matches)){
    		$buffer .= $matches[1]; // prefix
    		$test = $matches[2]; // test variable
    		$casecontent = $matches[3];
    		$suffix = $matches[4];
    		if ($test){
    			// form 1 : simple boolean form
    			if (preg_match('/[a-z_]+[0-9a-z_]*/', $test)){

					// fix testvar name for lists
    				if (@$this->fields[$test]->type == 'list'){
    					$testvar = $test.'option';
    				} else {
    					$testvar = $test;
    				}
    				
		    		if (!empty($this->data->$testvar)){
		    			$buffer .= $casecontent;
		    		}
		    	}
    			// form 2 : numeric comparisons
    			if (preg_match('/([a-z_]+[0-9a-z_])*\s*([>=<]+)\s*(.*)/', $test, $matches)){
    				$testvar = $matches[1];
    				$testop = $matches[2];
    				$testarg = $matches[3];
    				
					// fix testvar name for lists
    				if (@$this->fields[$testvar]->type == 'list'){
    					$testvar = $testvar.'option';
    				}
    				
    				//fix string argument: eliminates quoting
    				preg_replace('/[\'"](.*)[\'"]/', "$1", $testarg);
    				
    				// make test
    				$accept = false;
					debug_trace("check : {$this->data->$testvar} $testop $testarg ");
    				switch($testop){
    					case '==' :{
    						$accept = (@$this->data->$testvar == $testarg);
    						break;
    					}
    					case '<=' :{
    						$accept = (@$this->data->$testvar <= $testarg);
    						break;
    					}
    					case '>=' :{
    						$accept = (@$this->data->$testvar >= $testarg);
    						break;
    					}
    					case '<' :{
    						$accept = (@$this->data->$testvar < $testarg);
    						break;
    					}
    					case '>' :{
    						$accept = (@$this->data->$testvar > $testarg);
    						break;
    					}
    				}
    				
		    		if ($accept){
		    			$buffer .= $casecontent;
		    		}
		    	}
	    	}
    		$test = $casecontent; // conditional content
    		$template = $suffix;
    	}
		$buffer .= $template;

		return $buffer;
    }
}

?>