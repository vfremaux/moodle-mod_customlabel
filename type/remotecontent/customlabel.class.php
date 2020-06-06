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
 * @package customlabel
 * @category mod
 * @subpackage document_wrappers
 * @author Valery Fremaux [valery.fremaux@gmail.com] > 1.9
 * @date 2008/03/31
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 * this defines a set of fields. You just need defining fields and add them to the class,
 * then make an HTML template that uses <%%fieldname%%> calls, using style classing, and
 * finally add a customlabel.css within the same directory
 */

class customlabel_type_remotecontent extends customlabel_type {

    const CONTEXT_NO_SEND = 0;
    const CONTEXT_SEND_PLAIN = 1;
    const CONTEXT_SEND_CRYPTED = 2;

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'remotecontent';
        $this->fields = array();

        $protocoloptions = ['soap', 'rest', 'moodlews', 'htmlcapture'];

        $field = new StdClass;
        $field->name = 'protocol';
        $field->type = 'list';
        $field->options = $protocoloptions;
        $this->fields['protocol'] = $field;

        // Rest params
        $field = new StdClass;
        $field->name = 'resthdr';
        $field->type = 'header';
        $field->header = get_string('resthdr', 'customlabeltype_remotecontent');
        $this->fields['resthdr'] = $field;

        $field = new StdClass;
        $field->name = 'resturl';
        $field->type = 'textfield';
        $field->size = 120;
        $this->fields['resturl'] = $field;

        $receptionformatoptions = ['plain', 'json', 'xml'];

        $field = new StdClass;
        $field->name = 'receptionformat';
        $field->type = 'list';
        $field->options = $receptionformatoptions;
        $this->fields['receptionformat'] = $field;

        // Soap params
        $field = new StdClass;
        $field->name = 'soaphdr';
        $field->type = 'header';
        $field->header = get_string('soaphdr', 'customlabeltype_remotecontent');
        $this->fields['soaphdr'] = $field;

        $field = new StdClass;
        $field->name = 'wsdlurl';
        $field->type = 'textfield';
        $field->size = 120;
        $this->fields['wsdlurl'] = $field;

        $field = new StdClass;
        $field->name = 'soapfunction';
        $field->type = 'textfield';
        $field->size = 120;
        $this->fields['soapfunction'] = $field;

        $field = new StdClass;
        $field->name = 'soapparams';
        $field->type = 'textarea';
        $field->rows = 5;
        $field->cols = 80;
        $this->fields['soapparams'] = $field;

        $field = new StdClass;
        $field->name = 'soaplogin';
        $field->type = 'textfield';
        $field->size = 10;
        $this->fields['soaplogin'] = $field;

        $field = new StdClass;
        $field->name = 'soappassword';
        $field->type = 'textfield';
        $field->size = 16;
        $this->fields['soappassword'] = $field;

        // Moodle WS options
        $field = new StdClass;
        $field->name = 'moodlewshdr';
        $field->type = 'header';
        $field->header = get_string('moodlewshdr', 'customlabeltype_remotecontent');
        $this->fields['moodlewshdr'] = $field;

        $wsprotocoloptions = ['rest', 'soap'];

        $field = new StdClass;
        $field->name = 'wsprotocol';
        $field->type = 'list';
        $field->options = $wsprotocoloptions;
        $this->fields['wsprotocol'] = $field;

        $field = new StdClass;
        $field->name = 'wsfunction';
        $field->type = 'textfield';
        $field->size = 40;
        $this->fields['wsfunction'] = $field;

        $authtypeoptions = ['token', 'params'];

        $field = new StdClass;
        $field->name = 'wsauthtype';
        $field->type = 'list';
        $field->options = $authtypeoptions; // This can be changed to whatever any menu_list.
        $this->fields['wsauthtype'] = $field;

        $field = new StdClass;
        $field->name = 'wstoken';
        $field->type = 'textfield';
        $field->size = 40;
        $this->fields['wstoken'] = $field;

        $field = new StdClass;
        $field->name = 'wsparams';
        $field->type = 'textfield';
        $field->size = 80;
        $this->fields['wsparams'] = $field;

        /*
         * Other options.
         */
        $field = new StdClass;
        $field->name = 'htmlcapturehdr';
        $field->type = 'header';
        $field->header = get_string('htmlcapturehdr', 'customlabeltype_remotecontent');
        $this->fields['htmlcapturehdr'] = $field;

        /*
         * Type : Section Header
         * Other options.
         */
        $field = new StdClass;
        $field->name = 'otherhdr';
        $field->type = 'header';
        $field->header = get_string('otherhdr', 'customlabeltype_remotecontent');
        $this->fields['otherhdr'] = $field;

        $contextoptions = [
            self::CONTEXT_NO_SEND => get_string('contextnosend', 'customlabeltype_remotecontent'),
            self::CONTEXT_SEND_PLAIN => get_string('contextsendplain', 'customlabeltype_remotecontent'),
            self::CONTEXT_SEND_CRYPTED => get_string('contextsendcrypted', 'customlabeltype_remotecontent'),
        ];

        /*
         * Type: Boolean
         * Feature: allows sending local context ids to the remote data provider (userid, courseid, categoryid, groupid, etc.)
         */
        $field = new StdClass;
        $field->name = 'sendcontext';
        $field->type = 'list';
        $field->options = $contextoptions; // This can be changed to whatever any menu_list.
        $field->straightoptions = true;
        $this->fields['sendcontext'] = $field;

        /*
         * Type: CSS Text
         * Feature: Allow adding a specific additional set of CSS rules for the formatted content.
         */
        $field = new StdClass;
        $field->name = 'extracss';
        $field->type = 'textarea';
        $field->cols = 80;
        $field->rows = 5;
        $this->fields['extracss'] = $field;

        /*
         * Type: Mustache Text
         * Feature: Allow defining a mustache String that will format the received data.
         */
        $field = new StdClass;
        $field->name = 'mustache';
        $field->type = 'textarea';
        $field->cols = 80;
        $field->rows = 10;
        $this->fields['mustache'] = $field;

    }

    public function preprocess_data() {

        $str = '';

        switch ($this->config->method) {

            case 'moodlews': {
                $str .= $this->get_moodlews_content();
                break;
            }

            case 'rest': {
                $str .= $this->get_rest_content();
                break;
            }

            case 'soap': {
                $str .= $this->get_soap_content();
                break;
            }

            case 'htmlcapture': {
                $str .= $this->capture_remote_html();
                break;
            }
        }

        $this->data->remotecontent = $str;
    }

    protected function get_moodlews_content() {
        global $PAGE, $CFG;

        $renderer = $PAGE->get_renderer('block_remote_content');

        $params = array('wstoken' => $this->data->wstoken);

        if ($this->config->wsprotocol == 'rest') {
            $params['wsfunction'] = $this->data->wsfunction;
            $params['moodlewsrestformat'] = 'json';
        }

        if ($paramarr = explode('&', $this->data->wsparams)) {
            foreach ($paramarr as $pair) {
                $parts = explode('=', $pair);
                $key = array_shift($parts);
                $value = implode('', $parts);
                $params[$key] = $value;
            }
        }

        if ($this->config->wsprotocol == 'rest') {

            $serviceurl = $this->config->baseurl.'/webservice/rest/server.php';

            $result = $this->send_moodlews($serviceurl, $params);

        } else {
            // Call Moodle WS using a Soap client.
            $serviceurl = $this->config->baseurl.'/webservice/soap/server.php';

            $options = array('trace' => 1);

            $opts = array(
                'http' => array(
                    'user_agent' => 'Moodle SOAP Client'
                )
            );
            $context = stream_context_create($opts);
            $options['stream_context'] = $context;
            $options['cache_wsdl'] = WSDL_CACHE_NONE;

            $options['location'] = $serviceuri;
            $options['uri'] = $CFG->wwwroot;
            $client = new SoapClient(null, $options);

            $result = $client->__soapCall($this->config->wsfunction, $paramarr);
        }

        $data = json_decode($result);

        if (!empty($this->data->mustache)) {

            $content = '';

            if (!is_array($data)) {
                // Converts int a single object array to render one template instance.
                $data = array($data);
            }

            foreach($data as $datum) {
                $mustache = $renderer->get_mustache();
                $mustache->setLoader(new Mustache_Loader_StringLoader());
                $template = $mustache->loadTemplate($this->config->mustache);
                $content .= $template->render($datum);
            }

            return $content;
        } else {
            return print_r($data);
        }
    }

    protected function get_rest_content() {
        $content = $this->send_curl_get($this->data->resturl);

        return $content;
    }

    protected function get_soap_content() {
        $content = $this->send_soap_request();

        return $content;
    }

    protected function capture_remote_html() {
        $content = $this->send_curl_get($this->data->captureurl);

        $pattern = '/'.str_replace('/', '\\/', $this->data->frompattern);
        $pattern .= '(.*?)'.str_replace('/', '\\/', $this->data->topattern).'/';

        preg_match($pattern, $content, $matches);

        return $matches[1];
    }

    protected function send_moodlews($serviceurl, $params) {

        $ch = curl_init($serviceurl);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        if (!$result = curl_exec($ch)) {
            throw new Exception("CURL Error : ".curl_errno($ch).' '.curl_error($ch)."\n");
        }

        if (preg_match('/EXCEPTION/', $result)) {
            throw new Exception("Moodle WS Error : ".$result."\n");
        }

        return $result;
    }

    protected function send_curl_get($url) {

        if (!empty($this->data->sendcontext)) {
            $this->add_context($url);
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!$result = curl_exec($ch)) {
            throw new Exception("CURL Get Error : ".curl_errno($ch).' '.curl_error($ch)."\n");
        }

        return $result;
    }

    protected function send_soap_request() {

        $options = array('trace' => 1);
        if (!empty($this->data->soaplogin)) {
            $options['login'] = $this->data->soaplogin;
            $options['password'] = $this->data->soappassword;
        }
        $client = new SoapClient($this->data->wsdlurl, $options);

        $soapparams = $this->data->soapparams;
        if (!empty($this->data->sendcontext)) {
            $this->add_context($soapparams);
        }

        $argsarr = array();
        if ($argspairs = explode('&', $soapparams)) {
            foreach ($soappairs as $pair) {
                $parts = explode('=', $pair);
                $key = array_shift($pairs);
                $value = implode('=', $parts);
                $argsarr[$key] = $value;
            }
        }

        $options = array('exceptions' => true);
        try {
            $result = $client->__soapCall($this->config->soapfunction, $argsarr, $options);
        } catch (Exception $soape) {
            throw new Exception("SOAP Call Error : ".$soape->getMessage()."\n");
        }

        return $result;
    }

    /**
     * Adds dynamic dsplay time context info for the remote
     * content provider.
     * @param stringref $url a url to complete with context.
     */
    protected function add_context(&$url) {
        global $COURSE, $USER;

        $group = groups_get_course_group($COURSE);

        $query = 'courseid='.$COURSE->id.'&userid='.$USER->id.'&coursecategoryid='.$COURSE->category;
        if ($group) {
            $query .= '&groupid='.$group;
        }

        if ($this->config->sendcontext == self::CONTEXT_SEND_PLAIN) {
            if (strpos($url, '?') !== false) {
                $url .= '&'.$query;
            } else {
                $url .= '?'.$query;
            }
        } else {
            // Ask for crypto container
            $cryptedquery = $this->crypt($query);
            if (strpos($url, '?') !== false) {
                $url .= '&moodlecontext='.urlencode($cryptedquery);
            } else {
                $url .= '?moodlecontext='.urlencode($cryptedquery);
            }
        }
    }

}

