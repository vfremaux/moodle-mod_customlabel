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
 * Form fo editing classification values.
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux (www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

/**
 * Form for values
 */
class EditValueForm extends moodleform {

    /**  @param string */
    private $view;

    /**  @param string */
    private $action;

    /**  @param string */
    private $type;

    /**
     * Constructor
     */
    public function __construct($view, $action, $type, $url) {
        $this->view = $view;
        $this->action = $action;
        $this->type = $type;
        parent::__construct($url);
    }

    /**
     * Standard definition
     */
    public function definition() {
        $mform = & $this->_form;

        $options[0] = get_string('category', 'customlabel');
        $options[1] = get_string('filter', 'customlabel');

        $mform->addElement('hidden', 'view', $this->view);
        $mform->setType('view', PARAM_TEXT);

        $mform->addElement('hidden', 'what', $this->action);
        $mform->setType('what', PARAM_TEXT);

        $mform->addElement('hidden', 'typeid', $this->type);
        $mform->setType('typeid', PARAM_INT);

        $mform->addElement('hidden', 'id', '');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'code', get_string('code', 'customlabel'), ['size' => 15]);
        $mform->setType('code', PARAM_ALPHANUM);

        $mform->addElement('text', 'value', get_string('name'), ['size' => 60]);
        $mform->setType('value', PARAM_TEXT);

        $this->add_action_buttons(false);
    }

    /**
     * Standard validation
     */
    public function validation($data, $files = null) {
        $errors = [];

        if (empty($data['value'])) {
            $errors['value'] = get_string('emptyvalueerror', 'customlabel');
        }

        return $errors;
    }
}
