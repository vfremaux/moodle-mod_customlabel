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
 * this admin screen allows updating massively all customlabels when a change has been proceeded
 * within the templates. Can only be done in one language.
 *
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class customlabel_updateall_form extends moodleform {

    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'scope', get_string('updatescope', 'customlabel'));

        $attrs = array('size' => 10);
        $select = &$mform->addElement('select', 'courses', get_string('courses'), $this->_customdata['courses'], $attrs);
        $mform->addRule('courses', null, 'required', null, 'client');
        $select->setMultiple(true);

        $label = get_string('labelclasses', 'customlabel');
        $select = &$mform->addElement('select', 'labelclasses', $label, $this->_customdata['types'], array('size' => 10));
        $select->setMultiple(true);

        $updatestr = get_string('doupdate', 'customlabel');
        $this->add_action_buttons(true, $updatestr);
    }
}