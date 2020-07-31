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
require_once($CFG->dirroot.'/mod/customlabel/type/worktodo/locallib.php');

/**
 *
 *
 */
class customlabel_type_worktodo extends customlabel_type {

    public function __construct($data) {
        global $DB;

        parent::__construct($data);
        $this->type = 'worktodo';
        $this->fields = array();
        $this->allowedpageformats = 'page';

        $field = new StdClass;
        $field->name = 'worktodo';
        $field->type = 'editor';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['worktodo'] = $field;

        $field = new StdClass;
        $field->name = 'estimatedworktime';
        $field->type = 'textfield';
        $field->size = 10;
        $this->fields['estimatedworktime'] = $field;

        if ($fieldid = $DB->get_field('customlabel_mtd_type', 'id', array('code' => 'WORKTYPE'))) {

            $field = new StdClass;
            $field->name = 'worktypefield';
            $field->type = 'vdatasource';
            $field->source = 'dbfieldkeyed';
            $field->table = 'customlabel_mtd_value';
            $field->field = 'value';
            $field->key = 'code';
            $field->select = " typeid = $fieldid ";
            $this->fields['worktypefield'] = $field;
        }

        if ($fieldid = $DB->get_field('customlabel_mtd_type', 'id', array('code' => 'WORKEFFORT'))) {

            $field = new StdClass;
            $field->name = 'workeffortfield';
            $field->type = 'vdatasource';
            $field->source = 'dbfieldkeyed';
            $field->table = 'customlabel_mtd_value';
            $field->field = 'value';
            $field->key = 'code';
            $field->select = " typeid = $fieldid ";
            $this->fields['workeffortfield'] = $field;
        }

        if ($fieldid = $DB->get_field('customlabel_mtd_type', 'id', array('code' => 'WORKMODE'))) {

            $field = new StdClass;
            $field->name = 'workmodefield';
            $field->type = 'vdatasource';
            $field->source = 'dbfieldkeyed';
            $field->table = 'customlabel_mtd_value';
            $field->field = 'value';
            $field->key = 'code';
            $field->select = " typeid = $fieldid ";
            $this->fields['workmodefield'] = $field;
        }

        /*
         * An activity module of the course that will be linked to this work requirement for
         * completion signalling, grade, etc.
         */
         /*
        $field = new StdClass;
        $field->name = 'linktomodule';
        $field->type = 'vdatasource';
        $field->source = 'function';
        $field->function = 'customlabel_get_candidate_modules';
        $this->fields['linktomodule'] = $field;
        */
    }

    public function postprocess_data($course = null) {
        global $OUTPUT, $DB;

        $this->data->hasmode = $DB->get_field('customlabel_mtd_type', 'id', array('code' => 'WORKMODE'));
        $this->data->haseffort = $DB->get_field('customlabel_mtd_type', 'id', array('code' => 'WORKEFFORT'));
        $this->data->hastype = $DB->get_field('customlabel_mtd_type', 'id', array('code' => 'WORKTYPE'));

        $this->data->clock = $OUTPUT->image_url('clock', 'customlabeltype_worktodo')->out();

        if (is_array(@$this->data->worktypefield)) {
            $this->data->worktypefield = implode(', ', @$this->data->worktypefield);
        }

        if (empty($this->data->worktypefield)) {
            $this->data->hastype = false;
        }

        if (is_array(@$this->data->workeffortfield)) {
            $this->data->workeffortfield = implode(', ', @$this->data->workeffortfield);
        }

        if (empty($this->data->workeffortfield)) {
            $this->data->haseffort = false;
        }

        if (is_array(@$this->data->workmodefield)) {
            $this->data->workmodefield = implode(', ', @$this->data->workmodefield);
        }

        if (empty($this->data->workmodefield)) {
            $this->data->hasmode = false;
        }
    }
}
