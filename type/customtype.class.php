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
 * @package mod_customlabel
 * @category mod
 * @author Valery Fremaux
 * @date 02/12/2007
 *
 * A generic class for collecting all that is common to all elements
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/extralib/lib.php');

class customlabel_type {

    public $title;
    public $type;
    public $fields;
    public $data; // The dynamic data values.
    public $fullaccess;
    public $content; // The encoded specific data.
    public $cmid; // The current course module.
    public $processedcontent; // The expanded content, ready to print. OBSOLETE

    /**
     * A customlabel has a type
     * A custom label is made of fields objects within an array. A field
     * determines the nature of the data and the way it can be input.
     */
    public function __construct($data, $type = 'undefined') {
        $this->cmid = @$data->coursemodule; // It is possible we have NOT, f.e. when we just loading the class as an empty object.
        $this->title = @$data->title;
        $this->type = $type;
        $this->fields = array();
        // $this->data = $data;
        $this->instance = $data;
        $this->data = json_decode(base64_decode(@$data->content));
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
        global $CFG, $DB;

        $options = array();
        switch ($field->source) {
            case 'dbfieldkeyed':
                $table = '{'.$field->table.'}';
                $fieldname = $field->field;
                $fieldkey = (empty($field->key)) ? 'id' : $field->key;
                $select = (!empty($field->select)) ? " WHERE {$field->select} " : '';
                $ordering = (!empty($field->ordering)) ? " ORDER BY {$field->ordering} " : '';
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
     * Given a possibly list of values, get an array of ids
     */
    public function get_current_options($options, $value, $multiple = false) {

        if (is_array($value)) {
            return $value;
        }
        if (is_object($value)) {
            return (array)$value;
        }

        $result = array();
        $optionsparts = preg_split('/ - |; |<br\/>/', $value);
        foreach ($optionsparts as $part) {
            foreach ($options as $key => $val) {
                if (trim($part) == $val) {
                    if ($multiple) {
                        $result[] = $key;
                        break;
                    } else {
                        return $key;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * checks the visibility of the current instance.
     */
    public function is_visible($cm) {
        $capability = 'customlabeltype/'.$this->type.':view';
        $context = context_module::instance($cm->id);
        return has_capability($capability, $context);
    }

    /**
     * Given a course module supposed to be a courselabel, checks
     * the visibility.
     */
    public static function module_is_visible($cm, $instance) {
        global $DB;

        $context = context_module::instance($cm->id);

        if (!isloggedin() || is_guest($context)) {
            // Check capability to see on user role.
            $userrole = $DB->get_record('role', array('shortname' => 'guest'));
            $params = array('contextid' => context_system::instance()->id,
                            'roleid' => $userrole->id,
                            'capability' => 'customlabeltype/'.$instance->labelclass.':view',
                            'permission' => CAP_ALLOW);
            if (!$DB->get_record('role_capabilities', $params)) {
                // Set no chance to see anything from it.
                return false;
            }
        } else {
            if (!has_capability('customlabeltype/'.$instance->labelclass.':view', $context)) {
                return false;
            }
        }

        return true;
    }

    /**
     *
     *
     */
    public function get_datasource_values($field, $values) {
        global $CFG, $DB;

        if (is_array($values)) {
            $valuelist = implode("','", $values);
        } else {
            $valuelist = $values;
        }

        $fieldname = $field->field;
        $fieldkey = (empty($field->key)) ? 'id' : $field->key;
        $fieldselect = (@$field->select) ? "AND $field->select" : '';
        $select = " WHERE `$fieldkey` IN ('$valuelist') ". $fieldselect;

        $output = array();
        $table = $field->table;
        $sql = "
            SELECT
                {$fieldkey},
                {$fieldname}
            FROM
                {{$table}}
                $select
        ";
        $results = $DB->get_records_sql_menu($sql);
        if ($results) {
            foreach ($results as $result) {
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
        assert(1);
    }

    /**
     * postprocesses data values to get definitive data object.
     * Usual customlabels will not overload this function, unless
     * some form information must be fetched or computed after
     * internal standard transforms have been processed such as final formatting.
     */
    public function postprocess_data($course = null) {
        assert(1);
    }

    public function postprocess_icon() {
        global $OUTPUT;

        // Old compat - Deprecated.
        $this->data->icon = $OUTPUT->image_url('icon', 'customlabeltype_'.$this->type)->out();

        $this->data->iconurl = $OUTPUT->image_url('icon', 'customlabeltype_'.$this->type)->out();
    }

    /**
     * postprocesses data stub after template has been rendered. usually
     * this is used by some types that use date or datetime fields and
     * need to revert orginal timestamp format for storage
     */
    public function posttemplate_data() {
        assert(1);
    }

    public function get_content() {
        return $this->data->processedcontent;
    }

    /**
     * @param string $lang if set, will compile only content for this language. If not set and multilang filtering is on,
     * will compile as many versions of templates per installed language, pursuant proper template is available.
     */
    public function make_content($lang = '', $course = null) {
        global $PAGE;

        $this->preprocess_data(); // Hooking to subclasses specialization.
        $this->process_form_fields();
        $this->process_datasource_fields();
        $this->postprocess_data($course); // Hooking to subclasses specialization after all field and source field processing done.
        $this->postprocess_icon();
        $this->data->currenttheme = $PAGE->theme->name;
        $this->data->title = $this->title;
        $this->data->processedcontent = $this->make_template($lang);

        if (empty($this->data->processedcontent)) {
            // Arbitrary name if missing.
            $this->data->processedcontent = "customlabel{$customlabel->instance}";
        }

        $this->posttemplate_data();

        return $this->data->processedcontent;
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
            if (strpos(@$CFG->textfilters, 'filter/multilang') !== false ||
                    strpos(@$CFG->textfilters, 'filter/multilangenhanced') !== false) {
                // We have multilang.
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
                $contentlang = '<span class="multilang" lang="'.$lang.'" >';
                $contentlang .= $this->process_conditional($template);
                $contentlang .= '</span>';
                if (!empty($this->data)) {
                    $CFG->multilang_target_language = $lang;
                    foreach ($this->data as $key => $value) {

                        if (is_array($value) || is_object($value)) {
                            continue;
                        }
                        if (file_exists($CFG->dirroot.'/filter/multilangenhanced/filter.php')) {
                            include_once($CFG->dirroot.'/filter/multilangenhanced/filter.php');
                            $filter = new filter_multilangenhanced($context, array());
                            $cond = preg_match('/option$/', $key) || preg_match('/^http?:\/\//', $value);
                            $formattedvalue = $cond ? $value : $filter->filter($value);
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
                $contentlang = '<span class="multilang" lang="'.$lang.'" >';
                $contentlang .= get_string('nocontentforthislanguage', 'customlabel');
                $contentlang .= '</span>';
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

        // Assembles multiple list answers.
        foreach ($this->fields as $key => $field) {

            // Check string domain if inexistant.
            $domain = (empty($field->domain)) ? 'customlabel' : $field->domain;
            // Depending on field type, change rendering separator.
            $sep = ($field->type == 'vdatasource') ? '<br/>' : ' - ';

            if (preg_match("/datasource$/", $field->type)) {
                /*
                 * for lists and datasources, the rendered value will take
                 * place into the real field name entry.
                 * the orginal optioncode stored in internal data model is translated
                 * to <fieldname>option data entry.
                 */
                if (@$field->multiple) {
                    
                    /*
                     * If multiple select or list, the value set
                     * is always an array, event if having a single value.
                     */

                    $name = str_replace('[]', '', $field->name);
                    $optionname = $name.'option';

                    // If option codes have not been saved, save them.
                    if (empty($this->data->{$optionname})) {
                        $this->data->{$optionname} = @$this->data->{$name};
                    }
                    $valuearray = @$this->data->{$optionname};

                    if (is_string($valuearray) && !empty($valuearray)) {
                        /*
                         * Usually comes from storage in which it has been serialed to a value list.
                         *
                         */
                        $valuearray = explode(',', $valuearray);
                    }

                    if (is_array($valuearray)) {
                        if (!empty($valuearray)) {
                            switch ($field->source) {
                                case 'dbfieldkeyed': {
                                    // Content is direct value of source fields.
                                    $sourcevalues = $this->get_datasource_values($field, $valuearray);
                                    $this->data->{$name} = implode($sep, $sourcevalues);
                                    break;
                                }

                                case 'dbfieldkey': {
                                    // Content is the translated value of source fields.
                                    foreach ($valuearray as $value) {
                                        $valuearraystr[] = get_string($value, $domain);
                                    }
                                    $this->data->{$name} = implode($sep, $valuearraystr);
                                    break;
                                }

                                case 'function': {
                                    /*
                                     * function needs returning a text formted scalar list.
                                     */
                                    if (!empty($field->source) && file_exists($CFG->dirroot.$field->source)) {
                                        include_once($CFG->dirroot.$field->source);
                                    }
                                    $functionname = $field->function;
                                    $this->data->{$name} = $functionname($valuearray);
                                    break;
                                }
                            }
                        }
                    } else {
                        if ($field->source == 'dbfieldkeyed') {
                            // Fake a one value array.
                            $this->data->{$name} = implode($sep, $this->get_datasource_values($field, array($valuearray)));
                        } else {
                            if (!empty($this->data->{$name})) {
                                $key = ''.$this->data->{$name};
                                if (preg_match('/^_/', $key)) {
                                    continue;
                                }
                                $this->data->{$name} = get_string($key, 'customlabel');
                            }
                        }
                    }
                } else {
                    /*
                     * We are a singlechoice
                     */
                    $name = $field->name;
                    $nameoption = "{$name}option";
                    if (empty($this->data->{$name})) {
                        // Nothing choosen.
                        continue;
                    }

                    // If option codes have not been saved, save them.
                    if (empty($this->data->{$nameoption})) {
                        $this->data->{$nameoption} = $this->data->{$name};
                    }

                    switch ($field->source) {
                        case 'dbfieldkeyed': {
                            $sourcevalue = $this->get_datasource_values($field, array($this->data->{$nameoption}));
                            $this->data->{$name} = implode($sep, $sourcevalue);
                            break;
                        }

                        case 'dbfieldkey': {
                            $this->data->{$name} = get_string($this->data->{$nameoption}, $domain);
                            break;
                        }

                        case 'function': {
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
                            break;
                        }
                    }
                }
            }
        }
    }

    public function get_xml() {
        $internaldata = json_decode(base64_decode($this->data->content));
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
                if (is_array(@$internaldata->{$fieldname})) {
                    $fieldvalue = implode (',', $internaldata->{$fieldname});
                }
            } else {
                $fieldvalue = @$internaldata->{$fieldname};
                $fieldvalue = str_replace("\\'", "'", $fieldvalue);
            }
            $xml .= $fieldvalue;
            $xml .= "</$fieldname>\n";
        }
        $xml .= "\t</content>\n";
        $xml .= '</datablock>';
        return $xml;
    }

    public function on_delete() {
        assert(1);
    }

    /**
     * New : get template from lang strings
     * loads a template and caches it in static database for reuse
     */
    public function get_template($lang) {
        static $templates; // Kind of caching.

        $strm = get_string_manager();

        if (!isset($templates[$this->type][$lang])) {
            // Allow override of templates using theme localisation.
            $templates[$this->type][$lang] = $strm->get_string('template', 'customlabeltype_'.$this->type, '', $lang);
        }
        return $templates[$this->type][$lang];
    }

    public function pre_update() {
        assert(1);
    }

    public function post_update() {
        assert(1);
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
            // Test variable or expression. this works with an expression that is <fieldname> <op> <value>, or a single <fieldname>.
            $test = $matches[2];

            // We extract fieldname from expression.
            preg_match('/^[a-zA-Z0-9_]+/', $test, $matches2);
            $fieldname = $matches2[0];
            if ($test) {
                // We defer evaluation along with an extractable array.
                // The eval() call is defered to an extralib library.
                $vars = array($fieldname => @$this->data->$fieldname);
                customlabel_eval($test, $vars, $result);
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

        if (empty($this->data->instance)) {
            return '';
        }

        $cm = get_coursemodule_from_instance('customlabel', $this->data->instance);

        // Fault tolerance.
        if (!$cm) {
            return false;
        }

        $context = context_module::instance($cm->id);
        if ($fs->is_area_empty($context->id, 'mod_customlabel', $field->name, 0)) {
            return false;
        }

        $files = $fs->get_area_files($context->id, 'mod_customlabel', $field->name, 0, "itemid, filepath, filename", false);
        $file = array_pop($files);

        $fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), 'mod_customlabel', $file->get_filearea(),
            0 + @$field->itemid, $file->get_filepath(), $file->get_filename());

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
            $lin = '<a href="'.$fileurl.'"
                       title="'.$fieldlabel.'"
                       alt="'.$fieldlabel.'" '.$classes.' />'.$file->get_filename().'</a>';
            return $link;
        }
    }

    /**
     * Accesss to an internal data value.
     * @param string $field
     */
    public function get_data($field) {
        return $this->data->$field;
    }

    /**
     * Updates an internal data value.
     * @param string $field
     */
    public function update_data($field, $value) {
        global $DB;

        // Get storage.
        if (!$internaldata = json_decode(base64_decode($this->data->content))) {
            $internaldata = new StdClass;
        }

        // Update storage and in memory.
        $internaldata->$field = $value;
        $this->data->$field = $value;

        // Save back.
        $this->data->content = base64_encode(json_encode($internaldata));
        $this->make_content();
        $DB->update_record('customlabel', $this->data);
    }

    public function set_instance($instance) {
        $this->data->instance = $instance;
    }

    /**
     * Invoke amd modules if required.
     */
    public function require_js() {
        global $PAGE;

        if (!empty($this->hasamd)) {
            $class = 'customlabeltype_'.$this->type.'/customlabel';
            $PAGE->requires->js_call_amd($class, 'init');
        }
    }
}
