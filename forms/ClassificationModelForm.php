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
 * Form for classification
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux (www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

/**
 * Form definition.
 */
class ModelForm extends moodleform {

    /**
     * Standard definition.
     */
    public function definition() {
        $mform = & $this->_form;

        $mform->addElement('hidden', 'view');
        $mform->setType('view', PARAM_TEXT);

        $label = get_string('configclassificationtypetable', 'customlabel');
        $mform->addElement('text', 'classification_type_table', $label, ['size' => 28, 'maxlength' => 28]);
        $mform->setDefault('classification_type_table', 'customlabel_mtd_type');
        $mform->setType('classification_type_table', PARAM_TEXT);
        $mform->addHelpButton('classification_type_table', 'classificationtypetable', 'customlabel');

        $label = get_string('configclassificationvaluetable', 'customlabel');
        $mform->addElement('text', 'classification_value_table', $label, ['size' => 28, 'maxlength' => 28]);
        $mform->setDefault('classification_value_table', 'customlabel_mtd_value');
        $mform->setType('classification_value_table', PARAM_TEXT);
        $mform->addHelpButton('classification_value_table', 'classificationvaluetable', 'customlabel');

        $label = get_string('configclassificationvaluetypekey', 'customlabel');
        $mform->addElement('text', 'classification_value_type_key', $label, ['size' => 15, 'maxlength' => 28]);
        $mform->setDefault('classification_value_type_key', 'typeid');
        $mform->setType('classification_value_type_key', PARAM_TEXT);
        $mform->addHelpButton('classification_value_type_key', 'classificationvaluetypekey', 'customlabel');

        $label = get_string('configclassificationconstrainttable', 'customlabel');
        $mform->addElement('text', 'classification_constraint_table', $label, ['size' => 28, 'maxlength' => 28]);
        $mform->setDefault('classification_constraint_table', 'customlabel_mtd_constraint');
        $mform->setType('classification_constraint_table', PARAM_TEXT);
        $mform->addHelpButton('classification_constraint_table', 'classificationconstrainttable', 'customlabel');

        $label = get_string('configcoursemetadatatable', 'customlabel');
        $mform->addElement('text', 'course_metadata_table', $label, ['size' => 28, 'maxlength' => 28]);
        $mform->setDefault('course_metadata_table', 'customlabel_course_metadata');
        $mform->setType('course_metadata_table', PARAM_TEXT);
        $mform->addHelpButton('course_metadata_table', 'coursemetadatatable', 'customlabel');

        $label = get_string('configcoursemetadatavaluekey', 'customlabel');
        $mform->addElement('text', 'course_metadata_value_key', $label, ['size' => 15, 'maxlength' => 28]);
        $mform->setDefault('course_metadata_value_key', 'valueid');
        $mform->setType('course_metadata_value_key', PARAM_TEXT);
        $mform->addHelpButton('course_metadata_value_key', 'coursemetadatavaluekey', 'customlabel');

        $label = get_string('configcoursemetadatacoursekey', 'customlabel');
        $mform->addElement('text', 'course_metadata_course_key', $label, ['size' => 15, 'maxlength' => 28]);
        $mform->setDefault('course_metadata_course_key', 'courseid');
        $mform->setType('course_metadata_course_key', PARAM_TEXT);
        $mform->addHelpButton('course_metadata_course_key', 'coursemetadatacoursekey', 'customlabel');

        $this->add_action_buttons(false);
    }
}
