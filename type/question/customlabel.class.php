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

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
 *
 *
 */

class customlabel_type_question extends customlabel_type {

    function __construct($data) {
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
    }

    function preprocess_data() {
        global $CFG, $OUTPUT;

        $customid = @$CFG->custom_unique_id + 1;

        $minusurl = $OUTPUT->pix_url('minus', 'customlabel');
        $plusurl = $OUTPUT->pix_url('plus', 'customlabel');
        $this->data->initialcontrolimage = ($this->data->initiallyvisible) ? $minusurl : $plusurl;
        $this->data->hintinitialcontrolimage = ($this->data->hintinitiallyvisible) ? $minusurl : $plusurl;
        $this->data->wwwroot = $CFG->wwwroot;
        $this->data->customid = $customid;
        set_config('custom_unique_id', $customid);
    }
}

