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
 * Main type implementation
 *
 * @package    customlabeltype_worktodo
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');
require_once($CFG->dirroot.'/mod/customlabel/type/worktodo/locallib.php');

/**
 * Type implementation class.
 */
class customlabel_type_worktodo extends customlabel_type {

    /**
     * Constructor
     */
    public function __construct($data) {
        global $DB;

        parent::__construct($data);
        $this->type = 'worktodo';
        $this->fields = [];
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

        if ($fieldid = $DB->get_field('customlabel_mtd_type', 'id', ['code' => 'WORKTYPE'])) {

            $field = new StdClass;
            $field->name = 'showworktypefield';
            $field->type = 'choiceyesno';
            $field->advanced = true;
            $field->default = true;
            $this->fields['showworktypefield'] = $field;

            $field = new StdClass;
            $field->name = 'worktypefield';
            $field->type = 'vdatasource';
            $field->source = 'dbfieldkeyed';
            $field->table = 'customlabel_mtd_value';
            $field->field = 'value';
            $field->key = 'code';
            $field->select = " typeid = $fieldid ";
            $field->advanced = true;
            $this->fields['worktypefield'] = $field;
        }

        if ($fieldid = $DB->get_field('customlabel_mtd_type', 'id', ['code' => 'WORKEFFORT'])) {

            $field = new StdClass;
            $field->name = 'showworkeffortfield';
            $field->type = 'choiceyesno';
            $field->advanced = true;
            $field->default = true;
            $this->fields['showworkeffortfield'] = $field;

            $field = new StdClass;
            $field->name = 'workeffortfield';
            $field->type = 'vdatasource';
            $field->source = 'dbfieldkeyed';
            $field->table = 'customlabel_mtd_value';
            $field->field = 'value';
            $field->key = 'code';
            $field->select = " typeid = $fieldid ";
            $field->advanced = true;
            $this->fields['workeffortfield'] = $field;
        }

        if ($fieldid = $DB->get_field('customlabel_mtd_type', 'id', ['code' => 'WORKMODE'])) {

            $field = new StdClass;
            $field->name = 'showworkmodefield';
            $field->type = 'choiceyesno';
            $field->advanced = true;
            $field->default = true;
            $this->fields['showworkmodefield'] = $field;

            $field = new StdClass;
            $field->name = 'workmodefield';
            $field->type = 'vdatasource';
            $field->source = 'dbfieldkeyed';
            $field->table = 'customlabel_mtd_value';
            $field->field = 'value';
            $field->key = 'code';
            $field->select = " typeid = $fieldid ";
            $field->advanced = true;
            $this->fields['workmodefield'] = $field;
        }
    }

    /**
     * Post process data after loading type.
     */
    public function postprocess_data($course = null) {
        global $OUTPUT, $DB;

        $this->data->hasmode = $DB->get_field('customlabel_mtd_type', 'id', ['code' => 'WORKMODE']);
        $this->data->haseffort = $DB->get_field('customlabel_mtd_type', 'id', ['code' => 'WORKEFFORT']);
        $this->data->hastype = $DB->get_field('customlabel_mtd_type', 'id', ['code' => 'WORKTYPE']);

        $this->data->clock = $OUTPUT->image_url('clock', 'customlabeltype_worktodo')->out();

        if (is_array($this->data->worktypefield ?? false)) {
            $this->data->worktypefield = implode(', ', $this->data->worktypefield);
        }

        if (empty($this->data->worktypefield)) {
            $this->data->hastype = false;
        }

        if (is_array($this->data->workeffortfield ?? false)) {
            $this->data->workeffortfield = implode(', ', $this->data->workeffortfield);
        }

        if (empty($this->data->workeffortfield)) {
            $this->data->haseffort = false;
        }

        if (is_array($this->data->workmodefield ?? false)) {
            $this->data->workmodefield = implode(', ', $this->data->workmodefield);
        }

        if (empty($this->data->workmodefield)) {
            $this->data->hasmode = false;
        }
    }
}
