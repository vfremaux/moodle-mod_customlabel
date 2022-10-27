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
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');
require_once($CFG->dirroot.'/mod/customlabel/type/localdokuwikicontent/locallib.php');

class customlabel_type_localdokuwikicontent extends customlabel_type {

    private $config;

    private $lang;

    public function __construct($data) {
        global $CFG, $PAGE, $OUTPUT;

        $this->config = get_config('customlabeltype_localdokuwikicontent');

        parent::__construct($data);
        $this->type = 'localdokuwikicontent';
        $this->fields = array();

        $field = new StdClass();
        $field->name = 'local';
        $field->type = 'choiceyesno';
        $field->default = $this->config->defaultlocal;
        $this->fields['local'] = $field;

        $field = new StdClass();
        $field->name = 'contentpage';
        $field->type = 'textfield';
        $field->size = 20;
        $this->fields['contentpage'] = $field;

        $field = new StdClass();
        $field->name = 'accesstoken';
        $field->type = 'textfield';
        $field->default = $this->config->defaultlocal;
        $field->size = 20;
        $this->fields['accesstoken'] = $field;

        $field = new StdClass();
        $field->name = 'remotehost';
        $field->type = 'textfield';
        $field->default = $this->config->defaultremotehost;
        $field->size = 40;
        $this->fields['remotehost'] = $field;

        $field = new StdClass();
        $field->name = 'removelinks';
        $field->type = 'choiceyesno';
        $field->default = true;
        $this->fields['removelinks'] = $field;

        $field = new StdClass();
        $field->name = 'hideelements';
        $field->type = 'textfield';
        $field->default = 'h1,h2';
        $this->fields['hideelements'] = $field;

        $field = new StdClass();
        $field->name = 'localcss';
        $field->type = 'textarea';
        $field->itemid = 1;
        $field->default = '';
        $this->fields['localcss'] = $field;

        if (!$this->check_wiki_availability()) {
            $this->fields['contentpage']->type = 'hidden';
            $this->fields['removelinks']->type = 'hidden';
            $this->fields['hideelements']->type = 'hidden';
            $this->fields['localcss']->type = 'hidden';

            $field = new StdClass();
            $field->name = 'advice';
            $field->type = 'static';
            $field->itemid = 1;
            $field->default = get_string('notconfigured', 'customlabeltype_localdokuwikicontent');
            $this->fields['advice'] = $field;
        }
    }

    /**
     * If exists, this method can process local alternative values for
     * realizing the template, after all standard translations have been performed.
     * Type information structure and application context dependant.
     */
    public function postprocess_data($course = null) {
        global $CFG, $OUTPUT;

        $this->data->wikicontent = '';
        $lang = current_language();

        if (!empty($this->data->contentpage)) {

            if (!empty($this->data->local)) {
                debug_trace("Getting local ");
                $content = customlabeltype_localwikicontent_get_page_content($this->data->contentpage, $lang);

                $config = get_config('customlabeltype_localdokuwikicontent');

                $webroot = $config->webroot;
                $webroot = str_replace('{lang}', $lang, $webroot);
            } else {
                $content = '';
                $webroot = '';
                debug_trace("Getting remote");
                if ($remote = $this->get_remote_page_content($this->data->contentpage, $lang)) {
                    if (!empty($remote->content)) {
                        $content = $remote->content;
                        $webroot = $remote->webroot;
                    }
                } else {
                    $this->data->wikicontent = $OUTPUT->notification(get_string('contentnotreachable', 'customlabeltype_localdokuwikicontent'));
                    return;
                }
            }

            // Local post processing.
            if (!empty($this->data->localcss)) {
                $localcss = str_replace('</style>', '', $this->data->localcss); // Anti injection.
                $content = "<style>".$localcss.'</style>'.$content;
            }

            if (!empty($this->data->removelinks)) {
                $content = preg_replace('/<a\s[^>]*?>(.*?)<\/a>/', '\\1', $content);
            }

            // Remove some html elements from the content.
            if (!empty($this->data->hideelements)) {
                $elements = explode(',', $this->data->hideelements);
                foreach ($elements as $elm) {
                    $content = preg_replace('/<'.$elm.'\b[^>]*?>(.*?)<\/'.$elm.'>/i', '', $content);
                }
            }

            if (is_dir($CFG->dirroot.'/local/vflibs')) {
                // Use the vflib protocol if available and configured.
                include_once($CFG->dirroot.'/local/vflibs/vfdoclib.php');
                $ticket = local_vflibs_doc_make_ticket();
                if ($ticket) {
                    $content = preg_replace('~(href="'.$webroot.'.*?)"~', "\\1".'&cryptoken='.$ticket.'"', $content);
                    $this->data->accesstoken = false; // Disable following case.
                }
            }

            if (!empty($this->data->accesstoken)) {
                $content = preg_replace('~(href="'.$webroot.'.*?)"~', "\\1".'&readtoken='.$this->data->accesstoken.'"', $content);
            }

            $this->data->wikicontent = $content;
        }
    }

    /**
     * Checked the existance of a wiki in the expected language.
     * @return true if exists.
     */
    protected function check_wiki_availability() {

        $this->lang = current_language();

        $this->basedir = str_replace('{lang}', $this->lang, $this->config->basedir);

        if (is_dir($this->basedir)) {
            return true;
        }

        return false;
    }

    protected function get_remote_page_content($page, $lang) {

        $config = get_config('customlabeltype_localdokuwikicontent');

        $accesstoken = '';
        if (!empty($this->data->accesstoken)) {
            $accesstoken = $this->data->accesstoken;
        } else {
            if (!empty($config->defaultremotetoken)) {
                $accesstoken = $config->defaultremotetoken;
            }
        }

        $remotehost = '';
        if (!empty($this->data->remotehost)) {
            $remotehost = $this->data->remotehost;
        } else {
            if (!empty($config->defaultremotehost)) {
                $remotehost = $config->defaultremotehost;
            }
        }

        $params = array('wstoken' => $accesstoken,
                        'wsfunction' => 'customlabeltype_localdokuwikicontent_get_page',
                        'moodlewsrestformat' => 'json',
                        'page' => $page,
                        'lang' => $lang);

        $serviceurl = $remotehost.'/webservice/rest/server.php';
        return $this->send($serviceurl, $params);
    }

    protected function send($serviceurl, $params) {
        global $OUTPUT, $CFG;

        $ch = curl_init($serviceurl);

        if ($CFG->debug == DEBUG_DEVELOPER) {
            foreach ($params as $key => $value) {
                $paramstrs[] = "$key=".urlencode($value);
            }
            $querystring = implode('&', $paramstrs);
            debug_trace("Sending localdokuwikicall with\n$serviceurl?".$querystring);
        }

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $error = '';
        if (!$result = curl_exec($ch)) {
            if ($CFG->debug == DEBUG_DEVELOPER) {
           		$error = "CURL Error : ".curl_errno($ch).' '.curl_error($ch)."\n";
           	}
        }

        if (preg_match('/EXCEPTION/', $result)) {
            echo "Remote Exception : $result";
            return;
        }

        $result = json_decode($result);
        if (is_object($result) && isset($result->content)) {
            return $result;
        }

        $errormsg = get_string('contentnotreachable', 'customlabeltype_localdokuwikicontent');
        if (!empty($error)) {
            $errormsg .= '<br/>'.$error;
        }
        return $OUTPUT->notification($errormsg);
    }
}