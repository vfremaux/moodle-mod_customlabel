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

        if (isset($data->content)) {
            // $data is a customlabel record not yet decoded. This comes from modedit.php
            $preset = json_decode(base64_decode($data->content));
            if (!empty($preset)) {
                // Decode content and append members to $data.
                foreach ($preset as $key => $value) {
                    $data->$key = $value;
                }
            }
        }

        if (empty($data)) {
            $data = new StdClass;
        }

        if (empty($data->uselevels)) {
            $data->uselevels = 2;
        }

        $field = new StdClass;
        $field->name = 'tablecaption';
        $field->type = 'textfield';
        $field->default = get_string('courseclassification', 'customlabeltype_courseclassifier');
        $this->fields['tablecaption'] = $field;

        $field = new StdClass;
        $field->name = 'uselevels';
        $field->type = 'list';
        $field->options = array('1', '2', '3', '4');
        $field->straightoptions = true;
        $field->mandatory = true;
        $field->default = 2;
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
            $field->constraintson = '1,2,3';
            $field->mandatory = false;
            $field->size = 8;
            $this->fields['level0'] = $field;
        }

        if ($data->uselevels > 1) {
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
                $field->size = 8;
                $field->constraintson = '0,2,3';
                $field->mandatory = false;
                $this->fields['level1'] = $field;
            }
        }

        if ($data->uselevels > 2) {
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
                $field->constraintson = '0,1,3';
                $field->mandatory = false;
                $this->fields['level2'] = $field;
            }
        }

        if ($data->uselevels > 3) {
            if ($fieldid = $DB->get_field($config->classification_type_table, 'id', array('code' => 'LEVEL3'))) {

                $field = new StdClass;
                $field->name = 'level3';
                $field->type = 'datasource';
                $field->source = 'dbfieldkeyed';
                $field->table = $config->classification_value_table;
                $field->field = 'value';
                $field->select = $config->classification_value_type_key.' = '.$fieldid;
                $field->multiple = 'multiple';
                $field->constraintson = '0,1,2';
                $field->mandatory = false;
                $field->size = 8;
                $this->fields['level2'] = $field;
            }
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
            $field->isfilter = 1;
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

    public function post_update() {
        global $DB, $COURSE;

        $config = get_config('customlabel');
        if (empty($this->data)) {
            $this->data = new StdClass;
        }

        // Have to save back datasource information within tables.
        $valuekey = $config->course_metadata_value_key;
        $coursekey = $config->course_metadata_course_key;

        // Remove all old classification.
        $DB->delete_records($config->course_metadata_table, array($config->course_metadata_course_key => $COURSE->id));

        // Add updated level0.
        $cc = new StdClass;
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level0) && ($this->data->level0 != '_qf__force_multiselect_submission')) {
            if (is_array($this->data->level0)) {
                foreach ($this->data->level0 as $method) {
                    $cc->$valuekey = $method;
                    $DB->insert_record($config->course_metadata_table, $cc);
                }
            } else {
                $cc->$valuekey = $this->data->level0;
                $DB->insert_record($config->course_metadata_table, $cc);
            }
        }

        // Add updated level1.
        $cc = new StdClass;
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level1) && ($this->data->level1 != '_qf__force_multiselect_submission')) {
            if (is_array($this->data->level1)) {
                foreach ($this->data->level1 as $method) {
                    $cc->$valuekey = $method;
                    $DB->insert_record($config->course_metadata_table, $cc);
                }
            } else {
                $cc->$valuekey = $this->data->level1;
                $DB->insert_record($config->course_metadata_table, $cc);
            }
        }

        // Add updated level2.
        $cc = new StdClass;
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level2) && ($this->data->level2 != '_qf__force_multiselect_submission')) {
            if (is_array($this->data->level2)) {
                foreach ($this->data->level2 as $method) {
                    $cc->$valuekey = $method;
                    $DB->insert_record($config->course_metadata_table, $cc);
                }
            } else {
                $cc->$valuekey = $this->data->level2;
                $DB->insert_record($config->course_metadata_table, $cc);
            }
        }

        // Add updated level3.
        $cc = new StdClass;
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level3) && ($this->data->level3 != '_qf__force_multiselect_submission')) {
            if (is_array($this->data->level3)) {
                foreach ($this->data->level3 as $method) {
                    $cc->$valuekey = $method;
                    $DB->insert_record($config->course_metadata_table, $cc);
                }
            } else {
                $cc->$valuekey = $this->data->level3;
                $DB->insert_record($config->course_metadata_table, $cc);
            }
        }

        // Get all course filters.
        $this->data->classifiers = false;
        $this->data->classifierrows = '';
        $coursefilters = $DB->get_records($config->classification_type_table, array('type' => 'coursefilter'));

        foreach ($coursefilters as $coursefilter) {

            $cc->$coursekey = $COURSE->id;

            $optionkey = strtolower($coursefilter->code);

            if (!empty($this->data->$optionkey) && ($this->data->$optionkey != '_qf__force_multiselect_submission')) {
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

    public function preprocess_data() {
        global $DB;

        $config = get_config('customlabel');

        $this->data->classifiers = false;
        $classifiers = $DB->get_records($config->classification_type_table, array('type' => 'category'));
        if ($classifiers) {
            foreach ($classifiers as $classif) {
                if ($classif->code == 'LEVEL0') {
                    $key = 'level0';
                    $classifiertpl = new StdClass();
                    $classifiertpl->label = format_string($classif->name);
                    $classifiertpl->values = implode(', ', $this->get_datasource_values($this->fields['level0'], $this->data->$key));
                    $this->data->classifiers[] = $classifiertpl;
                }
                if (($classif->code == 'LEVEL1') && $this->data->uselevels > 1) {
                    $key = 'level1';
                    $classifiertpl = new StdClass();
                    $classifiertpl->label = format_string($classif->name);
                    $classifiertpl->values = implode(', ', $this->get_datasource_values($this->fields['level1'], $this->data->$key));
                    $this->data->classifiers[] = $classifiertpl;
                }
                if (($classif->code == 'LEVEL2') && $this->data->uselevels > 2) {
                    $key = 'level2';
                    $classifiertpl = new StdClass();
                    $classifiertpl->label = format_string($classif->name);
                    $classifiertpl->values = implode(', ', $this->get_datasource_values($this->fields['level2'], $this->data->$key));
                    $this->data->classifiers[] = $classifiertpl;
                }
                if (($classif->code == 'LEVEL3') && $this->data->uselevels > 3) {
                    $key = 'level3';
                    $classifiertpl = new StdClass();
                    $classifiertpl->label = format_string($classif->name);
                    $classifiertpl->values = implode(', ', $this->get_datasource_values($this->fields['level3'], $this->data->$key));
                    $this->data->classifiers[] = $classifiertpl;
                }
            }
        }

        $coursefilters = $DB->get_records($config->classification_type_table, array('type' => 'coursefilter'));
        if ($coursefilters) {
            foreach ($coursefilters as $filter) {
                $showkey = 'show'.strtolower($filter->code);
                if (!empty($this->data->$showkey)) {
                    $this->data->hasfilters = true;
                    $filtertpl = new StdClass();
                    $filtertpl->label = format_string($filter->name);
                    $key = strtolower($filter->code);
                    $filtertpl->values = implode(', ', $this->get_datasource_values($this->fields[$key], $this->data->$key));
                    $this->data->filters[] = $filtertpl;
                }
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
}
