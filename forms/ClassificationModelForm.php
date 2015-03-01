<?php

require_once $CFG->libdir.'/formslib.php';

class ModelForm extends moodleform{
    
    function definition() {
        $mform = & $this->_form;

        $mform->addElement('hidden', 'view');
        $mform->setType('view', PARAM_TEXT);

        $mform->addElement('text', 'classification_type_table', get_string('classificationtypetable', 'customlabel'), array('size' => 28, 'maxlength' => 28));
        $mform->setDefault('classification_type_table', 'customlabel_mtd_type');
        $mform->setType('classification_type_table', PARAM_TEXT);
        $mform->addHelpButton('classification_type_table', 'classificationtypetable', 'customlabel');

        $mform->addElement('text', 'classification_value_table', get_string('classificationvaluetable', 'customlabel'), array('size' => 28, 'maxlength' => 28));
        $mform->setDefault('classification_value_table', 'customlabel_mtd_value');
        $mform->setType('classification_value_table', PARAM_TEXT);
        $mform->addHelpButton('classification_value_table', 'classificationvaluetable', 'customlabel');

        $mform->addElement('text', 'classification_value_type_key', get_string('classificationvaluetypekey', 'customlabel'), array('size' => 15, 'maxlength' => 28));
        $mform->setDefault('classification_value_type_key', 'typeid');
        $mform->setType('classification_value_type_key', PARAM_TEXT);
        $mform->addHelpButton('classification_value_type_key', 'classificationvaluetypekey', 'customlabel');

        $mform->addElement('text', 'classification_constraint_table', get_string('classificationconstrainttable', 'customlabel'), array('size' => 28, 'maxlength' => 28));
        $mform->setDefault('classification_constraint_table', 'customlabel_mtd_constraint');
        $mform->setType('classification_constraint_table', PARAM_TEXT);
        $mform->addHelpButton('classification_constraint_table', 'classificationconstrainttable', 'customlabel');

        $mform->addElement('text', 'course_metadata_table', get_string('coursemetadatatable', 'customlabel'), array('size' => 28, 'maxlength' => 28));
        $mform->setDefault('course_metadata_table', 'customlabel_course_metadata');
        $mform->setType('course_metadata_table', PARAM_TEXT);
        $mform->addHelpButton('course_metadata_table', 'coursemetadatatable', 'customlabel');

        $mform->addElement('text', 'course_metadata_value_key', get_string('coursemetadatavaluekey', 'customlabel'), array('size' => 15, 'maxlength' => 28));
        $mform->setDefault('course_metadata_value_key', 'valueid');
        $mform->setType('course_metadata_value_key', PARAM_TEXT);
        $mform->addHelpButton('course_metadata_value_key', 'coursemetadatavaluekey', 'customlabel');

        $mform->addElement('text', 'course_metadata_course_key', get_string('coursemetadatacoursekey', 'customlabel'), array('size' => 15, 'maxlength' => 28));
        $mform->setDefault('course_metadata_course_key', 'courseid');
        $mform->setType('course_metadata_course_key', PARAM_TEXT);
        $mform->addHelpButton('course_metadata_course_key', 'coursemetadatacoursekey', 'customlabel');
        
        $this->add_action_buttons(false);
    }

}