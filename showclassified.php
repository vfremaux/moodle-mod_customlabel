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

require_once('../../config.php');

$url = new moodle_url('/mod/customlabel/adminmetadata.php');

$value = optional_param('value', 0, PARAM_INT);
$type = optional_param('typeid', 0, PARAM_INT);

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
            c.fullname,
            c.approval_status_id
        FROM
            {course} c,
            {customlabel_course_metadata} ccm,
            {customlabel_mtd_value} v
        WHERE
            c.id = ccm.course AND
            ccm.value = v.id AND
            v.id = $value
        ORDER BY
            c.fullname
    ";
    if (!$courses = $DB->get_records_sql($sql)) {
        $courses = array();
    }
} elseif ($type != 0) {
    $sql = "
        SELECT
            c.id,
            c.shortname,
            c.fullname,
            c.approval_status_id
        FROM
            {course} c,
            {customlabel_course_metadata} ccm,
            {customlabel_mtd_value} v,
            {customlabel_mtd_value} t
        WHERE
            c.id = ccm.course AND
            ccm.value = v.id AND
            v.type = t.id AND
            t.id = $type
        ORDER BY
            c.fullname
    ";
    if (!$courses = $DB->get_records_sql($sql)) {
        $courses = array();
    }
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('lpclassificationheading', 'customlabel'));

if (!empty($courses)) {
    $strcourse = get_string('course');
    $strstatus = get_string('status');

    $table->head = array("<b>$strcourse</b>", "<b>$strstatus</b>");
    $table->size = array('70%', '30%');
    $table->width = array('570px');
    $table->align = array('left', 'center');
    foreach ($courses as $acourse) {
        $courselink = "<a href=\"{$CFG->wwwroot}/course/view.php?id={$acourse->id}\">".format_string($acourse->fullname).'</a>';
        $table->data[] = array($courselink, course_status_get_desc($acourse));
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

