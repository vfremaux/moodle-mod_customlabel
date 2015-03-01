<?php

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
        
        $mform->addElement('hidden', 'view', $this->view);
        $mform->setType('view', PARAM_TEXT);

        $mform->addElement('hidden', 'what', $this->action);
        $mform->setType('what', PARAM_TEXT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('select', 'type', get_string('typetype', 'customlabel'), $options);
        $mform->setType('type', PARAM_TEXT);

        $mform->addElement('text', 'code', get_string('code', 'customlabel'), array('size' => 15));
        $mform->setType('code', PARAM_ALPHANUM);

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
