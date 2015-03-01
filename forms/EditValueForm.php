<?php

require_once $CFG->libdir.'/formslib.php';

class EditValueForm extends moodleform{
    
    private $view;
    private $action;
    private $type;
    
    function __construct($view, $action, $type, $url) {
        $this->view = $view;
        $this->action = $action;
        $this->type = $type;
        parent::moodleform($url);
    }

    function definition() {
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
    
    function validation($data, $files = null) {
        $errors = array();
        
        if (empty($data['value'])) {
            $errors['value'] = get_string('emptyvalueerror', 'customlabel');
        }
        
        return $errors;
    }
}
