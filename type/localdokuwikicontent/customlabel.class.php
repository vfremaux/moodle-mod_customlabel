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

require_once ($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

class customlabel_type_localdokuwikicontent extends customlabel_type {

    public function __construct($data) {
        global $CFG, $PAGE, $OUTPUT;

        $config = get_config('customlabeltype_localdokuwikicontent');

        parent::__construct($data);
        $this->type = 'localdokuwikicontent';
        $this->fields = array();

        if (!empty($config->basedir) && is_dir($config->basedir)) {
            $field = new StdClass();
            $field->name = 'contentpage';
            $field->type = 'textfield';
            $field->size = 20;
            $this->fields['contentpage'] = $field;
    
            $field = new StdClass();
            $field->name = 'removelinks';
            $field->type = 'choiceyesno';
            $field->default = true;
            $this->fields['removelinks'] = $field;
    
            $field = new StdClass();
            $field->name = 'localcss';
            $field->type = 'textarea';
            $field->itemid = 1;
            $field->default = '';
            $this->fields['localcss'] = $field;
        }
    }

    /**
     * If exists, this method can process local alternative values for
     * realizing the template, after all standard translations have been performed.
     * Type information structure and application context dependant.
     */
    public function postprocess_data($course = null) {

        $config = get_config('customlabeltype_localdokuwikicontent');

        $path = core_text::strtolower(str_replace(':', '/', $this->data->contentpage));
        $contentfile = $config->basedir.'/data/pages/'.$path.'.txt';
        $contentfile = str_replace('//', '/', $contentfile);

        $cmd = 'php '.escapeshellarg($config->basedir.'/bin/render.php').' < '.escapeshellarg($contentfile);
        $result = exec($cmd, $output, $returnvar);

        $content = '';
        if (!empty($this->data->localcss)) {
            $localcss = str_replace('</style>', '', $this->data->localcss); // Anti injection.
            $content = "<style>".$localcss.'</style>';
        }

        $content .= implode($output);

        if (!empty($this->data->removelinks)) {
            $content = preg_replace('/<a\s.*?<\/a>/', '', $content);
        }

        $this->data->content = $content;
    }
}