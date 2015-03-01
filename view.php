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
 * main view of a customlabel is always in its course container, unless
 * we display its XML exportation.
 *
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

require('../../config.php');
require_once($CFG->dirroot.'/mod/customlabel/lib.php');

$id = optional_param('id',0,PARAM_INT);    // Course Module ID, or
$l = optional_param('l',0,PARAM_INT);     // Label ID
$what = optional_param('what', '', PARAM_ALPHA);     // What to be seen
if ($id) {
    if (! $cm = get_coursemodule_from_id('customlabel', $id)) {
        error("Course Module ID was incorrect");
    }
    if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error('coursemisconf');
    }
    if (! $customlabel = $DB->get_record('customlabel', array('id' => $cm->instance))) {
        error("Course module is incorrect");
    }

} else {
    if (! $customlabel = $DB->get_record('customlabel', array('id' => $l))) {
        error("Course module is incorrect");
    }
    if (! $course = $DB->get_record('course', array('id' => $customlabel->course))) {
        error("Course is misconfigured");
    }
    if (! $cm = get_coursemodule_from_instance("customlabel", $customlabel->id, $course->id)) {
        error("Course Module ID was incorrect");
    }
}

require_login($course->id);

// If we are exporting to XML.

if ($what == 'xml') {
    echo $OUTPUT->header();
    $customlabel = $DB->get_record('customlabel', array('id' => $l));
    $instance = customlabel_load_class($customlabel);
    $xml = $instance->get_xml();
    $xml = str_replace('<', '&lt;', $xml);
    $xml = str_replace('>', '&gt;', $xml);
    echo '<pre>'.$xml.'</pre>';
    die;
}

// Normal view "in-situ".

redirect(new moodle_url('/course/view.php', array('id' => $course->id)));
