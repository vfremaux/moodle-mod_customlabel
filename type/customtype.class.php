<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package mod-customlabel
 * @category mod
 * @author Valery Fremaux
 * @date 02/12/2007
 *
 * A generic class for collecting all that is common to all elements
 */

class customlabel_type {

    public $title;
    public $type;
    public $fields;
    public $data; // The original DB record.
    public $fullaccess;
    public $content;

    /**
     * A customlabel has a type
     * A custom label is made of fields objects within an array. A field
     * determines the nature of the data and the way it can be input.
     */
    public function __construct($data, $type = 'undefined', $content = '') {
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
    public function get_options($fieldname) {
        if (!array_key_exists($fieldname, $this->fields)) {
            return array();
        }

        if (empty($this->fields[$fieldname]->options)) {
            return array();
        }

        if (is_string($this->fields[$fieldname]->options)) {
            $optionsource = explode(',', $this->fields[$fieldname]->options);
        } else {
            $optionsource = $this->fields[$fieldname]->options;
        }

        // Get all code / translations for the option list.
        $options = array();
        foreach ($optionsource as $option) {
            $options[$option] = get_string($option, 'customlabeltype_'.$this->type);
        }
        return $options;
    }

    /**
     * makes a suitable options list for an external datasource.
     * @return an array of displayable options
     */
    public function get_datasource_options(&$field) {
        global $CFG, $USER, $COURSE, $DB;

        $options = array();
        switch ($field->source) {
            case 'dbfieldkeyed':
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
                break;

            case 'dbfieldkey':
                $table = '{'.$field->table.'}';
                $fieldname = $field->field;
                $select = (!empty($field->select)) ? " WHERE {$field->select} " : '';
                $ordering = (!empty($field->ordering)) ? " ORDER BY $field->ordering " : '';
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
                $domain = (empty($field->domain)) ? 'customlabel' : $field->domain;
                foreach (array_values($keys) as $key) {
                    $options[$key] = get_string($key, $domain);
                }
                break;

            case 'function':
                if (!empty($field->file) && file_exists($CFG->dirroot.$field->file)) {
                    include_once($CFG->dirroot.$field->file);
                }
                $functionname = $field->function;
                $options = $functionname();
                break;
        }

        return $options;
    }

    /**
     *
     *
     */
    public function get_datasource_values($field, $values) {
        global $CFG, $USER, $COURSE, $DB;

        if (is_array($values)) {
            $valuelist = implode("','", $values);
        } else {
            $valuelist = $values;
        }

        $table = $CFG->prefix.$field->table;
        $fieldname = $field->field;
        $fieldkey = (empty($field->key)) ? 'id' : $field->key;
        $fieldselect = (@$field->select) ? "AND $field->select" : '';
        $select = " WHERE `$fieldkey` IN ('$valuelist') ". $fieldselect;

        $output = array();

        $sql = "
            SELECT
                {$fieldkey},
                {$fieldname}
            FROM
                {$table}
                $select
        ";
        $results = $DB->get_records_sql_menu($sql);
        if ($results) {
            foreach($results as $result) {
                $output[] = $result;
            }
        }
        return $output;
    }

    /**
     * preprocesses data values before standard internal transforms are operated.
     * will deal with incoming values from the mod_form.php form.
     * Usual customlabels will not overload this function, unless
     * some form information must be fetched based on form results on a specific way.
     * It can be used for storing collected information in additional
     * locations in the database.
     *
     */
    public function preprocess_data() {
    }

    /**
     * postprocesses data values to get definitive data object.
     * Usual customlabels will not overload this function, unless
     * some form information must be fetched or computed after
     * internal standard transforms have been processed such as final formatting.
     */
    public function postprocess_data($course = null) {
    }

    /**
     * postprocesses data stub after template has been rendered. usually
     * this is used by some types that use date or datetime fields and
     * need to revert orginal timestamp format for storage
     */
    public function posttemplate_data() {
    }

    public function get_content() {
        return $this->data->processedcontent;
    }

    /**
     * @param string $lang if set, will compile only content for this language. If not set and multilang filtering is on, 
     * will compile as many versions of templates per installed language, pursuant proper template is available. 
     *
     */
    public function make_content($lang = '', $course = null) {
        global $DB, $PAGE;

        $this->preprocess_data(); // Hooking to subclasses specialization.
        $this->process_form_fields();
        $this->process_datasource_fields();
        $this->postprocess_data($course); // Hooking to subclasses specialization after all field and source field processing done.
        $this->data->currenttheme = $PAGE->theme->name;
        $this->data->title = $this->title;
        $this->content = $this->make_template($lang);
        $this->posttemplate_data();

        if (empty($this->content)) {
            // Arbitrary name if missing.
            $content = "customlabel{$customlabel->instance}";
            return $content;
        }

        return $this->content;
    }

    /**
     * realizes the template (the standard way is to compile content fields 
     * in a HTML template. 
     */
    public function make_template($lang = '') {
        global $CFG, $USER, $COURSE;

        $content = '';
        $context = context_course::instance($COURSE->id);

        if (!empty($lang)) {
             $languages[] = $lang;
        } else {
            if (strpos(@$CFG->textfilters, 'filter/multilang') !== false || strpos(@$CFG->textfilters, 'filter/multilangenhanced') !== false){ // We have multilang.
                $languages = array_keys(get_list_of_languages());
            } else {
                $languages[] = current_language();
            }
        }
        $content = '';
        foreach ($languages as $lang) {
            $template = $this->get_template($lang);
            if (!$template && !empty($CFG->defaultlang)) {
                $template = $this->get_template($CFG->defaultlang);
            }

            if ($template) {
                $contentlang = "<span class=\"multilang\" lang=\"$lang\" >";
                $contentlang .= $this->process_conditional($template);
                $contentlang .= "</span>";
                if (!empty($this->data)) {
                    $CFG->multilang_target_language = $lang;
                    foreach ($this->data as $key => $value) {
                        if (is_array($value) || is_object($value)) {
                            continue;
                        }
                        if (file_exists($CFG->dirroot.'/filter/multilangenhanced/filter.php')) {
                            include_once($CFG->dirroot.'/filter/multilangenhanced/filter.php');
                            $filter = new filter_multilangenhanced($context, array());
                            $formattedvalue = (preg_match('/option$/', $key) || preg_match('/^http?:\/\//', $value)) ? $value : $filter->filter($value) ;
                        } else {
                            $formattedvalue = $value;
                        }
                        $contentlang = str_replace("<%%{$key}%%>", $formattedvalue, $contentlang);
                    }
                    // Cleanup any unused tags and final replacements.
                    $contentlang = str_replace("<%%WWWROOT%%>", $CFG->wwwroot, $contentlang);
                    $contentlang = str_replace("<%%COURSEID%%>", $COURSE->id, $contentlang);
                    $contentlang = str_replace("<%%USERID%%>", $USER->id, $contentlang);
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
    public function process_form_fields() {
        foreach ($this->fields as $key => $field) {
            // Assembles multiple list answers.
            if (preg_match("/list$/", $field->type)) {
                if (@$field->multiple) {
                    if (!function_exists('get_string_for_list')) {
                        function get_string_for_list(&$a) {
                            $a = get_string($a, 'customlabel');
                        }
                    }
                    $name = str_replace('[]', '', $field->name);
                    $valuearray = @$this->data->{$name};
                    if (is_array($valuearray)) {
                        if (!empty($valuearray)) {
                            array_walk($valuearray, 'get_string_for_list');
                            if ($field->type == 'vlist') {
                                $this->data->{$name} = implode('<br/>', $valuearray);
                            } else {
                                $this->data->{$name} = implode(', ', $valuearray);
                            }
                        }
                    } else {
                        if (!empty($this->data->{$name})) {
                            $this->data->{$name} = get_string($this->data->{$name}, 'customlabel');
                        }
                    }
                } else {
                    $name = $field->name;
                    $optionname = "{$name}option";
                    if (!isset($this->data->{$optionname})) {
                        $optionvalue = $this->data->{$optionname} = $this->data->{$name};
                    } else {
                        $optionvalue = $this->data->{$optionname};
                    }
                    if (!empty($optionvalue)) {
                        if (is_numeric($optionvalue)) {
                            $this->data->{$name} = $optionvalue;
                        } else {
                            $this->data->{$name} = get_string($optionvalue, 'customlabeltype_'.$this->type);
                        }
                    } else {
                        $this->data->{$name} = '';
                    }
                }
            }
            // Unpacks editor internal info.
            if (preg_match("/editor|textarea/", $field->type)) {
                $name = $field->name.'_editor';
                if (!empty($this->data->{$name}) && is_array($this->data->{$name})) {
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
    public function process_datasource_fields() {
        global $CFG;
        static $processed = false;
        
        if ($processed) return;

        // Assembles multiple list answers.
        foreach($this->fields as $key => $field) {

            // Check string domain if inexistant.
            $domain = (empty($field->domain)) ? 'customlabel' : $field->domain;
            $sep = ($field->type == 'vdatasource') ? '<br/>' : ', ';

            if (preg_match("/datasource$/", $field->type)) {
                if (@$field->multiple) {
                    $name = str_replace('[]', '', $field->name);
                    $optionname = $name.'option';

                    // If option codes have not been saved, save them.
                    if (empty($this->data->{$optionname})) {
                        $this->data->{$optionname} = @$this->data->{$name};
                    }

                    $valuearray = @$this->data->{$optionname};

                    if (is_array($valuearray)) {
                        if (!empty($valuearray)) {
                            if ($field->source == 'dbfieldkeyed') {
                                $this->data->{$name} = implode($sep, $this->get_datasource_values($field, $valuearray));
                            } elseif ($field->source == 'dbfieldkey') {
                                foreach ($valuearray as $value) {
                                    $valuearraystr[] = get_string($value, $domain);
                                }
                                $this->data->{$name} = implode($sep, $valuearraystr);
                            } elseif ($field->source == 'function') {
                                if (!empty($field->source) && file_exists($CFG->dirroot.$field->source)){
                                    include_once($CFG->dirroot.$field->source);
                                }
                                $functionname = $field->function;
                                $this->data->{$name} = $functionname($valuearray);
                            }
                        }
                    } else {
                        if (!empty($this->data->{$name})) {
                            $this->data->{$name} = get_string($this->data->{$name}, 'customlabel');
                        }
                    }
                } else {
                    $name = $field->name;
                    $nameoption = "{$name}option";
                    if (empty($this->data->{$name})) {
                        continue;
                    }
                    if (empty($this->data->{$nameoption})) $this->data->{$nameoption} =  $this->data->{$name};
                    if ($field->source == 'dbfieldkeyed') {
                        $this->data->{$name} = $this->get_datasource_values($field, $this->data->{$nameoption});
                    } elseif ($field->source == 'dbfieldkey') {
                        $this->data->{$name} = get_string($this->data->{$nameoption}, $domain);
                    } elseif ($field->source == 'function') {
                        // Function must have an optional first argument that can be scalar or array.
                        if (!empty($field->source) && file_exists($CFG->dirroot.$field->source)) {
                            include_once($CFG->dirroot.$field->source);
                        }
                        $functionname = $field->function;
                        if (!empty($this->data->{$nameoption})) {
                            $this->data->{$name} = $functionname(@$this->data->{$nameoption});
                        } else {
                            $this->data->{$name} = '';
                        }
                    }
                }
            }
        }
        $processed = true;
    }

    public function get_xml(){
        global $CFG;

        $content = json_decode($this->data->content);
        $xml = "<datablock>\n";
        $xml .= "\t<instance>\n";
        $xml .= "\t\t<labeltype>{$this->type}</labeltype>\n";
        $xml .= "\t\t<title>{$this->title}</title>\n";
        $xml .= "\t</instance>\n";
        $xml .= "\t<content>\n";

        foreach ($this->fields as $field) {
            $fieldvalue = '';
            $fieldname = $field->name;
            $xml .= "\t\t<{$fieldname}>";
            if (preg_match("/list$/", $field->type) && !empty($field->multiple)) {
                if (is_array(@$content->{$fieldname})) {
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

    public function on_delete() {
    }

    /**
    * New : get template from lang strings
    * loads a template and caches it in static database for reuse
    */
    public function get_template($lang) {
        global $CFG, $PAGE;

        static $templates; // kind of caching

        $strm = get_string_manager();

        if (!isset($templates[$this->type][$lang])) {
            // Allow override of templates using theme localisation.
            $templates[$this->type][$lang] = $strm->get_string('template', 'customlabeltype_'.$this->type, '', $lang);
        }
        return $templates[$this->type][$lang];
    }

    public function pre_update() {
    }

    public function post_update() {
    }

    /**
     * Process some local conditional statement in templates for making
     * simple decisions.
     * this will admit simple tests such as <%if %%fieldname%% %> or 
     * simple comparison expressions surch as <%if %%fieldname >= 2%% %>
     * First expression member MUST be a fieldname defined in the customlabel type, followed
     * by a calculable expression.
     */
    public function process_conditional($template) {

        if (!preg_match('/<%if /', $template)) {
            // Quick return for unconditional templates.
            return $template;
        }

        $search = '/(.*?)<%if %%(.*?)%%\s+%>(.*?)<%endif\s+%>(.*)$/is';
        $buffer = '';
        $matches = array();
        $matches[4] = '';
        while (preg_match($search, $template, $matches)) {
            $buffer .= $matches[1]; // Prefix.
            $test = $matches[2]; // Test variable or expression. this works with an expression that is <fieldname> <op> <value>, or a single <fieldname>
            if ($test) {
                $exp = "\$result = @\$this->data->$test ; ";
                eval($exp);
                if ($result) {
                    $buffer .= $matches[3];
                }
            }
            $test = $matches[3]; // Conditional content.
            $template = $matches[4];
        }
        $buffer .= $template;

        return $buffer;
    }

    /**
     * for file related fields, will provide the access URL to the stored file
     * for that field. Url can be inserted into customlabel template from this function.
     * @param string the field name in the customlabel micromodel.
     * @return a moodle_url to the stored file
     */
    public function get_file_url($fieldname) {

        if ($this->fields[$fieldname]->type != 'filepicker') {
            return false;
        }

        if (!array_key_exists($fieldname, $this->fields)) {
            return false;
        }

        $field = $this->fields[$fieldname];

        $fs = get_file_storage();

        if (empty($this->data->instance)) return '';
        $cm = get_coursemodule_from_instance('customlabel', $this->data->instance);

        // Fault tolerance
        if (!$cm) return false;

        $context = context_module::instance($cm->id);
        if ($fs->is_area_empty($context->id, 'mod_customlabel', $field->name, 0)) {
            return false;
        }

        $files = $fs->get_area_files($context->id, 'mod_customlabel', $field->name, 0, "itemid, filepath, filename", false);
        $file = array_pop($files);

        $fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), 'mod_customlabel', $file->get_filearea(),
            0, $file->get_filepath(), $file->get_filename());

        if (empty($field->destination) || $field->destination == 'url') {
            return $fileurl;
        }

        if ($field->destination = 'image') {
            $width = @$field->width;
            $height = @$field->height;
            $classes = @$field->classes;
            $fieldlabel = get_string($field->name, 'customlabeltype_'.$this->type);
            return '<img src="'.$fileurl.'" title="'.$fieldlabel.'" alt="'.$fieldlabel.'" '.$width.' '.$height.' '.$classes.' />';
        }

        if ($field->destination = 'link') {
            $classes = @$field->classes;
            $fieldlabel = get_string($field->name, 'customlabeltype_'.$this->type);
            return '<a href="'.$fileurl.'" title="'.$fieldlabel.'" alt="'.$fieldlabel.'" '.$classes.' />'.$file->get_filename().'</a>';
        }
    }
}
