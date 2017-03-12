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

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 * this defines a set of fields. You just need defining fields and add them to the class,
 * then make an HTML template that uses <%%fieldname%%> calls, using style classing, and
 * finally add a customlabel.css within the same directory
 */

class customlabel_type_NEWTYPE extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'NEWTYPE';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'smalltext';
        $field->type = 'textfield';
        $field->maxlength = 80;
        $this->fields['smalltext'] = $field;

        unset($field);
        $field->name = 'parag';
        $field->type = 'textarea';
        $field->itemid = 0;
        $field->rows = 5;
        $field->cols = 40;
        $this->fields['parag'] = $field;

        unset($field);
        $field->name = 'list';
        $field->type = 'list';
        $field->options = array('opt1', 'opt2'); // This can be changed to whatever any menu_list.
        $this->fields['list'] = $field;

        unset($field);
        $field->name = 'listmultiple[]';
        $field->type = 'list';
        $field->options = array('opt1', 'opt2'); // This can be changed to whatever any menu_list.
        $field->multiple = 1;
        $field->size = 5;
        $this->fields['listmultiple'] = $field;

        unset($field);
        $field->name = 'lockedfield';
        $field->type = 'textfield';
        $field->maxlength = 80;
        $field->fullaccess = 0;
        $field->default = get_string('lockedsample', 'customlabel');
        $this->fields['lockedfield'] = $field;
    }
}

