<?php

require_once $CFG->libdir.'/formslib.php';

class EditValueForm extends moodleform{
    
    private $view;
    private $action;
    private $type;
    
    function __construct($view, $action, $type, $url){
        $this->view = $view;
        $this->action = $action;
        $this->type = $type;
        parent::moodleform($url);
    }

    function definition(){
        $mform = & $this->_form;
        
        $options[0] = get_string('category', 'customlabel');
        $options[1] = get_string('filter', 'customlabel');
        
        $mform->addElement('hidden', 'view', $this->view);
        $mform->addElement('hidden', 'what', $this->action);
        $mform->addElement('hidden', 'typeid', $this->type);
        $mform->addElement('hidden', 'id', '');
        $mform->addElement('text', 'code', get_string('code', 'customlabel'), array('size' => 15));
        $mform->addElement('text', 'value', get_string('name'), array('size' => 60));

        $this->add_action_buttons(false);
    }
    
    function validation($data, $files = null){
        $errors = array();
        
        if (empty($data['value'])){
            $errors['value'] = get_string('emptyvalueerror', 'customlabel');
        }
        
        return $errors;
    }
}
