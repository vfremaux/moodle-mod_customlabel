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
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 */

require_once($CFG->dirroot.'/mod/customlabel/forms/ClassificationModelForm.php');

$mform = new ModelForm();

if (!$mform->is_cancelled()) {

    if ($data = $mform->get_data()) {
        set_config('classification_type_table', $data->classification_type_table);
        set_config('classification_value_table', $data->classification_value_table);
        set_config('classification_value_type_key', $data->classification_value_type_key);
        set_config('classification_constraint_table', $data->classification_constraint_table);
        set_config('course_metadata_table', $data->course_metadata_table);
        set_config('course_metadata_value_key', $data->course_metadata_value_key);
        set_config('course_metadata_course_key', $data->course_metadata_course_key);
    }
}

// Print table.

echo $deferredheader;

echo $OUTPUT->heading(get_string('classificationmodel', 'customlabel'));

$data = new StdClass;
$data->view = $view;
$data->classification_type_table = 'customlabel_mtd_type';
$data->classification_value_table = 'customlabel_mtd_value';
$data->classification_value_type_key = 'typeid';
$data->classification_constraint_table = 'customlabel_mtd_constraint';
$data->course_metadata_table = 'customlabel_course_metadata';
$data->course_metadata_value_key = 'valueid';
$data->course_metadata_course_key = 'courseid';

$mform->set_data($data);
$mform->display();
