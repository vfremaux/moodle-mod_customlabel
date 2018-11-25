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

class customlabel_type_question extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'question';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'questiontext';
        $field->type = 'editor';
        $field->rows = 20;
        $field->itemid = 0;
        $this->fields['questiontext'] = $field;

        $field = new StdClass;
        $field->name = 'hint';
        $field->type = 'editor';
        $field->rows = 20;
        $field->itemid = 1;
        $this->fields['hint'] = $field;

        $field = new StdClass;
        $field->name = 'hintinitiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 0;
        $this->fields['hintinitiallyvisible'] = $field;

        $field = new StdClass;
        $field->name = 'answertext';
        $field->type = 'editor';
        $field->rows = 20;
        $field->itemid = 2;
        $this->fields['answertext'] = $field;

        $field = new StdClass;
        $field->name = 'initiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 0;
        $this->fields['initiallyvisible'] = $field;

        $field = new StdClass;
        $field->name = 'showansweron';
        $field->type = 'datetime';
        $field->default = time() + DAYSECS * 7;
        $this->fields['showansweron'] = $field;
    }

    public function preprocess_data() {
        global $OUTPUT, $COURSE;

        $minusurl = $OUTPUT->image_url('minus', 'customlabel');
        $plusurl = $OUTPUT->image_url('plus', 'customlabel');
        $this->data->initialcontrolimageurl = ($this->data->initiallyvisible) ? $minusurl : $plusurl;
        $this->data->hintinitialcontrolimageurl = ($this->data->hintinitiallyvisible) ? $minusurl : $plusurl;
        $this->data->initialclass = ($this->data->initiallyvisible) ? '' : 'hidden';
        $this->data->hintinitialclass = ($this->data->hintinitiallyvisible) ? '' : 'hidden';
        $this->data->customid = $this->cmid;
        if (!empty($this->data->showansweron) && $this->data->showansweron->enabled) {
            $qdate = mktime($this->data->showansweron->hour,
                            $this->data->showansweron->minute,
                            0,
                            $this->data->showansweron->month,
                            $this->data->showansweron->day,
                            $this->data->showansweron->year);
            if ($qdate < time()) {
                $this->data->canshow = true;
            }
            $this->data->opentime = userdate($qdate);
        } else {
            $this->data->canshow = true;
        }

        $context = context_course::instance($COURSE->id);
        if (has_capability('moodle/course:manageactivities', $context)) {
            $this->data->hascap = true;
        }
    }
}
