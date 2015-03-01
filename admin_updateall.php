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
 * this admin screen allows updating massively all customlabels when a change has been proceeded
 * within the templates. Can only be done in one language.
 *
 * @package    mod-customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');
require_once($CFG->dirroot.'/mod/customlabel/admin_updateall_form.php');

$systemcontext = context_system::instance();

require_login();
require_capability('moodle/site:config', $systemcontext);

$url = $CFG->wwwroot.'/mod/customlabel/admin_updateall.php';

$PAGE->set_context($systemcontext);
$PAGE->set_url($url);
$PAGE->set_title(get_string('updatelabels', 'customlabel', get_string('pluginname', 'customlabel')));
$PAGE->set_heading(get_string('updatelabels', 'customlabel', get_string('pluginname', 'customlabel')));
/* SCANMSG: may be additional work required for $navigation variable */
$PAGE->set_focuscontrol('');
$PAGE->set_cacheable(true);

// Get courses.

$allcourses = $DB->get_records_menu('course', null, 'shortname', 'id,shortname');

// Get types.

$alltypes = customlabel_get_classes($systemcontext, false);
if ($alltypes) {
    foreach ($alltypes as $atype) {
        $types[$atype->id] = $atype->name;
    }
} else {
    $types = array();
}

// If data submitted, proceed.
$form = new customlabel_updateall_form($url, array('courses' => $allcourses, 'types' => $types));

if ($form->is_cancelled()) {
    redirect($CFG->wwwroot.'/admin/settings.php?section=modsettingcustomlabel');
}

echo $OUTPUT->header();

if ($data = $form->get_data()) {
    echo $OUTPUT->container_start('emptyleftspace');
    echo $OUTPUT->heading(get_string('updatelabels', 'customlabel', get_string('modulename', 'customlabel')), 1);

    $courseids = clean_param_array($data->courses, PARAM_INT);
    $labelclasses = clean_param_array($data->labelclasses, PARAM_RAW);
    if (empty($courseids)) {
        $courses = array();
    } else {
        $courses = $DB->get_records_list('course', 'id', $courseids);
    }

    if (empty($labelclasses)) {
        $labelclasses = array();
    }

    echo "<pre>";
    foreach ($courses as $courseid => $course) {
        mtrace("processing course $courseid : $course->shortname");
        customlabel_course_regenerate($course, $labelclasses);
    }
    echo '</pre>';
    echo $OUTPUT->container_end();
    echo $OUTPUT->continue_button(new moodle_url('/mod/customlabel/admin_updateall.php'));
} else {
    // Print form.
    echo $OUTPUT->heading(get_string('updatelabels', 'customlabel', get_string('modulename', 'customlabel')), 1);
    $form->display();
}

echo $OUTPUT->footer();
