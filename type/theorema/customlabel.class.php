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
 * Main subtype implementation
 *
 * @package    customlabeltype_theorema
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 * Main implementation class
 */
class customlabel_type_theorema extends customlabel_type {

    /**
     * Constructor
     * @param object $data
     */
    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'theorema';
        $this->fields = [];

        $field = new StdClass();
        $field->name = 'theoremaname';
        $field->type = 'textfield';
        $field->size = 255;
        $this->fields['theoremaname'] = $field;

        $field = new StdClass();
        $field->name = 'theorema';
        $field->type = 'editor';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['theorema'] = $field;

        if (!empty($data->corollarynum)) {
            $subdefsnum = $data->corollarynum;
        } else {
            // Second chance.
            if (!empty($this->data->corollarynum)) {
                $subdefsnum = $this->data->corollarynum;
            } else {
                $subdefsnum = 0;
            }
        }

        $field = new StdClass();
        $field->name = 'corollarynum';
        $field->type = 'textfield';
        $field->size = 4;
        $field->default = 0;
        $this->fields['corollarynum'] = $field;

        $i = 0;
        for ($i = 0; $i < $subdefsnum; $i++) {
            $field = new StdClass();
            $field->name = 'corollary'.$i;
            $field->type = 'editor';
            $field->itemid = $i + 1;
            $field->size = 60;
            $this->fields['corollary'.$i] = $field;
        }

        $field = new StdClass();
        $field->name = 'showdemonstration';
        $field->type = 'choiceyesno';
        $this->fields['showdemonstration'] = $field;

        $field = new StdClass();
        $field->name = 'demonstration';
        $field->type = 'editor';
        $field->itemid = $i + 1;
        $field->rows = 20;
        $this->fields['demonstration'] = $field;

        $field = new StdClass();
        $field->name = 'demoinitiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 0;
        $this->fields['demoinitiallyvisible'] = $field;

        $field = new StdClass();
        $field->name = 'showdemonstrationon';
        $field->type = 'datetime';
        $field->default = time() + DAYSECS * 7;
        $this->fields['showdemonstrationon'] = $field;
    }

    /**
     * Preprocesses template before getting options and additional inputs
     * from fields.
     */
    public function preprocess_data() {
        global $OUTPUT, $COURSE;

        $minusurl = $OUTPUT->image_url('minus', 'customlabel');
        $plusurl = $OUTPUT->image_url('plus', 'customlabel');
        $this->data->initialcontrolimageurl = ($this->data->demoinitiallyvisible ?? false) ? $minusurl : $plusurl;
        $this->data->initialclass = ($this->data->demoinitiallyvisible ?? false) ? '' : 'hidden';

        $this->data->corollarylist = "<ul class=\"customlabel-corollaries theorema\">\n";
        for ($i = 0; $i < $this->data->corollarynum; $i++) {
            $key = 'corollary'.$i;
            $title = get_string('corollary', 'customlabeltype_theorema').' '.($i + 1).' ';
            $this->data->corollarylist .= (isset($this->data->$key)) ? "<li><i>{$title} :</i> {$this->data->$key}</li>\n" : '';
        }
        $this->data->corollarylist .= "</ul>\n";
        $this->data->customid = $this->cmid;
        if (!empty($this->data->showdemonstrationon->enabled)) {
            $qdate = mktime($this->data->showdemonstrationon->hour ?? 0,
                            $this->data->showdemonstrationon->minute ?? 0,
                            0,
                            $this->data->showdemonstrationon->month ?? 0,
                            $this->data->showdemonstrationon->day ?? 0,
                            $this->data->showdemonstrationon->year ?? 0);
            if ($qdate <= time()) {
                $this->data->canshow = true;
            }
            $this->data->opentime = userdate($qdate);
        } else {
            // If not timed, let can show.
            $this->data->canshow = true;
        }

        $context = context_course::instance($COURSE->id);
        if (has_capability('moodle/course:manageactivities', $context)) {
            $this->data->hascap = true;
        }

        $this->data->hasname = !empty($this->data->theoremaname);
    }
}
