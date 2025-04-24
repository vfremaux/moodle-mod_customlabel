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
 * main view of a customlabel is always in its course container, unless we display its XML exportation.
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux <valery.fremaux@gmail.com> (www.activeProLearn.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

require('../../config.php');
require_once($CFG->dirroot.'/mod/customlabel/lib.php');

$id = optional_param('id', 0, PARAM_INT);    // Course Module ID, or.
$l = optional_param('l', 0, PARAM_INT);     // Label ID.
$what = optional_param('what', '', PARAM_ALPHA);     // What to be seen.

if ($id) {
    if (! $cm = get_coursemodule_from_id('customlabel', $id)) {
        throw new moodle_exception('invalidcoursemodule');
    }
    if (! $course = $DB->get_record('course', ['id' => $cm->course])) {
        throw new moodle_exception('coursemisconf');
    }
    if (! $customlabel = $DB->get_record('customlabel', ['id' => $cm->instance])) {
        throw new moodle_exception('invalidcoursemodule');
    }
} else {
    if (! $customlabel = $DB->get_record('customlabel', ['id' => $l])) {
        throw new moodle_exception('invalidcoursemodule');
    }
    if (! $course = $DB->get_record('course', ['id' => $customlabel->course])) {
        throw new moodle_exception('coursemisconf');
    }
    if (! $cm = get_coursemodule_from_instance("customlabel", $customlabel->id, $course->id)) {
        throw new moodle_exception('invalidcoursemodule');
    }
}

require_login($course->id);

// If we are exporting to XML.

if ($what == 'xml') {
    echo $OUTPUT->header();
    $customlabel = $DB->get_record('customlabel', ['id' => $l]);
    $customlabel->coursemodule = $cm->id;
    $instance = customlabel_load_class($customlabel);
    $xml = $instance->get_xml();
    $xml = str_replace('<', '&lt;', $xml);
    $xml = str_replace('>', '&gt;', $xml);
    echo '<pre>'.$xml.'</pre>';
    die;
}

// Normal view "in-situ".

redirect(new moodle_url('/course/view.php', ['id' => $course->id]));
