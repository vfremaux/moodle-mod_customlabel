<?php

/**
* @package mod-customlabel
* @category mod
* @author Valery Fremaux
* @date 02/12/2007
*
* A generic class for collecting all that is common to all elements
*/

class customlabel_type{
    var $title;
    var $type;
    var $fields;
    var $data;
    var $fullaccess;
    var $content;
    /**
    * A customlabel has a type
    * A custom label is made of fields objects within an array. A field
    * determines the nature of the data and the way it can be input.
    */
    function __construct($data, $type = 'undefined', $content = ''){
       $this->title = @$data->title;
       $this->type = $type;
       $this->fields = array();
       $this->data = $data;
       $this->content = $content;
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
            $options[$option] = get_string($option, 'customlabeltype_'.$this->type);
        }
        return $options;
    }

    /**
    * makes a suitable options list for an external datasource.
    * @return an array of displayable options
    */
    function get_datasource_options(&$field){
        global $CFG, $USER, $COURSE, $DB;
        
        $options = array();
        switch($field->source){
            case 'dbfieldkeyed':{
                $table = '{'.$field->table.'}';
                $fieldname = $field->field;
                $fieldkey = (empty($field->key)) ? 'id' : $field->key ;
                $select = (!empty($field->select)) ? " WHERE {$field->select} " : '' ;
                $ordering = (!empty($field->ordering)) ? " ORDER BY $field->ordering " : '' ;
                $sql = "
                    SELECT 
                        `$fieldkey`, 
                        `$fieldname`
                    FROM
                        $table
                        $select
                    $ordering
                ";
                $options = $DB->get_records_sql_menu($sql);
            }
            break; 
            case 'dbfieldkey':{
                $table = '{'.$field->table.'}';
                $fieldname = $field->field;
                $select = (!empty($field->select)) ? " WHERE {$field->select} " : '' ;
                $ordering = (!empty($field->ordering)) ? " ORDER BY $field->ordering " : '' ;
                $sql = "
                    SELECT DISTINCT
                        id,
                        `$fieldname`
                    FROM
                        $table
                        $select
                    $ordering
                ";
                $keys = $DB->get_records_sql_menu($sql);
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
        global $CFG, $USER, $COURSE, $DB;

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
        $results = $DB->get_records_sql_menu($sql);
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

    function get_content() {
    	return $this->data->processedcontent;
    }

    /**
    * @param string $lang if set, will compile only content for this language. If not set and multilang filtering is on, 
    * will compile as many versions of templates per installed language, pursuant proper template is available. 
    *
    */
    function make_content($lang = '') {
    	global $DB, $PAGE;

        $this->preprocess_data(); // hooking to subclasses specialization
        $this->process_form_fields();
        $this->process_datasource_fields();

        $this->data->currenttheme = $PAGE->theme->name;
        $this->data->title = $this->title;
        $this->content = $this->make_template($lang);
        if (empty($this->content)) {
            // arbitrary name
            $content = "customlabel{$customlabel->instance}";
            return $content;
        }

       	$this->postprocess_data(); // hooking to subclasses specialization

        return $this->content;
    }

    /**
    * realizes the template (the standard way is to compile content fields 
    * in a HTML template. 
    */
    function make_template($lang = ''){
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
            $template = $this->get_template($lang);
            if (!$template && !empty($CFG->defaultlang)){
                $template = $this->get_template($CFG->defaultlang);
            }
            
            if ($template){
                $contentlang = "<span class=\"multilang\" lang=\"$lang\" >";
                $contentlang .= $template;
                $contentlang .= "</span>";
                // $contentlang = str_replace("'", "\\'", $contentlang);
                if (!empty($this->data)){
                	$CFG->multilang_target_language = $lang;
                    foreach($this->data as $key => $value){
                    	if (is_array($value)) continue;
                    	if (file_exists($CFG->wwwroot.'/filter/multilangenhanced/filter.php')){
	                    	include_once $CFG->wwwroot.'/filter/multilangenhanced/filter.php';
	                    	$formattedvalue = (preg_match('/option$/', $key) || preg_match('/^http?:\/\//', $value)) ? $value : $filter->filter($value) ;
	                    } else {
	                    	$formattedvalue = $value;
	                    }
                        $contentlang = str_replace("<%%{$key}%%>", $formattedvalue, $contentlang);
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
        foreach($this->fields as $key => $field){
        	// assembles multiple list answers
            if (preg_match("/list$/", $field->type)){
                if (@$field->multiple){
                    if (!function_exists('get_string_for_list')){
                        function get_string_for_list(&$a){
                            $a = get_string($a, 'customlabel');
                        }
                    }
                    $name = str_replace('[]', '', $field->name);
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
                    $this->data->{$nameoption} = $this->data->{$name};
                    $this->data->{$name} = get_string($this->data->{$name}, 'customlabeltype_'.$this->type);
                }
                $this->data->{$name} = str_replace("'", "\\'", $this->data->{$name});
            }
        	// unpacks editor internal info
            if (preg_match("/editor|textarea/", $field->type)){
                $name = $field->name.'_editor';
            	if (!empty($this->data->{$name}) && is_array($this->data->{$name})){
	            	$this->data->{$field->name} = @$this->data->{$name}['text'];
	            	$formatkey = $field->name.'format';
	            	$this->data->{$formatkey} = @$this->data->{$name}['format'];
	            }
            }
        }
    }

    /**
    * post processes datasource fields for rendering in templates
    */
    function process_datasource_fields(){
    	global $CFG;
    	
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
                    if (empty($this->data->{$name})) continue;
                    $this->data->{$nameoption} = $this->data->{$name};
                    if ($field->source == 'dbfieldkeyed') {
                        $this->data->{$name} = $this->get_datasource_values($field, $this->data->{$name});
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
    function get_template($lang){
    	global $CFG, $PAGE;

    	static $templates; // kind of caching

        if (!isset($templates[$this->type][$lang])){
        	// allow override of templates using theme localisation
        	if (is_file($CFG->dirroot .'/theme/'.$PAGE->theme->name."/mod/customlabel/type/{$this->type}/lang/{$lang}/template.tpl")){
            	$templatefile = $CFG->dirroot .'/theme/'.$PAGE->theme->name."/mod/customlabel/type/{$this->type}/lang/{$lang}/template.tpl";
        	} else {
            	$templatefile = $CFG->dirroot ."/mod/customlabel/type/{$this->type}/lang/{$lang}/template.tpl";
        	}
        	
            if (file_exists($templatefile)){
                $templates[$this->type][$lang] = implode('', file($templatefile));
            } else {
            	if (debugging()){
	            	echo "unable to find template";
	            }
                return null;
            }
        }
        return $templates[$this->type][$lang];         
    }

    function pre_update(){
    }

    function post_update(){
    }
}

?>