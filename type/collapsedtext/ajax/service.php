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

require('../../../../../config.php');
require_once($CFG->dirroot.'/lib/completionlib.php');
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

$action = required_param('what', PARAM_TEXT);

require_login();

if ($action == 'open') {
    $cmid = required_param('cmid', PARAM_INT);
    $item = required_param('item', PARAM_INT);

    $cm = $DB->get_record('course_modules', array('id' => $cmid));
    $instance = $DB->get_record('customlabel', array('id' => $cm->instance));

    $instance->coursemodule = $cm->id;
    $customlabel = customlabel_load_class($instance);

    // Mark each chapter as a power of 2, 1 for chapter 1, 10 for chapter 2 and so on.
    // If all chapter are marked, the customlabel is complete. ($item starts at 0).
    $pow = pow(2, $item - 1);

    $params = array('userid' => $USER->id, 'customlabelid' => $instance->id);
    $ud = $DB->get_record('customlabel_user_data', $params);
    if (!$ud) {
        $ud = new StdClass;
        $ud->userid = $USER->id;
        $ud->customlabelid = $cm->instance;
        $ud->completion1 = $pow;
        $ud->timecompleted1 = time();
        $ud->id = $DB->insert_record('customlabel_user_data', $ud);
        echo "writing $ud->id with $pow ";
    } else {
        $ud->completion1 |= $pow;
        echo "updating $ud->id with $pow in $ud->completion1 . Item is $item ";
        $DB->update_record('customlabel_user_data', $ud);
    }

    // Check if everything is marked.
    $fullmark = pow(2, 0 + @$customlabel->get_data('chapternum')) - 1;
    if ($ud->completion1 == $fullmark) {
        $course = $DB->get_record('course', array('id' => $cm->course));
        $completion = new completion_info($course);
        if (($completion->is_enabled($cm) == COMPLETION_TRACKING_AUTOMATIC) &&
           $instance->completion1enabled) {
            $completion->update_state($cm, COMPLETION_COMPLETE, $USER->id);
        }
    }
}

if ($action == 'complete') {
    $cmid = required_param('cmid', PARAM_INT);

    $cm = $DB->get_record('course_modules', array('id' => $cmid));
    $instance = $DB->get_record('customlabel', array('id' => $cm->instanceid));

    $instance->coursemodule = $cm->id;
    $customlabel = customlabel_load_class($instance);

    $fullmark = pow(2, $customlabel->get_data('chapternum')) - 1;

    $params = array('userid' => $USER->id, 'customlabelid' => $instance->id);
    $ud = $DB->get_record('customlabel_user_data', $params);
    if (!$ud) {
        $ud = new StdClass;
        $ud->userid = $USER->id;
        $ud->customlabelid = $cm->instance;
        $ud->completion1 = $fullmark;
        $ud->timecompleted1 = time();
        $DB->insert_record('customlabel_user_data', $ud);
    } else {
        $ud->completion1 = $fullmark;
        $DB->update_record('customlabel_user_data', $ud);
    }
    $course = $DB->get_record('course', array('id' => $cm->course));

    $completion = new completion_info($course);
    if (($completion->is_enabled($cm) == COMPLETION_TRACKING_AUTOMATIC) &&
       $instance->completion1enabled) {
        $completion->update_state($cm, COMPLETION_COMPLETE, $USER->id);
    }
}