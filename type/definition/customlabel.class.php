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
 * @package    customlabeltype_definition
 *
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

class customlabel_type_definition extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'definition';
        $this->fields = [];

        $field = new StdClass;
        $field->name = 'definition';
        $field->type = 'editor';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['definition'] = $field;

        $field = new StdClass;
        $field->name = 'subdefsnum';
        $field->type = 'list';
        $field->options = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
        $field->straightoptions = true;
        $this->fields['subdefsnum'] = $field;

        if (!empty($this->data->subdefsnum)) {
            for ($i = 0; $i < $this->data->subdefsnum; $i++) {
                $field = new StdClass;
                $field->name = 'subdef'.$i;
                $field->type = 'editor';
                $field->itemid = $i + 1;
                $field->size = 60;
                $this->fields['subdef'.$i] = $field;
            }
        }
    }

    public function postprocess_data($course = null) {

        $this->data->hassubdefs = false;
        if ($this->data->subdefsnum) {
            for ($i = 0; $i < $this->data->subdefsnum; $i++) {
                $key = 'subdef'.$i;
                $subdeftpl = new StdClass;
                if (isset($this->data->$key)) {
                    $subdeftpl->subdef = $this->data->$key;
                    $this->data->subdefs[] = $subdeftpl;
                    $this->data->hassubdefs = true;
                }
            }
        }
    }
}
