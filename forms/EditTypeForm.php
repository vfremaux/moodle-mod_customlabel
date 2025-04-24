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
 * Type edition form
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux (www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

/**
 * Form for classification types.
 */
class EditTypeForm extends moodleform {

    /** @var string */
    private $view;

    /** @var string */
    private $action;

    /**
     * Constructor
     */
    public function __construct($view, $action, $url) {
        $this->view = $view;
        $this->action = $action;
        parent::__construct($url);
    }

    /**
     * Standard definition
     */
    public function definition() {
        $mform = & $this->_form;

        $options['category'] = get_string('category', 'customlabel');
        $options['filter'] = get_string('filter', 'customlabel');
        $options['coursefilter'] = get_string('coursefilter', 'customlabel');

        $mform->addElement('hidden', 'view', $this->view);
        $mform->setType('view', PARAM_TEXT);

        $mform->addElement('hidden', 'what', $this->action);
        $mform->setType('what', PARAM_TEXT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('select', 'type', get_string('typetype', 'customlabel'), $options);
        $mform->setType('type', PARAM_TEXT);
        $mform->addHelpButton('type', 'typetype', 'customlabel');

        $mform->addElement('text', 'code', get_string('code', 'customlabel'), ['size' => 15]);
        $mform->setType('code', PARAM_ALPHANUM);
        $mform->addHelpButton('code', 'typecode', 'customlabel');

        $mform->addElement('text', 'name', get_string('name'), ['size' => 30]);
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('textarea', 'description', get_string('description'), ['rows' => 3, 'cols' => 30]);
        $mform->setType('description', PARAM_TEXT);

        $this->add_action_buttons(false);
    }

    /**
     * Standard validation
     * @param object $data
     * @param array $files
     */
    public function validation($data, $files = null) {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = get_string('emptytypenameerror', 'customlabel');
        }

        if (in_array($data['code'], ['type'])) {
            $errors['code'] = get_string('errorreservedname', 'customlabel');
        }

        return $errors;
    }
}
