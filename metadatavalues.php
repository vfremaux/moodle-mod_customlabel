<?php
// This file is part of Moodle - http://moodle.org/
// // Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// // Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// // You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 */

require_once($CFG->dirroot.'/mod/customlabel/forms/EditValueForm.php');

// Get parms.

$type = optional_param('typeid', 0, PARAM_INT);

// Get necessary data.

$url = $CFG->wwwroot.'/mod/customlabel/adminmetadata.php';

$types = $DB->get_records_menu($CFG->classification_type_table, null, 'name', 'id, name');

if (!$types) {
    notice(get_string('noclassifiers', 'customlabel'));
    echo $OUTPUT->footer();
    return;
}

// Form and controller.

if ($action == 'edit') {
    $mform = new EditValueForm($view, 'update', $type, $url);
} else {
    $mform = new EditValueForm($view, 'add', $type, $url);
}

if (!$mform->is_cancelled()) {
    if ($action) {
        include 'metadatavalues.controller.php';
    }
}

// Print page.

echo $deferredheader;

echo get_string('editclass', 'customlabel') . ':';

echo $OUTPUT->single_select($url . '?view='.$view, 'typeid', $types, $type);

echo $OUTPUT->heading(get_string('metadataset', 'customlabel'));
if (!$values = $DB->get_records($CFG->classification_value_table, array($CFG->classification_value_type_key => $type), 'sortorder')) {
    $values = array();
}
// Make the value form.
$strvalues = get_string('value', 'customlabel');
$strcode = get_string('code', 'customlabel');
$strcourses = get_string('courses');
$strcommands = get_string('commands', 'customlabel');
$table = new html_table();
$table->head = array("", "<b>$strcode</b>", "<b>$strvalues</b>", "<b>$strcourses</b>", "<b>$strcommands</b>");
$table->width = array('10%', '50%', '10%', '30%');
$table->align = array('center', 'left', 'center', 'right');
$table->width = '90%';

echo $OUTPUT->box_start();
if (!empty($values)) {
    $i = 0;
    $valuecount = count($values);
    foreach ($values as $avalue) {
         $sql = "
            SELECT
                COUNT(ccm.id) AS courses
            FROM
                {{$CFG->classification_value_table}} v
            LEFT JOIN
                {{$CFG->course_metadata_table}} ccm
            ON
                ccm.{$CFG->course_metadata_value_key} = v.id
            LEFT JOIN
                {course} c
            ON
                (ccm.{$CFG->course_metadata_course_key} = c.id OR ccm.{$CFG->course_metadata_course_key} IS NULL)
            WHERE
                ccm.{$CFG->course_metadata_value_key} = {$avalue->id}                    
        ";
        $avalue->courses = $DB->count_records_sql($sql);

       $cmds = "<a href=\"{$url}?view=qualifiers&amp;what=delete&amp;valueid={$avalue->id}&typeid={$type}\"><img src=\"".$OUTPUT->pix_url('/t/delete')."\" alt=".get_string('delete').'"></a>';
        $cmds .= "&nbsp;<a href=\"{$url}?typeid={$type}&amp;what=edit&amp;valueid={$avalue->id}\"><img src=\"".$OUTPUT->pix_url('/t/edit')."\" /></a>";
        if ($i > 0) {
            $cmds .= "&nbsp;<a href=\"{$url}?typeid={$type}&amp;what=up&amp;valueid={$avalue->id}\"><img src=\"".$OUTPUT->pix_url('/t/up')."\" /></a>";
        } else {
            $cmds .= "&nbsp;&nbsp;&nbsp;";
        }
        if ($i < $valuecount - 1) {
            $cmds .= "&nbsp;<a href=\"{$url}?typeid={$type}&amp;what=down&amp;valueid={$avalue->id}\"><img src=\"".$OUTPUT->pix_url('/t/down')."\" /></a>";
        } else {
            $cmds .= "&nbsp;&nbsp;&nbsp;";
        }
        $coursecount = ($avalue->courses) ? "<a href=\"$CFG->wwwroot/local/admin/lpshowclassified.php?value={$avalue->id}&amp;typeid={$type}\">{$avalue->courses} <img src=\"".$OUTPUT->pix_url('/t/hide')."\"></a>" : 0 ;
        $selcheck = "<input type=\"checkbox\" name=\"items[]\" value=\"{$avalue->id}\" />";
        $table->data[] = array($selcheck, $avalue->code, $avalue->value, $coursecount, $cmds);
        $i++;
    }
    echo html_writer::table($table);
} else {
    print_string('novalues', 'customlabel');
}
echo $OUTPUT->box_end();

if ($type) {
    echo $OUTPUT->box_start('addform');
    if (isset($data)) {
        $mform->set_data($data);
    }
    $mform->display();
    echo $OUTPUT->box_end();
}
