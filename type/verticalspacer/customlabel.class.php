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
 * Main type implementation.
 *
 * @package customlabeltype_verticalspacer
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 * the vertical spacer allows making vertical blank gaps in a page, adjustable
 * so aligning vertically columns content.
 */
class customlabel_type_verticalspacer extends customlabel_type {

    /**
     * Constructor
     * @param object $data
     */
    public function __construct($data) {
        global $USER;

        parent::__construct($data);
        $this->type = 'verticalspacer';
        $this->fields = [];
        $this->hasamd = true;

        $field = new StdClass();
        $field->name = 'spacing';
        $field->type = 'textfield';
        $field->default = 100;
        $this->fields['spacing'] = $field;

    }

    /**
     * Preprocesses template before getting options and additional inputs
     * from fields.
     */
    public function preprocess_data() {
        global $CFG, $OUTPUT, $COURSE, $PAGE;

        // Some kind of global static.
        $customid = ($CFG->custom_unique_id ?? 0) + 1;

        $this->data->courseid = $COURSE->id;
        $this->data->cid = $this->data->instance ?? 0;
        $this->data->customid = $customid;
        $this->data->wwwroot = $CFG->wwwroot;
        $this->data->editing = $PAGE->user_is_editing();
        $this->data->dragimageurl = $OUTPUT->image_url('dragpaddle', 'customlabeltype_verticalspacer')->out();
    }
}

