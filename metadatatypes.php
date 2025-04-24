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
 * Edit metadata types (domains)
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux <valery.fremaux@gmail.com> (www.activeProLearn.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @see Acces from adminmetadata.php
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/forms/EditTypeForm.php');

// Get parms.

$type = optional_param('type', 0, PARAM_INT);

$config = get_config('customlabel');

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

$types = $DB->get_records($config->classification_type_table, null, 'sortorder');

echo $OUTPUT->heading(get_string('classifierstypes', 'customlabel'));

// Make the type form.
$strname = get_string('typename', 'customlabel');
$strcode = get_string('code', 'customlabel');
$strcourses = get_string('courses');
$struseas = get_string('usedas', 'customlabel');
$strdesc = get_string('description');
$strcommands = get_string('commands', 'customlabel');
$table = new html_table();
$table->head = ["<b>$strname</b>",
                     "<b>$struseas</b>",
                     "<b>$strcode</b>",
                     "<b>$strdesc</b>",
                     "<b>$strcourses</b>",
                     "<b>$strcommands</b>"];
$table->size = ['20%', '5%', '10%', '50%', '5%', '10%'];
$table->align = ['left', 'center', 'center', 'center', 'right'];
$table->width = '95%';
echo $OUTPUT->box_start();
$count = count($types);
$i = 0;

if ($types) {
    $upstr = get_string('up', 'customlabel');
    $downstr = get_string('down', 'customlabel');

    $configclassvaluetable = clean_param($config->classification_value_table, PARAM_ALPHANUMEXT);
    $configcoursemetadatatable = clean_param($config->course_metadata_table, PARAM_ALPHANUMEXT);
    $configmetadatavaluekey = clean_param($config->course_metadata_value_key, PARAM_ALPHANUMEXT);
    $configmetadatacoursekey = clean_param($config->course_metadata_course_key, PARAM_ALPHANUMEXT);
    $configvaluetypekey = clean_param($config->classification_value_type_key, PARAM_ALPHANUMEXT);

    foreach ($types as $atype) {

        $sql = "
            SELECT
                COUNT(c.id)
            FROM
                {{$configclassvaluetable}} v
            LEFT JOIN
                {{$configcoursemetadatatable}} ccm
            ON
                ccm.{$configmetadatavaluekey} = v.id
            LEFT JOIN
                {course} c
            ON
                c.id = ccm.{$configmetadatacoursekey}
            WHERE
                v.{$configvaluetypekey} = ?
        ";
        $atype->courses = $DB->count_records_sql($sql, [$atype->id]);

        // Delete command.
        $piximage = $OUTPUT->pix_icon('/t/delete', get_string('delete'));
        $cmdurl = clone($url);
        $cmdurl->params(['view' => 'classifiers', 'what' => 'delete', 'typeid' => $atype->id]);
        $cmds = '<a href="'.$cmdurl.'">'.$piximage.'</a>';

        // Edit command.
        $piximage = $OUTPUT->pix_icon('/t/edit', get_string('editvalues', 'customlabel'));
        $cmdurl = clone($url);
        $cmdurl->params(['view' => 'classifiers', 'what' => 'edit', 'typeid' => $atype->id]);
        $cmds .= ' <a href="'.$cmdurl.'">'.$piximage.'</a>';

        if ($i > 0) {
            // Up command.
            $piximage = $OUTPUT->pix_icon('/t/up', get_string('up', 'customlabel'));
            $cmdurl = clone($url);
            $cmdurl->params(['view' => 'classifiers', 'what' => 'up', 'typeid' => $atype->id]);
            $cmds .= ' <a href="'.$cmdurl.'" title="'.$upstr.'">'.$piximage.'</a>';
        }
        if ($i < $count - 1) {
            $pixicon = $OUTPUT->pix_icon('/t/down', get_string('down', 'customlabel'));
            $cmdurl = clone($url);
            $cmdurl->params(['view' => 'classifiers', 'what' => 'down', 'typeid' => $atype->id]);
            $cmds .= ' <a href="'.$cmdurl.'" title="'.$downstr.'">'.$pixicon.'</a>';
        }
        $typename = format_string($atype->name);
        $link = "<a href=\"{$url}?view=qualifiers&typeid={$atype->id}\">{$typename}</a> ";
        $counturl = new moodle_url('/mod/customlabel/showclassified.php', ['typeid' => $atype->id]);
        $img = $OUTPUT->pix_icon('/t/hide', get_string('hide'));
        $coursecount = ($atype->courses) ? '<a href="'.$counturl.'">'.$atype->courses.' '.$img.'</a>' : 0;
        $typestr = 'unresolved type';
        if (!empty($atype->type)) {
            $typestr = get_string($atype->type, 'customlabel');
        }
        $table->data[] = [$link, $typestr, $atype->code, format_string($atype->description), $coursecount, $cmds];
        $i++;
    }

    echo html_writer::table($table);
} else {
    print_string('notypes', 'customlabel');
}
echo $OUTPUT->box_end();

echo $OUTPUT->heading(get_string('addatype', 'customlabel'), 3);

echo $OUTPUT->box_start('addform');
if (isset($data)) {
    $mform->set_data($data);
}

$mform->display();

echo $OUTPUT->box_end();
