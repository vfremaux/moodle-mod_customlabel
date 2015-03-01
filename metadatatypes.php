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
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 * @see Acces from adminmetadata.php
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Sorry, You cannot use this script this way');
}

require_once($CFG->dirroot.'/mod/customlabel/forms/EditTypeForm.php');

// Get parms.

$type = optional_param('type', 0, PARAM_INT);

// Get necessary data.

$url = new moodle_url('/mod/customlabel/adminmetadata.php');

if ($action == 'edit') {
    $mform = new EditTypeForm($view, 'update', $url);
} else {
    $mform = new EditTypeForm($view, 'add', $url);
}

if (!$mform->is_cancelled()) {
    if ($action) {
        include_once($CFG->dirroot.'/mod/customlabel/metadatatypes.controller.php');
    }
}

// Print page start (after controller).
echo $deferredheader;

$types = $DB->get_records($CFG->classification_type_table, null, 'sortorder');

echo $OUTPUT->heading(get_string('classifierstypes', 'customlabel'));
// Make the type form.
$strname = get_string('typename', 'customlabel');
$strcode = get_string('code', 'customlabel');
$strcourses = get_string('courses');
$struseas = get_string('usedas', 'customlabel');
$strdesc = get_string('description');
$strcommands = get_string('commands', 'customlabel');
$table = new html_table();
$table->head = array("<b>$strname</b>", "<b>$struseas</b>", "<b>$strcode</b>", "<b>$strdesc</b>", "<b>$strcourses</b>", "<b>$strcommands</b>");
$table->size = array('20%', '5%', '10%', '50%', '5%', '10%');
$table->align = array('left', 'center', 'center', 'center', 'right');
$table->width = '95%'; 
echo $OUTPUT->box_start();
$count = count($types);
$i = 0;

if ($types) {
    $upstr = get_string('up', 'customlabel');
    $downstr = get_string('down', 'customlabel');

    foreach ($types as $atype) {

        $sql = "
            SELECT 
                COUNT(c.id)
            FROM
                {{$CFG->classification_value_table}} v
            LEFT JOIN
                {{$CFG->course_metadata_table}} ccm
            ON
                ccm.{$CFG->course_metadata_value_key} = v.id
            LEFT JOIN
                {course} c
            ON
                c.id = ccm.{$CFG->course_metadata_course_key}
            WHERE
                v.{$CFG->classification_value_type_key} = ?
        ";
        $atype->courses = $DB->count_records_sql($sql, array($atype->id));

        // Delete command.
        $piximage = '<img src="'.$OUTPUT->pix_url('/t/delete').'" alt="'.get_string('delete').'">';
        $cmdurl = clone($url);
        $cmdurl->params(array('view' => 'classifiers', 'what' => 'delete', 'typeid' => $atype->id));
        $cmds = '<a href="'.$cmdurl.'">'.$piximage.'</a>';

        // Edit command.
        $piximage = '<img src="'.$OUTPUT->pix_url('/t/edit').'" alt="'.get_string('editvalues', 'customlabel').'">';
        $cmdurl = clone($url);
        $cmdurl->params(array('view' => 'classifiers', 'what' => 'edit', 'typeid' => $atype->id));
        $cmds .= ' <a href="'.$cmdurl.'">'.$piximage.'</a>';

        if ($i > 0) {
            // Up command.
            $piximage = '<img src="'.$OUTPUT->pix_url('/t/up').'" alt="'.get_string('up', 'customlabel').'">';
            $cmdurl = clone($url);
            $cmdurl->params(array('view' => 'classifiers', 'what' => 'up', 'typeid' => $atype->id));
            $cmds .= ' <a href="'.$cmdurl.'" title="'.$upstr.'">'.$piximage.'</a>';
        }
        if ($i < $count - 1) {
            $piximage = '<img src="'.$OUTPUT->pix_url('/t/down').'" alt="'.get_string('down', 'customlabel').'">';
            $cmdurl = clone($url);
            $cmdurl->params(array('view' => 'classifiers', 'what' => 'down', 'typeid' => $atype->id));
            $cmds .= ' <a href="'.$cmdurl.'" title="'.$downstr.'">'.$piximage.'</a>';
        }
        $link = "<a href=\"{$url}?view=qualifiers&typeid={$atype->id}\">{$atype->name}</a> ";
        $counturl = new moodle_url('/mod/customlabel/showclassified.php', array('typeid' => $atype->id));
        $coursecount = ($atype->courses) ? '<a href="'.$counturl.'">'.$atype->courses.' <img src="'.$OUTPUT->pix_url('/t/hide').'"></a>' : 0 ;
        $table->data[] = array($link, get_string($atype->type, 'customlabel'), $atype->code, $atype->description, $coursecount, $cmds);
        $i++;
    }

    echo html_writer::table($table);
} else {
    print_string('notypes', 'customlabel');
}
echo $OUTPUT->box_end();

echo $OUTPUT->box_start('addform');
if (isset($data)) {
    $mform->set_data($data);
}
$mform->display();
echo $OUTPUT->box_end();
