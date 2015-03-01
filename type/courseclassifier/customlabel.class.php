<?php

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_courseclassifier extends customlabel_type{

    function __construct($data) {
        global $CFG, $DB;

        parent::__construct($data);
        $this->type = 'courseclassifier';
        $this->fields = array();
        
        $CFG->classification_type_table = 'customlabel_mtd_type';
        $CFG->classification_value_type_key = 'typeid';
        $CFG->course_metadata_table = 'customlabel_course_metadata';
        $CFG->course_metadata_value_key = 'valueid';
        $CFG->course_metadata_course_key = 'courseid';

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

        if ($fieldid = $DB->get_field($CFG->classification_type_table, 'id', array('code' => 'LEVEL0'))) {

            $field = new StdClass;
            $field->name = 'level0';
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $CFG->classification_value_table;
            $field->field = 'value';
            $field->select = $CFG->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
            $field->constraintson = 'level1,level2';
            $field->mandatory = true;
            $this->fields['level0'] = $field;
        }

        if ($fieldid = $DB->get_field($CFG->classification_type_table, 'id', array('code' => 'LEVEL1'))) {

            $field = new StdClass;
            $field->name = 'level1';
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $CFG->classification_value_table;
            $field->field = 'value';
            $field->select = $CFG->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
            $field->constraintson = 'level0,level2';
            $field->mandatory = true;
            $this->fields['level1'] = $field;
        }

        if ($fieldid = $DB->get_field($CFG->classification_type_table, 'id', array('code' => 'LEVEL2'))) {

            $field = new StdClass;
            $field->name = 'level2';
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $CFG->classification_value_table;
            $field->field = 'value';
            $field->select = $CFG->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
            $field->constraintson = 'level0,level1';
            $field->mandatory = true;
            $this->fields['level2'] = $field;
        }

        if ($fieldid = $DB->get_field($CFG->classification_type_table, 'id', array('code' => 'PEOPLE'))) {

            $field = new StdClass;
            $field->name = 'showpeople';
            $field->type = 'choiceyesno';
            $this->fields['showpeople'] = $field;

            $field = new StdClass;
            $field->name = 'people';
            $field->type = 'datasource';
            $field->source = 'dbfieldkeyed';
            $field->table = $CFG->classification_value_table;
            $field->field = 'value';
            $field->select = $CFG->classification_value_type_key.' = '.$fieldid;
            $field->multiple = 'multiple';
            $field->constraintson = '';
            $field->mandatory = true;
            $this->fields['people'] = $field;
        }

        unset($field);

    }
    
    function on_delete() {
        global $CFG, $DB, $COURSE;
        
        // remove all old classification
        $DB->delete_records($CFG->course_metadata_table, array($CFG->course_metadata_course_key => $COURSE->id));
    }

    /**
    *
    *
    */
    function preprocess_data() {
        global $COURSE, $CFG, $DB;

        // Have to save back datasource information within tables.
        $valuekey = $CFG->course_metadata_value_key;
        $coursekey = $CFG->course_metadata_course_key;

        // remove all old classification
        $DB->delete_records($CFG->course_metadata_table, array($CFG->course_metadata_course_key => $COURSE->id));

        // add updated level0
        $cc = new StdClass;
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level0opt)) {
            if (is_array($this->data->level0opt)) {
                foreach ($this->data->level0opt as $method) {
                    $cc->$valuekey = $method;
                    if (!$DB->insert_record($CFG->course_metadata_table, $cc)) {
                        $OUTPUT->notification("Could not classify course");
                    }
                }
            } else {
                $cc->$valuekey = $this->data->level0opt;
                if (!$DB->insert_record($CFG->course_metadata_table, $cc)) {
                    $OUTPUT->notification("Could not classify course");
                }
            }
        }

        // add updated level1
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level1opt)) {
            if (is_array($this->data->level1opt)) {
                foreach ($this->data->level1opt as $method) {
                    $cc->$valuekey = $method;
                    if (!$DB->insert_record($CFG->course_metadata_table, $cc)) {
                        $OUTPUT->notification("Could not classify course");
                    }
                }
            } else {
                $cc->$valuekey = $this->data->level1opt;
                if (!$DB->insert_record($CFG->course_metadata_table, $cc)) {
                    $OUTPUT->notification("Could not classify course");
                }
            }
        }

        // add updated level2 
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->level2opt)) {
            if (is_array($this->data->level2opt)) {
                foreach ($this->data->level2opt as $method) {
                    $cc->$valuekey = $method;
                    if (!$DB->insert_record($CFG->course_metadata_table, $cc)) {
                        $OUTPUT->notification("Could not classify course");
                    }
                }
            } else {
                $cc->$valuekey = $this->data->level2opt;
                if (!$DB->insert_record('customlabel_course_metadata', $cc)) {
                    $OUTPUT->notification("Could not classify course");
                }
            }
        }

        // add updated people
        $cc->$coursekey = $COURSE->id;
        if (!empty($this->data->people)) {
            if (is_array($this->data->people)) {
                foreach ($this->data->people as $method) {
                    $cc->$valuekey = $method;
                    if (!$DB->insert_record($CFG->course_metadata_table, $cc)) {
                        $OUTPUT->notification("Could not classify course");
                    }
                }
            } else {
                $cc->$valuekey = $this->data->people;
                if (!insert_record($CFG->course_metadata_table, $cc)) {
                    $OUTPUT->notification("Could not classify course");
                }
            }
        }
    }
}

