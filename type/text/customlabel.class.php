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
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 *
 *
 */

class customlabel_type_text extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'text';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'textcontent';
        $field->type = 'editor';
        $field->itemid = 0;
        $field->lines = 20;
        $field->default = '';
        $this->fields['textcontent'] = $field;

        $field = new StdClass;
        $field->name = 'readmorecontent';
        $field->type = 'editor';
        $field->itemid = 1;
        $field->lines = 20;
        $field->default = '';
        $this->fields['readmorecontent'] = $field;

        $field = new StdClass;
        $field->name = 'initiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 1;
        $this->fields['initiallyvisible'] = $field;
    }

    public function preprocess_data() {
        global $CFG;

        $this->data->label1 = get_string('readless', 'customlabeltype_text');
        $this->data->label2 = get_string('readmore', 'customlabeltype_text');

        if (!empty($this->data->initiallyvisible)) {
            $this->data->initialstring = get_string('readless', 'customlabeltype_text');
            $this->data->initialclass = '';
            $this->data->initialclass = '';
        } else {
            $this->data->initialstring = get_string('readmore', 'customlabeltype_text');
            $this->data->initialclass = 'hidden';
        }

        $context = context_module::instance($this->cmid);
        // Weird fix.
        $this->data->textcontent = preg_replace('/@@PLUGINFILE\:\:\d+@@/', '@@PLUGINFILE@@', $this->data->textcontent);
        $this->data->textcontent = file_rewrite_pluginfile_urls($this->data->textcontent,
                'pluginfile.php', $context->id, 'mod_customlabel', 'contentfiles', $this->fields['textcontent']->itemid);

        // Weird fix.
        $this->data->readmorecontent = preg_replace('/@@PLUGINFILE\:\:\d+@@/', '@@PLUGINFILE@@', $this->data->readmorecontent);
        $this->data->readmorecontent = file_rewrite_pluginfile_urls($this->data->readmorecontent,
                'pluginfile.php', $context->id, 'mod_customlabel', 'contentfiles', $this->fields['readmorecontent']->itemid);

        $this->data->customid = $this->cmid;
    }
}
