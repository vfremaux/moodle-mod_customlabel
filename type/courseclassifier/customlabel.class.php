<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_courseclassifier extends customlabel_type {

    function __construct($data) {
        global $CFG, $DB;

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
            $field->constraintson = 'level1,level2';
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
            $field->select = $config->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
            $field->constraintson = 'level0,level2';
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
            $field->select = $config->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
            $field->constraintson = 'level0,level1';
            $field->mandatory = false;
            $this->fields['level2'] = $field;
        }

        // Get all course filters
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
            $field->multiple = 'multiple';
            $field->label = $coursefilter->name;
            $field->select = $config->classification_value_type_key.' = '.$coursefilter->id;
            $this->fields[$key] = $field;
        }

        unset($field);

    }

    function on_delete() {
        global $CFG, $DB, $COURSE;

        $config = get_config('customlabel');

        // Remove all old classification

        $DB->delete_records($config->course_metadata_table, array($config->course_metadata_course_key => $COURSE->id));
    }

    function preprocess_data() {
        global $DB;

        $config = get_config('customlabel');

        $this->data->classifiers = false;
        $this->data->classifierrows = '';
        $coursefilters = $DB->get_records($config->classification_type_table, array('type' => 'coursefilter'));

        foreach ($coursefilters as $coursefilter) {
            $showkey = 'show'.strtolower($coursefilter->code);
            $key = strtolower($coursefilter->code);

            if ($this->data->$showkey) {
                $this->data->classifiers = true;
                $classif = new StdClass();
                $classif->label = $coursefilter->name;
                $classif->values = $this->data->$key;
                $this->data->classifierrows .= get_string('classifierrow', 'customlabeltype_courseclassifier', $classif);
            }
        }
    }

    /**
     *
     *
     */
    function postprocess_data($course = null) {
        global $COURSE, $CFG, $DB;

        if (!isset($this->data->coursemodule)) {
            // We are not really updating data.
            return;
        }

        $config = get_config('customlabel');

        // Have to save back datasource information within tables.
        $valuekey = $config->course_metadata_value_key;
        $coursekey = $config->course_metadata_course_key;

        // Remove all old classification
        $DB->delete_records($config->course_metadata_table, array($config->course_metadata_course_key => $COURSE->id));

        // add updated level0
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

        // add updated level1
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

        // add updated level2
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

        // Get all course filters
        $this->data->classifiers = false;
        $this->data->classifierrows = '';
        $coursefilters = $DB->get_records($config->classification_type_table, array('type' => 'coursefilter'));

        foreach ($coursefilters as $coursefilter) {

            $cc->$coursekey = $COURSE->id;

            $optionkey = strtolower($coursefilter->code).'option';

            if (!empty($this->data->$optionkey)) {
                if (is_array($this->data->$optionkey)) {
                    foreach ($this->data->$optionkey as $optid => $method) {
                        $cc->$valuekey = $optid;
                        $DB->insert_record($config->course_metadata_table, $cc);
                    }
                } else {
                    $cc->$valuekey = $this->data->$optionkey;
                    $DB->insert_record($config->course_metadata_table, $cc);
                }
            }
        }
    }
}
