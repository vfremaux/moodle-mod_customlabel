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
 * @package mod_customlabel
 * @category mod
 * @author Valery Fremaux
 * @date 02/12/2007
 *
 * A generic class for collecting all that is common to all elements
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 * the vertical spacer allows making vertical blank gaps in a page, adjustable
 * so aligning vertically columns content.
 *
 */
class customlabel_type_verticalspacer extends customlabel_type {

    public function __construct($data) {
        global $USER;

        parent::__construct($data);
        $this->type = 'verticalspacer';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'spacing';
        $field->type = 'textfield';
        $field->default = 100;
        $this->fields['spacing'] = $field;

    }

    /**
     * Prepares data for template
     */
    public function preprocess_data($course = null) {
        global $CFG, $OUTPUT, $COURSE, $PAGE;

        // Some kind of global static.
        $customid = @$CFG->custom_unique_id + 1;

        $this->data->courseid = $COURSE->id;
        $this->data->cid = 0 + @$this->data->instance;
        $this->data->customid = $customid;
        $this->data->wwwroot = $CFG->wwwroot;
        $this->data->editing = $PAGE->user_is_editing();
        $this->data->dragimageurl = $OUTPUT->image_url('dragpaddle', 'customlabeltype_verticalspacer')->out();
    }
}

