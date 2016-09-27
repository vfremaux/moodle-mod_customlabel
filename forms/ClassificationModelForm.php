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

defined('MOODLE_INTERNAL') || die();

/**
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */
require_once $CFG->libdir.'/formslib.php';

class ModelForm extends moodleform{

    function definition() {
        $mform = & $this->_form;

        $mform->addElement('hidden', 'view');
        $mform->setType('view', PARAM_TEXT);

        $mform->addElement('text', 'classification_type_table', get_string('configclassificationtypetable', 'customlabel'), array('size' => 28, 'maxlength' => 28));
        $mform->setDefault('classification_type_table', 'customlabel_mtd_type');
        $mform->setType('classification_type_table', PARAM_TEXT);
        $mform->addHelpButton('classification_type_table', 'classificationtypetable', 'customlabel');

        $mform->addElement('text', 'classification_value_table', get_string('configclassificationvaluetable', 'customlabel'), array('size' => 28, 'maxlength' => 28));
        $mform->setDefault('classification_value_table', 'customlabel_mtd_value');
        $mform->setType('classification_value_table', PARAM_TEXT);
        $mform->addHelpButton('classification_value_table', 'classificationvaluetable', 'customlabel');

        $mform->addElement('text', 'classification_value_type_key', get_string('configclassificationvaluetypekey', 'customlabel'), array('size' => 15, 'maxlength' => 28));
        $mform->setDefault('classification_value_type_key', 'typeid');
        $mform->setType('classification_value_type_key', PARAM_TEXT);
        $mform->addHelpButton('classification_value_type_key', 'classificationvaluetypekey', 'customlabel');

        $mform->addElement('text', 'classification_constraint_table', get_string('configclassificationconstrainttable', 'customlabel'), array('size' => 28, 'maxlength' => 28));
        $mform->setDefault('classification_constraint_table', 'customlabel_mtd_constraint');
        $mform->setType('classification_constraint_table', PARAM_TEXT);
        $mform->addHelpButton('classification_constraint_table', 'classificationconstrainttable', 'customlabel');

        $mform->addElement('text', 'course_metadata_table', get_string('configcoursemetadatatable', 'customlabel'), array('size' => 28, 'maxlength' => 28));
        $mform->setDefault('course_metadata_table', 'customlabel_course_metadata');
        $mform->setType('course_metadata_table', PARAM_TEXT);
        $mform->addHelpButton('course_metadata_table', 'coursemetadatatable', 'customlabel');

        $mform->addElement('text', 'course_metadata_value_key', get_string('configcoursemetadatavaluekey', 'customlabel'), array('size' => 15, 'maxlength' => 28));
        $mform->setDefault('course_metadata_value_key', 'valueid');
        $mform->setType('course_metadata_value_key', PARAM_TEXT);
        $mform->addHelpButton('course_metadata_value_key', 'coursemetadatavaluekey', 'customlabel');

        $mform->addElement('text', 'course_metadata_course_key', get_string('configcoursemetadatacoursekey', 'customlabel'), array('size' => 15, 'maxlength' => 28));
        $mform->setDefault('course_metadata_course_key', 'courseid');
        $mform->setType('course_metadata_course_key', PARAM_TEXT);
        $mform->addHelpButton('course_metadata_course_key', 'coursemetadatacoursekey', 'customlabel');
        
        $this->add_action_buttons(false);
    }

}