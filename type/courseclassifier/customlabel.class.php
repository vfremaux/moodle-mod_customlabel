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
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 *
 *
 */

class customlabel_type_courseclassifier extends customlabel_type {

    public function __construct($data) {
        global $DB;

        parent::__construct($data);
        $this->type = 'courseclassifier';
        $this->fields = array();

        $config = get_config('customlabel');

        $field = new StdClass;
        $field->name = 'tablecaption';
        $field->type = 'textfield';
        $this->fields['tablecaption'] = $field;

        $field = new StdClass;
        $field->name = 'uselevels';
        $field->type = 'list';
        $field->options = array('1', '2', '3');
        $field->straightoptions = true;
        $field->mandatory = true;
        $this->fields['uselevels'] = $field;

        if ($fieldid = $DB->get_field($config->classification_type_table, 'id', array('code' => 'LEVEL0'))) {

            $field = new StdClass;
            $field->name = 'level0';
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $config->classification_value_table;
            $field->field = 'value';
            $field->select = $config->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
            $field->constraintson = 'level1,level2,level3';
            $field->mandatory = false;
            $this->fields['level0'] = $field;
        }

        if ($fieldid = $DB->get_field($config->classification_type_table, 'id', array('code' => 'LEVEL1'))) {

            $field = new StdClass;
            $field->name = 'level1';
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $config->classification_value_table;
            $field->field = 'value';
            $field->ordering = 'sortorder';
            $field->select = $config->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
<<<<<<< HEAD
=======
            $field->size = 8;
>>>>>>> MOODLE_35_STABLE
            $field->constraintson = 'level0,level2,level3';
            $field->mandatory = false;
            $this->fields['level1'] = $field;
        }

        if ($fieldid = $DB->get_field($config->classification_type_table, 'id', array('code' => 'LEVEL2'))) {

            $field = new StdClass;
            $field->name = 'level2';
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $config->classification_value_table;
            $field->field = 'value';
            $field->ordering = 'sortorder';
            $field->select = $config->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
            $field->size = 8;
            $field->constraintson = 'level0,level1,level3';
            $field->mandatory = false;
            $this->fields['level2'] = $field;
        }

        if ($fieldid = $DB->get_field($config->classification_type_table, 'id', array('code' => 'LEVEL3'))) {

            $field = new StdClass;
            $field->name = 'level3';
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $config->classification_value_table;
            $field->field = 'value';
            $field->select = $config->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
<<<<<<< HEAD
            $field->constraintson = 'level0,level1,level3';
            $field->mandatory = false;
            $this->fields['level2'] = $field;
        }

        if ($fieldid = $DB->get_field($config->classification_type_table, 'id', array('code' => 'LEVEL3'))) {

            $field = new StdClass;
            $field->name = 'level3';
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $config->classification_value_table;
            $field->field = 'value';
            $field->select = $config->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
=======
>>>>>>> MOODLE_35_STABLE
            $field->constraintson = 'level0,level1,level2';
            $field->mandatory = false;
            $this->fields['level2'] = $field;
        }

        // Get all course filters.
        $coursefilters = $DB->get_records($config->classification_type_table, array('type' => 'coursefilter'));

        foreach ($coursefilters as $coursefilter) {

            $showkey = 'show'.strtolower($coursefilter->code);
            $key = strtolower($coursefilter->code);

            $field = new StdClass;
            $field->name = $showkey;
            $field->type = 'choiceyesno';
            $field->default = 1;
            $field->label = get_string('show', 'mod_customlabel').' '.$coursefilter->name;
            $this->fields[$showkey] = $field;

            $field = new StdClass;
            $field->name = $key;
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $config->classification_value_table;
            $field->field = 'value';
            $field->ordering = 'sortorder';
            $field->multiple = 'multiple';
            $field->size = 8;
            $field->label = $coursefilter->name;
            $field->select = $config->classification_value_type_key.' = '.$coursefilter->id;
            $this->fields[$key] = $field;
        }

        unset($field);

    }

    public function on_delete() {
        global $DB, $COURSE;

        $config = get_config('customlabel');

        // Remove all old classification.

        $DB->delete_records($config->course_metadata_table, array($config->course_metadata_course_key => $COURSE->id));
    }

    public function preprocess_data() {
        global $DB;

        $config = get_config('customlabel');

        $this->data->classifiers = false;
        $this->data->classifierrows = '';
        $coursefilters = $DB->get_records($config->classification_type_table, array('type' => 'coursefilter'));

        foreach ($coursefilters as $coursefilter) {
            $showkey = 'show'.strtolower($coursefilter->code);
            $key = strtolower($coursefilter->code);

            if (!empty($this->data->$showkey)) {
                $this->data->classifiers = true;
                $classif = new StdClass();
                $classif->label = format_string($coursefilter->name);
                $classif->values = $this->data->$key;
                $this->data->classifierrows .= get_string('classifierrow', 'customlabeltype_courseclassifier', $classif);
            }
        }
    }

    /**
     *
     *
     */
    public function postprocess_data($course = null) {
        global $COURSE, $DB;

        if (!isset($this->data->coursemodule)) {
            // We are not really updating data.
            return;
        }

        $config = get_config('customlabel');

        // Have to save back datasource information within tables.
        $valuekey = $config->course_metadata_value_key;
        $coursekey = $config->course_metadata_course_key;

        // Remove all old classification.
        $DB->delete_records($config->course_metadata_table, array($config->course_metadata_course_key => $COURSE->id));

        // Add updated level0.
        $cc = new StdClass;
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level0option)) {
            if (is_array($this->data->level0option)) {
                foreach ($this->data->level0option as $method) {
                    $cc->$valuekey = $method;
                    $DB->insert_record($config->course_metadata_table, $cc);
                }
            } else {
                $cc->$valuekey = $this->data->level0option;
                $DB->insert_record($config->course_metadata_table, $cc);
            }
        }

        // Add updated level1.
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level1option)) {
            if (is_array($this->data->level1option)) {
                foreach ($this->data->level1option as $method) {
                    $cc->$valuekey = $method;
                    $DB->insert_record($config->course_metadata_table, $cc);
                }
            } else {
                $cc->$valuekey = $this->data->level1option;
                $DB->insert_record($config->course_metadata_table, $cc);
            }
        }

        // Add updated level2.
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level2option)) {
            if (is_array($this->data->level2option)) {
                foreach ($this->data->level2option as $method) {
                    $cc->$valuekey = $method;
                    $DB->insert_record($config->course_metadata_table, $cc);
                }
            } else {
                $cc->$valuekey = $this->data->level2option;
                $DB->insert_record($config->course_metadata_table, $cc);
            }
        }

        // Get all course filters.
        $this->data->classifiers = false;
        $this->data->classifierrows = '';
        $coursefilters = $DB->get_records($config->classification_type_table, array('type' => 'coursefilter'));

        foreach ($coursefilters as $coursefilter) {

            $cc->$coursekey = $COURSE->id;

            $optionkey = strtolower($coursefilter->code).'option';

            if (!empty($this->data->$optionkey)) {
                if (is_array($this->data->$optionkey)) {
                    foreach ($this->data->$optionkey as $optid => $optvalue) {
                        $cc->$valuekey = $optvalue;
                        $DB->insert_record($config->course_metadata_table, $cc);
                    }
                } else {
                    $cc->$valuekey = $this->data->$optionkey;
                    $DB->insert_record($config->course_metadata_table, $cc);
                }
            } else {
                $this->data->$optionkey;
            }
        }
    }

    protected function get_constraints($levels, $minus) {
        for ($i = 0; $i < $levels; $i++) {
            if ($i != $minus) {
                $consttraintset[] = 'level'.$i;
            }
        }
        return implode(',', $constraintset);
    }

    protected function get_constraints($levels, $minus) {
        for ($i = 0; $i < $levels; $i++) {
            if ($i != $minus) {
                $consttraintset[] = 'level'.$i;
            }
        }
        return implode(',', $constraintset);
    }
}
