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
 *
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
require_once('../../config.php');

if (is_dir($CFG->dirroot.'/blocks/course_status')) {
    include_once($CFG->dirroot.'/blocks/course_status/xlib.php');
}

$value = optional_param('value', 0, PARAM_INT);
$type = optional_param('typeid', 0, PARAM_INT);
$params = ['value' => $value, 'typeid' => $type];
$url = new moodle_url('/mod/customlabel/adminmetadata.php', $params);

$context = context_system::instance();
$PAGE->set_url($url);
$PAGE->set_context($context);

require_login();
require_capability('moodle/site:config', $context);

if ($value == 0 && $type == 0) {
    echo "<br/>";
    echo $OUTPUT->notification("no input given");
    echo "<br/>";
    echo $OUTPUT->continue_button($url);
    exit;
}

if ($value != 0) {
    $sql = "
        SELECT
            c.id,
            c.shortname,
            c.fullname
        FROM
            {course} c,
            {customlabel_course_metadata} ccm,
            {customlabel_mtd_value} v
        WHERE
            c.id = ccm.courseid AND
            ccm.valueid = v.id AND
            v.id = $value
        ORDER BY
            c.fullname
    ";
    if (!$courses = $DB->get_records_sql($sql)) {
        $courses = [];
    }
} else if ($type != 0) {
    $sql = "
        SELECT
            c.id,
            c.shortname,
            c.fullname
        FROM
            {course} c,
            {customlabel_course_metadata} ccm,
            {customlabel_mtd_value} v,
            {customlabel_mtd_value} t
        WHERE
            c.id = ccm.courseid AND
            ccm.valueid = v.id AND
            v.typeid = t.id AND
            t.id = $type
        ORDER BY
            c.fullname
    ";
    if (!$courses = $DB->get_records_sql($sql)) {
        $courses = [];
    }
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('lpclassificationhdr', 'customlabel'));

if (!empty($courses)) {
    $strcourse = get_string('course');
    $strstatus = get_string('status');

    $table = new html_table();
    $table->head = ["<b>$strcourse</b>", "<b>$strstatus</b>"];
    $table->size = ['70%', '30%'];
    $table->width = '90%';
    $table->align = ['left', 'center'];
    foreach ($courses as $acourse) {
        $curl = new moodle_url('/course/view.php', ['id' => $acourse->id]);
        $courselink = '<a target="_blanck" href="'.$curl.'">'.format_string($acourse->fullname).'</a>';
        if (function_exists('ext_course_status_get_desc')) {
            $table->data[] = [$courselink, ext_course_status_get_desc($acourse)];
        } else {
            $table->data[] = [$courselink, ''];
        }
    }
    echo html_writer::table($table);
    if ($value != 0) {
        echo $OUTPUT->continue_button($url."?view=metadata&amp;type={$type}");
    } else {
        echo $OUTPUT->continue_button($url."?view=classifiers");
    }
} else {
    echo $OUTPUT->continue_button($url);
}

echo $OUTPUT->footer();

