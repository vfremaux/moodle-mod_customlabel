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
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir.'/formslib.php';

class EditValueForm extends moodleform {

    private $view;
    private $action;
    private $type;

    public function __construct($view, $action, $type, $url) {
        $this->view = $view;
        $this->action = $action;
        $this->type = $type;
        parent::moodleform($url);
    }

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

        $mform->addElement('text', 'code', get_string('code', 'customlabel'), array('size' => 15));
        $mform->setType('code', PARAM_ALPHANUM);

        $mform->addElement('text', 'value', get_string('name'), array('size' => 60));
        $mform->setType('value', PARAM_TEXT);

        $this->add_action_buttons(false);
    }

    public function validation($data, $files = null) {
        $errors = array();

        if (empty($data['value'])) {
            $errors['value'] = get_string('emptyvalueerror', 'customlabel');
        }

        return $errors;
    }
}
