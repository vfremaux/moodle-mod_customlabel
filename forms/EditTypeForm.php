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

class EditTypeForm extends moodleform{
    
    private $view;
    private $action;
    
    function __construct($view, $action, $url) {
        $this->view = $view;
        $this->action = $action;
        parent::moodleform($url);
    }

    function definition() {
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

        $mform->addElement('text', 'code', get_string('code', 'customlabel'), array('size' => 15));
        $mform->setType('code', PARAM_ALPHANUM);
        $mform->addHelpButton('code', 'typecode', 'customlabel');

        $mform->addElement('text', 'name', get_string('name'), array('size' => 30));
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('textarea', 'description', get_string('description'), array('rows' => 3, 'cols' => 30));
        $mform->setType('description', PARAM_TEXT);

        $this->add_action_buttons(false);
    }
    
    function validation($data, $files = null) {
        $errors = array();

        if (empty($data['name'])) {
            echo "is empty ?";
            $errors['name'] = get_string('emptytypenameerror', 'customlabel');
        }
        
        return $errors;
    }
}
