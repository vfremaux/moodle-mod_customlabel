<?php
/**
 * this admin screen allows updating massively all customlabels when a change has been proceeded
 * within the templates. Can only be done in one language.
 *
 * @package    mod-customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */
if (!defined('MOODLE_INTERNAL')) die ('You cannot use this script directly');

require $CFG->libdir.'/formslib.php';

class customlabel_updateall_form extends moodleform {

    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'scope', get_string('updatescope', 'customlabel'));

        $select = &$mform->addElement('select', 'courses', get_string('courses'), $this->_customdata['courses'], array('size' => 10));
        $mform->addRule('courses', null, 'required', null, 'client');
        $select->setMultiple(true);

        $select = &$mform->addElement('select', 'labelclasses', get_string('labelclasses', 'customlabel'), $this->_customdata['types'], array('size' => 10));
        $select->setMultiple(true);

        $updatestr = get_string('doupdate', 'customlabel');
        $this->add_action_buttons(true, $updatestr);
    }
}

