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

class customlabel_type_theorema extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'theorema';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'theorema';
        $field->type = 'editor';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['theorema'] = $field;

        if (!isset($data->corollarynum)) {
            // Second chance, get it from stored data.
            $storeddata = json_decode(base64_decode(@$this->data->content));
            $subdefsnum = (!empty($storeddata->corollarynum)) ? $storeddata->corollarynum : 0;
        } else {
            $subdefsnum = $data->corollarynum;
        }

        $field = new StdClass;
        $field->name = 'corollarynum';
        $field->type = 'textfield';
        $field->size = 4;
        $field->default = 0;
        $this->fields['corollarynum'] = $field;

        $i = 0;
        for ($i = 0; $i < $subdefsnum; $i++) {
            $field = new StdClass;
            $field->name = 'corollary'.$i;
            $field->type = 'editor';
            $field->itemid = $i + 1;
            $field->size = 60;
            $this->fields['corollary'.$i] = $field;
        }

        $field = new StdClass;
        $field->name = 'showdemonstration';
        $field->type = 'choiceyesno';
        $this->fields['showdemonstration'] = $field;

        $field = new StdClass;
        $field->name = 'demonstration';
        $field->type = 'editor';
        $field->itemid = $i + 1;
        $field->rows = 20;
        $this->fields['demonstration'] = $field;

        $field = new StdClass;
        $field->name = 'demoinitiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 0;
        $this->fields['demoinitiallyvisible'] = $field;

        $field = new StdClass;
        $field->name = 'showdemonstrationon';
        $field->type = 'datetime';
        $field->default = time() + DAYSECS * 7;
        $this->fields['showdemonstrationon'] = $field;
    }

    public function preprocess_data() {
        global $OUTPUT, $COURSE;

        $minusurl = $OUTPUT->image_url('minus', 'customlabel');
        $plusurl = $OUTPUT->image_url('plus', 'customlabel');
        $this->data->initialcontrolimageurl = ($this->data->demoinitiallyvisible) ? $minusurl : $plusurl;
        $this->data->corollarylist = "<ul class=\"customlabel-corollaries theorema\">\n";
        for ($i = 0; $i < $this->data->corollarynum; $i++) {
            $key = 'corollary'.$i;
            $title = get_string('corollary', 'customlabeltype_theorema').' '.($i + 1).' ';
            $this->data->corollarylist .= (isset($this->data->$key)) ? "<li><i>{$title} :</i> {$this->data->$key}</li>\n" : '';
        }
        $this->data->corollarylist .= "</ul>\n";
        $this->data->initialclass = ($this->data->demoinitiallyvisible) ? '' : 'hidden';
        $this->data->customid = $this->cmid;
        if ($this->data->showdemonstrationon->enabled) {
            $qdate = mktime($this->data->showdemonstrationon->hour,
                            $this->data->showdemonstrationon->minute,
                            0,
                            $this->data->showdemonstrationon->month,
                            $this->data->showdemonstrationon->day,
                            $this->data->showdemonstrationon->year);
            if ($qdate < time()) {
                $this->data->canshow = true;
            }
            $this->data->opentime = userdate($qdate);
        }

        $context = context_course::instance($COURSE->id);
        if (has_capability('moodle/course:manageactivities', $context)) {
            $this->data->hascap = true;
        }
    }
}
