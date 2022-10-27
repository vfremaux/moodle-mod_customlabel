<?php

require_once($CFG->libdir.'/formslib.php');

class customlabeltype_localdokuwikicontent_get_page_form extends moodleform {
    /**
     * The form definition.
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        $mform->addElement('header', 'wstestclienthdr', get_string('testclient', 'webservice'));

        // Note: these values are intentionally PARAM_RAW - we want users to test any rubbish as parameters.
        $data = $this->_customdata;
        if ($data['authmethod'] == 'simple') {
            $mform->addElement('text', 'wsusername', 'wsusername');
            $mform->setType('wsusername', PARAM_USERNAME);
            $mform->addElement('text', 'wspassword', 'wspassword');
            $mform->setType('wspassword', PARAM_RAW);
        } else if ($data['authmethod'] == 'token') {
            $mform->addElement('text', 'token', 'token');
            $mform->setType('token', PARAM_RAW_TRIMMED);
        }

        $mform->addElement('hidden', 'authmethod', $data['authmethod']);
        $mform->setType('authmethod', PARAM_ALPHA);

        $mform->addElement('text', 'page', get_string('contentpage', 'customlabeltype_localdokuwikicontent'));
        $mform->setType('page', PARAM_TEXT);

        $mform->addElement('text', 'lang', get_string('lang', 'customlabeltype_localdokuwikicontent'));
        $mform->setType('lang', PARAM_TEXT);

        $mform->addElement('hidden', 'function');
        $mform->setType('function', PARAM_PLUGIN);

        $mform->addElement('hidden', 'protocol');
        $mform->setType('protocol', PARAM_ALPHA);

        $this->add_action_buttons(true, get_string('execute', 'webservice'));
    }

    /**
     * Get the parameters that the user submitted using the form.
     * @return array|null
     */
    public function get_params() {
        if (!$data = $this->get_data()) {
            return null;
        }
        // Remove unused from form data.
        unset($data->submitbutton);
        unset($data->protocol);
        unset($data->function);
        unset($data->wsusername);
        unset($data->wspassword);
        unset($data->token);
        unset($data->authmethod);

        $params['setting'] = $data->setting;
        $params['allhosts'] = (@$data->allhosts) ? 1 : 0;
        $params['dateformat'] = (@$data->dateformat) ? $data->dateformat : 0;
        return $params;
    }
}