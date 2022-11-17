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
 * this defines a set of fields. You just need defining fields and add them to the class,
 * then make an HTML template that uses <%%fieldname%%> calls, using style classing, and
 * finally add a customlabel.css within the same directory
 */
class customlabel_type_formule extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'formule';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'formulename';
        $field->type = 'textfield';
        $field->size = 255;
        $this->fields['formulename'] = $field;

        $field = new StdClass;
        $field->name = 'formuletext';
        $field->type = 'editor';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['formuletext'] = $field;

        $field = new StdClass;
        $field->name = 'showdevelopment';
        $field->type = 'choiceyesno';
        $this->fields['showdevelopment'] = $field;

        $field = new StdClass;
        $field->name = 'development';
        $field->type = 'editor';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['development'] = $field;

        $field = new StdClass;
        $field->name = 'developmentinitiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 0;
        $this->fields['developmentinitiallyvisible'] = $field;
    }

    public function preprocess_data() {
        global $OUTPUT, $COURSE;

        $minusurl = $OUTPUT->image_url('minus', 'customlabel');
        $plusurl = $OUTPUT->image_url('plus', 'customlabel');
        $this->data->initialcontrolimageurl = (@$this->data->developmentinitiallyvisible) ? $minusurl : $plusurl;
        $this->data->initialclass = (@$this->data->developmentinitiallyvisible) ? '' : 'hidden';
        $this->data->hasname = !empty($this->data->formulename);
    }

}
