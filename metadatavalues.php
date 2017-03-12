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

require_once($CFG->dirroot.'/mod/customlabel/forms/EditValueForm.php');

// Get parms.

$type = optional_param('typeid', 0, PARAM_INT);

// Get necessary data.

$url = new moodle_url('/mod/customlabel/adminmetadata.php');

$config = get_config('customlabel');

$types = $DB->get_records_menu($config->classification_type_table, null, 'sortorder', 'id, name');

if (!$types) {
    echo $OUTPUT->notification(get_string('noclassifiers', 'customlabel'));
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
        include('metadatavalues.controller.php');
    }
}

// Print page.

echo $deferredheader;

echo get_string('editclass', 'customlabel') . ':';

echo $OUTPUT->single_select(new moodle_url('', array('view' => $view)), 'typeid', $types, $type);

echo $OUTPUT->heading(get_string('metadataset', 'customlabel'));
$params = array($config->classification_value_type_key => $type);
if (!$values = $DB->get_records($config->classification_value_table, $params, 'sortorder')) {
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
                {{$config->classification_value_table}} v
            LEFT JOIN
                {{$config->course_metadata_table}} ccm
            ON
                ccm.{$config->course_metadata_value_key} = v.id
            LEFT JOIN
                {course} c
            ON
                (ccm.{$config->course_metadata_course_key} = c.id OR ccm.{$config->course_metadata_course_key} IS NULL)
            WHERE
                ccm.{$config->course_metadata_value_key} = {$avalue->id}
        ";
        $avalue->courses = $DB->count_records_sql($sql);

        $params = array('view' => 'qualifiers', 'what' => 'delete', 'valueid' => $avalue->id, 'typeid' => $type);
        $deleteurl = new moodle_url('/mod/customlabel/adminmetadata.php', $params);
        $cmds = '<a href="'.$deleteurl.'"><img src="'.$OUTPUT->pix_url('/t/delete')."\" alt=".get_string('delete').'"></a>';

        $params = array('typeid' => $type, 'what' => 'edit', 'valueid' => $avalue->id);
        $editurl = new moodle_url('/mod/customlabel/adminmetadata.php', $params);
        $cmds .= '&nbsp;<a href="'.$editurl.'"><img src="'.$OUTPUT->pix_url('/t/edit').'" /></a>';
        if ($i > 0) {
            $params = array('typeid' => $type, 'what' => 'up', 'valueid' => $avalue->id);
            $upurl = new moodle_url('/mod/customlabel/adminmetadata.php', $params);
            $cmds .= '&nbsp;<a href="'.$upurl.'"><img src="'.$OUTPUT->pix_url('/t/up').'" /></a>';
        } else {
            $cmds .= '&nbsp;&nbsp;&nbsp;';
        }
        if ($i < $valuecount - 1) {
            $params = array('typeid' => $type, 'what' => 'down', 'valueid' => $avalue->id);
            $downurl = new moodle_url('/mod/customlabel/adminmetadata.php', $params);
            $cmds .= '&nbsp;<a href="'.$downurl.'"><img src="'.$OUTPUT->pix_url('/t/down').'" /></a>';
        } else {
            $cmds .= "&nbsp;&nbsp;&nbsp;";
        }
        $params = array('value' => $avalue->id, 'typeid' => $type);
        $lpshowclassifiedurl = new moodle_url('/local/admin/lpshowclassified.php', $params);
        $img = '<img src="'.$OUTPUT->pix_url('/t/hide').'">';
        $coursecount = ($avalue->courses) ? '<a href="'.$lpshowclassifiedurl.'">'.$avalue->courses.' '.$img.'</a>' : 0;
        $selcheck = '<input type="checkbox" name="items[]" value="'.$avalue->id.'" />';
        $table->data[] = array($selcheck, $avalue->code, format_string($avalue->value), $coursecount, $cmds);
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
