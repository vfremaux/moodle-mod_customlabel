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
    public $instance; // The customlabel record.
    public $fullaccess;
    public $content; // The encoded specific data.
    public $cmid; // The current course module.
    public $processedcontent; // The expanded content, ready to print. OBSOLETE.

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
        $this->instance = $data;
        if (isset($data->content)) {
            $this->data = json_decode(base64_decode($data->content));
        } else {
            $this->data = $data;
        }
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
                $table = '{'.clean_param($field->table, PARAM_ALPHANUMEXT).'}';
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
                if (!empty($field->file) && file_exists($CFG->dirroot.'/'.$field->file)) {
                    include_once($CFG->dirroot.'/'.$field->file);
                } else {
                    print_error("Missing explicit library file location for datasource");
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
            $valuelist = str_replace("'", "\'", $values);
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

        $iconurl = $OUTPUT->image_url('icon_'.$this->type, 'customlabeltype_'.$this->type);
        // Old compat - Deprecated.
        $this->data->icon = $iconurl;
        $this->data->iconurl = $iconurl;
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

    public function make_content() {
        global $OUTPUT, $PAGE;

        $config = get_config('customlabel');

        $content = '';
        $this->preprocess_data();
        $this->process_form_fields();
        $this->process_datasource_fields();
        try {
            $this->postprocess_data();
            $this->postprocess_icon();
            $this->data->labelclass = $this->type;
            $template = 'customlabeltype_'.$this->type.'/template';
            $this->data->skin = $config->defaultskin;

            $themename = $PAGE->theme->name;
            $override = get_config('theme_'.$themename, 'customlabelskin');
            if (!empty($override)) {
                $this->data->skin = $override;
            }

            $content = $OUTPUT->render_from_template($template, $this->data);
        } catch (Exception $e) {
            assert(1);
            // Quiet any exception here. Resolve case of Editing Teachers.
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
                        if (isset($this->data->{$name})) {
                            // This is to protect cases where the type implementation receives new list params.
                            $optionvalue = $this->data->{$optionname} = $this->data->{$name};
                        }
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
                 * the original optioncode stored in internal data model is translated
                 * to <fieldname>option data entry.
                 */
                if (@$field->multiple) {

                    /*
                     * If multiple select or list, the value set
                     * is always an array, even if having a single value.
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
                                    $this->data->{$name} = format_string(implode($sep, $sourcevalues));
                                    break;
                                }

                                ca$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI$&ÂgfÖú¶7Ó´Şu|'K.ÌoP
PİÀùFË.Ğıoûò9B<~. ’ïÅË[’´˜Ë<Ù­„$¯•¢·ä{1¹A•.òbKxºL ¯İ·'¯u8n5 ’ºe ,]ñH©–’ÆV¨ŒWwÃ$ùCƒel¹“|zys«™KŠi-ğqÊİ¬bk,wnGÿâ;¥  ~ÖeÉrÍ’‰ÜÔ~'1`Vâ¦«¹-*[ÉñLÔKÄ'2@ŸÜşĞä»ª ²n‘Íß2¸Nß ˆÆ¶µG•¢ói/U¢µ'Eï@¦`Hæ¹˜;J•¼¼ÜÅ+Jén#»¼‚6Ú´—Ä¹G•ü¡NÒGğ'—Z!öáí¸‰Wi»NJ @óàšAûÜZ|ª[¨ï$q}iÒ·µQbtTEC$œ’m…Îmo“LÒDüÜ;˜%gÏ?wêÁÅ·øîùovH0õÉa‡5£Ú*î Ø’ÃÌlÍ››S iyä”rÕO7ª“%L]İ×%±ºÇhk ¶«·÷>v1­HB£®±ßŞÚd\(eoIx¢>3´6BS%ÌØá“(
œÛf$Ãhıé¿¶åeÔôÚèHœ‚`İ¶f{Fo©Yò¿Ôó@00uMb’z-ëìXI