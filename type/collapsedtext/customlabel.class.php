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

/**
 *
 *
 */

class customlabel_type_collapsedtext extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'collapsedtext';
        $this->fields = array();

        // Introduces an architecture change with simplified and more direct component rendering.
        $this->hasamd = true;

        $chapternum = @$this->data->chapternum;
        if (empty($chapternum)) {
            $chapternum = 3;
            if (!isset($this->data)) {
                $this->data = new StdClass;
            }
            $this->data->chapternum = 3;
        }

        $field = new StdClass;
        $field->name = 'algorithm';
        $field->type = 'list';
        $field->options = array('toggle', 'accordion');
        $field->default = 'toggle';
        $this->fields['algorithm'] = $field;

        $field = new StdClass;
        $field->name = 'initialstate';
        $field->type = 'list';
        $field->options = array('open', 'firstopen', 'collapsed');
        $field->default = 'collapsed';
        $this->fields['initialstate'] = $field;

        $field = new StdClass;
        $field->name = 'titlelevel';
        $field->type = 'list';
        $field->straightoptions = true;
        $field->options = array('h2', 'h3', 'h4', 'h5', 'h6');
        $field->default = 'h4';
        $this->fields['titlelevel'] = $field;

        $field = new StdClass;
        $field->name = 'chapternum';
        $field->type = 'list';
        $field->straightoptions = true;
        $field->options = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 15, 20, 25, 30); // @see lang files to adjust string generation.
        $field->default = 3;
        $this->fields['chapternum'] = $field;

        for ($i = 1; $i <= $chapternum; $i++) {

            $field = new StdClass;
            $field->name = 'chaptercaption'.$i;
            $field->type = 'textfield';
            $field->size = 120;
            $this->fields['chaptercaption'.$i] = $field;

            $field = new StdClass;
            $field->name = 'chaptertext'.$i;
            $field->type = 'editor';
            $field->itemid = $i;
            $this->fields['chaptertext'.$i] = $field;
        }
    }

    /**
     * Process internal data before options and external source resolution.
     */
    public function preprocess_data() {
        global $OUTPUT, $CFG;

        $iconstr = get_string('toggle', 'customlabeltype_collapsedtext');
        if (!isset($this->data->algorithm)) {
            $this->data->algorithm = 'toggle';
        }

        $this->data->hasmorethanone = false;
        if ($this->data->algorithm == 'toggle') {
            $this->data->algorithmclass = 'is-toggle';
            $this->data->istoggle = true;
            if ($this->data->chapternum > 1) {
                $this->data->hasmorethanone = true;
            }
        }
        $this->data->openallstr = get_string('openall', 'customlabeltype_collapsedtext');
        $this->data->closeallstr = get_string('closeall', 'customlabeltype_collapsedtext');
        $iconstr = get_string('open', 'customlabeltype_collapsedtext');
        $this->data->toggleicon = $OUTPUT->pix_icon('open', $iconstr, 'customlabeltype_collapsedtext');
        $this->data->openallicon = $OUTPUT->pix_icon('expandall', $this->data->openallstr, 'customlabeltype_collapsedtext');
        $this->data->closeallicon = $OUTPUT->pix_icon('collapseall', $this->data->closeallstr, 'customlabeltype_collapsedtext');

        // Let static config force the title level
        if (empty($this->data->titlelevel)) {
            if (!empty($CFG->forced_plugin_settings['customlabeltype_collapsedtext']['defaulttitlelevel'])) {
                $this->data->titlelevel = $CFG->forced_plugin_settings['customlabeltype_collapsedtext']['defaulttitlelevel'];
            } else {
                $this->data->titlelevel = 'h4';
            }
        }

        if (empty($this->cmid)) {
            debugging('here');
        }

        $this->data->id = $this->cmid;
        for ($i = 1; $i <= $this->data->chapternum; $i++) {
            $chaptertpl = new StdClass;
            $chaptertpl->ix = $i;
            $key = 'chaptertext'.$i;
            $editorkey = 'chaptertext'.$i.'_editor';
            if (isset($this->data->$key)) {
                $chaptertpl->content = $this->data->$key;
            } else {
                if (isset($this->data->$editorkey)) {
                    // This can happen when the number of items is raising up.
                    $editor = $this->data->$editorkey;
                    $chaptertpl->content = format_text($editor->text, $editor->format);
                }
            }
            $key = 'chaptercaption'.$i;
            $chaptertpl->caption = format_string(@$this->data->$key);

            switch ($this->data->initialstate ?? 'open') {
                case 'open':
                    $chaptertpl->initialstate = '';
                    break;
                case 'firstopen':
                    if ($i == 1) {
                        $chaptertpl->initialstate = '';
                    } else {
                        $chaptertpl->initialstate = 'collapsed';
                        $chaptertpl->toggleicon = $OUTPUT->pix_icon('closed', $iconstr, 'customlabeltype_collapsedtext');
                    }
                    break;
                case 'collapsed':
                    $chaptertpl->initialstate = 'collapsed';
                    $chaptertpl->toggleicon = $OUTPUT->pix_icon('closed', $iconstr, 'customlabeltype_collapsedtext');
                    break;
            }

            $this->data->chapter[] = $chaptertpl;

        }
    }

    /**
     * Called from the module add_completion_rules @see mod/customlabel/lib.php
     * Add customized per type completion rules (up to 3)
     * @param object $mform the completion form
     */
    static public function add_completion_rules($mform) {

        $mform->addElement('checkbox', 'completion1enabled', '', get_string('completion1', 'customlabeltype_collapsedtext'));

        return array('completion1enabled');
    }

    /**
     * Provides the complete value to match for each used completion.
     * @param int $completionix the completion index from 1 to 3.
     */
    public function complete_value($completionix) {

        $return = false;

        switch ($completionix) {
            case 1 : {
                $return = pow(2, $this->get_data('chapternum')) - 1;
                break;
            }
            case 2 : {
                break;
            }
            case 3 : {
                break;
            }
        }

        return $return;
    }
}
