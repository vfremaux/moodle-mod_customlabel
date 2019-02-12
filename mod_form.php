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
 * Add customlabel form
 *
 * @package    mod_customlabel
 * @copyright  2012 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');
$PAGE->requires->js('/mod/customlabel/js/modform.js', false); // Needs being in footer to get oldtype.

class mod_customlabel_mod_form extends moodleform_mod {

    /**
     * Returns the options array to use in forum text editor
     *
     * @return array
     */
    public static function editor_options() {
        global $COURSE, $PAGE, $CFG;

        // TODO: add max files and max size support.
        $maxbytes = get_user_max_upload_file_size($PAGE->context, $CFG->maxbytes, $COURSE->maxbytes);
        return array(
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'maxbytes' => $maxbytes,
            'trusttext' => true
        );
    }

    public function definition() {
        global $COURSE, $DB, $CFG, $PAGE;
        global $customlabelscriptsloaded;

        // Setup amd module for mod_form.
        $params = array();
        $params['courseid'] = $COURSE->id;
        $params['section'] = optional_param('section', 0, PARAM_INT);
        $params['returntomod'] = optional_param('return', 0, PARAM_BOOL);
        $params['updatelabelid'] = 0 + @$this->current->update;
        if (!$customlabelscriptsloaded) {
            $PAGE->requires->js_call_amd('mod_customlabel/customlabel', 'init', array($params));
            $customlabelscriptsloaded = true;
        }

        $context = context_course::instance($COURSE->id);

        // Get classes for custom label.
        $labelclasses = customlabel_get_classes($context);

        $qoptions = array();
        foreach ($labelclasses as $labelclass) {
            $qoptions[$labelclass->family][$labelclass->id] = $labelclass->name;
        }
        asort($qoptions);

        $mform = $this->_form;

        $mform->disable_form_change_checker();

        $customlabel = $this->resolve_customlabel();
        $customclass = customlabel_load_class($customlabel);

        $mform->addElement('hidden', 'intro', '');
        $mform->setType('intro', PARAM_TEXT);
        $mform->addElement('hidden', 'introformat', 0);
        $mform->setType('introformat', PARAM_INT);

        $mform->addElement('header', 'general', get_string('general', 'form'));

        if (has_capability('mod/customlabel:fullaccess', $context) || $customclass->fullaccess) {
            $label = get_string('labelclass', 'customlabel');
            $attrs = array('class' => 'labeltypeselector');
            $typeselect = & $mform->addElement('select', 'labelclass', $label, array(), $attrs);
            foreach ($qoptions as $family => $options) {
                $label = '--- '.get_string('family'.$family, 'customlabel').' ---';
                $typeselect->addOption($label, '', array('disabled' => 'disabled'));
                foreach ($options as $opt => $optlabel) {
                    $typeselect->addOption($optlabel, $opt);
                }
            }
            $mform->setType('labelclass', PARAM_TEXT);
            $mform->setDefault('labelclass', 'text');
        } else {
            $mform->addElement('static', 'labelclassname', get_string('labelclass', 'customlabel'));
            $mform->addElement('hidden', 'labelclass');
            $mform->setType('labelclass', PARAM_TEXT);
        }

        $mform->addElement('text', 'title', get_string('title', 'customlabel'));
        $customlabelnextid = $DB->get_field('customlabel', 'MAX(id)', array()) + 1;
        $mform->setDefault('title', $customlabel->labelclass.'_'.$customlabelnextid);
        $mform->setType('title', PARAM_TEXT);

        if (!$customclass) {
            print_error("Custom label class lacks of definition");
        }

        foreach ($customclass->fields as $field) {
            // No capable users cannot edit lock fields.
            if (!has_capability('mod/customlabel:fullaccess', $context) && !empty($field->admin)) {
                continue;
            }

            $fieldname = str_replace('[]', '', $field->name); // Must take care it is a multiple field.
            if (!empty($field->label)) {
                $fieldlabel = format_string($field->label);
            } else {
                $fieldlabel = get_string($field->name, 'customlabeltype_'.$customclass->type);
            }

            if ($field->type == 'hidden') {

                $mform->addElement('hidden', $field->name, @$field->default);
                $mform->setType($field->name, PARAM_TEXT);

            } else if ($field->type == 'choiceyesno') {

                $mform->addElement('selectyesno', $field->name, $fieldlabel);
                $mform->setType($field->name, PARAM_BOOL);

            } else if ($field->type == 'textfield') {

                $attrs = array('size' => @$field->size, 'maxlength' => @$field->maxlength);
                $mform->addElement('text', $field->name, $fieldlabel, $attrs);
                $mform->setType($field->name, PARAM_CLEANHTML);

            } else if ($field->type == 'date') {

                $attrs = array(
                    'startyear' => date('Y'),
                    'stopyear' => date('Y') + 5,
                    'optional' => true
                );
                $mform->addElement('date_selector', $field->name, $fieldlabel, $attrs);

            } else if ($field->type == 'datetime') {

                $attrs = array(
                    'startyear' => date('Y'),
                    'stopyear' => date('Y') + 5,
                    'optional' => true,
                    'step' => 10
                );
                $mform->addElement('date_time_selector', $field->name, $fieldlabel, $attrs);

            } else if ($field->type == 'editor') {

                $editoroptions = self::editor_options();
                $editoroptions['context'] = $this->context;
                $mform->addElement('editor', $field->name.'_editor', $fieldlabel, array('rows' => 5, 'cols' => 60), $editoroptions);

            } else if ($field->type == 'textarea') {

                $mform->addElement('textarea', $field->name, $fieldlabel, array('rows' => 5, 'cols' => 60));

            } else if (preg_match("/list$/", $field->type)) {

                if (empty($field->straightoptions)) {
                    $options = $customclass->get_options($fieldname);
                } else {
                    $translatedoptions = $field->options;
                    array_walk($translatedoptions, 'format_string');
                    $options = array_combine($field->options, $translatedoptions);
                }
                $select = &$mform->addElement('select', $field->name, $fieldlabel, $options);
                if (!empty($field->multiple)) {
                    $select->setMultiple(true);
                }
                $mform->setType($field->name, PARAM_TEXT);

            } else if (preg_match("/datasource$/", $field->type)) {

                // Very similar to lists, except options come from an external datasource.
                $options = $customclass->get_datasource_options($field);

                $translatedoptions = array();
                foreach ($options as $key => $value) {
                    $translatedoptions[$key] = format_string($value);
                }

                $attrs = array();
                if (!empty($field->constraintson)) {
                    $attrs['class'] = 'constrained '.$customclass->type;
                    $attrs['disabled'] = 'disabled'; // Let javascript liberate them when ready to process constraints.
                    $attrs['data-constraints'] = $field->constraintson;
                    $attrs['data-label-type'] = $customclass->type;
                    $attrs['data-cmid'] = @$this->coursemodule->id;
                }

                if (!empty($field->size)) {
                    $attrs['size'] = $field->size;
                }

                $select = &$mform->addElement('select', $field->name, $fieldlabel, $translatedoptions, $attrs);
                if (!empty($field->multiple)) {
                    $select->setMultiple(true);
                }
                $mform->setType($field->name, PARAM_TEXT);

            } else if ($field->type == 'filepicker') {

                $group = array();
                $types = !empty($field->acceptedtypes) ? $field->acceptedtypes : '*';
                $options = array('courseid' => $COURSE->id, 'accepted_types' => $types);
                $group[] = $mform->createElement('filepicker', $field->name, '', $options);
                $group[] = $mform->createElement('checkbox', 'clear'.$field->name, '', get_string('cleararea', 'customlabel'));
                $mform->addGroup($group, $field->name.'group', $fieldlabel, '', array(''), false);

            } else if ($field->type == 'static') {

                $mform->addElement('static', $field->name, $fieldlabel, @$field->default);

            } else {

                echo "Unknown or unsupported type : $field->type";

            }

            if (!empty($field->mandatory)) {
                $mform->addRule($field->name, null, 'required', null, 'client');
            }

            if (!empty($field->help)) {
                $mform->addHelpButton($field->name, $field->help, 'customlabeltype_'.$customlabel->labelclass);
            }

            $mform->setDefault($fieldname, @$field->default);
        }

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }

    // We must prepare data, extract dynamic part from instance.
    public function set_data($customlabel) {

        if (empty($customlabel->labelclass)) {
             $customlabel->labelclass = 'text';
             $customlabel->content = '';
             $customlabel->processedcontent = '';
             $customlabel->intro = '';
             $customlabel->introformat = 0;
             $customlabel->textcontent = '';
             $customlabel->readmorecontent = '';
        }

        $instance = customlabel_load_class($customlabel);

        $formdata = clone($customlabel);

        // Get dynamic part of data and add to fixed model part from customlabel record.
        // $formdatadyn = (array)json_decode(base64_decode($customlabel->content));

        foreach ($formdata as $key => $value) {
            // Discard all moodle core data that should be there.
            if (in_array($key, array('coursemodule', 'instance', 'sesskey', 'module', 'section'))) {
                continue;
            }
            // Ignore old Moodle 1.9 stuff.
            if (in_array($key, array('safe_content', 'usesafe'))) {
                continue;
            }
            $formdata->{$key} = $value;
            if (is_object($formdata->{$key}) && isset($formdata->{$key}->text)) {
                $formdata->{$key} = (array)$formdata->{$key};
            }
        }

        // Prepare editor for intro standard field.

        // Prepare editors for all textarea|editor dynamic fields prepared in model.
        foreach ($instance->fields as $field) {

            $fieldname = $field->name;

            if (preg_match('/editor|textarea/', $field->type)) {

                $editorname = $fieldname.'_editor';
                $formdata->$fieldname = (isset($instance->data->{$fieldname})) ? $instance->data->{$fieldname} : '';
                $editoroptions = self::editor_options();
                $editoroptions['context'] = $this->context;

                // Fakes format field.
                $fieldnameformat = $fieldname.'format';
                $formdata->$fieldnameformat = FORMAT_HTML;

                file_prepare_standard_editor($formdata, $fieldname, $editoroptions, $this->context, 'mod_customlabel',
                                             'contentfiles', $field->itemid);
                $editor = &$formdata->$editorname;

                $editor['text'] = customlabel_file_rewrite_pluginfile_urls($editor['text'], 'pluginfile.php', $this->context->id,
                                                                           'mod_customlabel', 'contentfiles', $field->itemid);
                continue;
            }

            if (preg_match('/datasource$/', $field->type)) {
                $options = $instance->get_datasource_options($field);
                // ??? $current = $instance->get_current_options($options, @$instance->data->$fieldname, @$field->multiple);
                $current = @$instance->data->$fieldname;
                $formdata->$fieldname = $current;
                continue;
            }

            if ($field->type == 'filepicker') {
                // TODO : limit upload size on course settings.
                $maxbytes = -1;
                $draftitemid = file_get_submitted_draft_itemid($field->name);
                $groupname = $field->name.'group';
                $maxfiles = 1;
                $options = array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => $maxfiles);
                file_prepare_draft_area($draftitemid, $this->context->id, 'mod_customlabel', $field->name, 0, $options);
                $formdata->{$groupname} = array($field->name => $draftitemid);
                continue;
            }

            if ($field->type == 'date' || $field->type == 'datetime') {
                // Convert stored value into timestamp.
                if (is_object($instance->data->$fieldname)) {
                    $t = mktime($instance->data->$fieldname->hour,
                                $instance->data->$fieldname->minute,
                                0,
                                $instance->data->$fieldname->month,
                                $instance->data->$fieldname->day,
                                $instance->data->$fieldname->year);
                }
                $formdata->{$fieldname} = $t;
                continue;
            }

            // All other simple cases.
            $formdata->{$fieldname} = ''.@$instance->data->$fieldname;
        }

        // Prepare type selector value.
        if ($tomodel = optional_param('type', '', PARAM_TEXT)) {
            $formdata->labelclass = $tomodel;
        }

        $formdata->sesskey = sesskey();
        parent::set_data($formdata);
    }

    public function add_completion_rules() {

        $mform =& $this->_form;

        $customlabel = $this->resolve_customlabel();
        $customclass = customlabel_load_class($customlabel);

        if (method_exists($customclass, 'add_completion_rules')) {
            // Up to 3 rules for completion1 to completion3.
            return $customclass->add_completion_rules($mform);
        } else {
            return array();
        }
    }

    public function completion_rule_enabled($data) {
        return (!empty($data['completion1enabled'])) ||
            (!empty($data['completion2senabled'])) ||
            (!empty($data['completion3enabled']));
    }

    protected function resolve_customlabel() {
        global $DB, $SESSION;

        $tomodel = '';
        $customlabel = new StdClass;
        if ($tomodel = optional_param('type', '', PARAM_TEXT)) {
            $customlabel->labelclass = $tomodel;
        } else if ($tomodel = @$SESSION->customlabel->update_type_change) {
            $customlabel->labelclass = $tomodel;
            unset($SESSION->customlabel);
        }

        $customlabel->processedcontent = '';
        if (!$tomodel) {
            if ($this->current->instance) {
                // Are we updating an existing instance ?
                $customlabel = $DB->get_record('customlabel', array('id' => $this->current->id));
            } else {
                $customlabel->title = '';
                $customlabel->labelclass = 'text';
                $customlabel->textcontent = '';
                $customlabel->readmorecontent = '';
            }
        }

        $customlabel->coursemodule = @$this->_cm->id;

        return $customlabel;
    }

}
