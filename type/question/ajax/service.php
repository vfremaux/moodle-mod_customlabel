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

if ($action == 'submit') {
    $cmid = required_param('cmid', PARAM_INT);
    $givenanswer = required_param('answernum', PARAM_INT);

    $cm = $DB->get_record('course_modules', array('id' => $cmid));
    $instance = $DB->get_record('customlabel', array('id' => $cm->instance));

    $instance->coursemodule = $cm->id;
    $customlabel = customlabel_load_class($instance);

    $params = array('userid' => $USER->id, 'customlabelid' => $instance->id);

    $correctanswer = $customlabel->get_data('correctanswer');
    $status = CUSTOMLABEL_QUESTION_ANSWERED;
    if ($correctanswer == $givenanswer) {
        $status = CUSTOMLABEL_QUESTION_ANSWER_IS_CORRECT;
    }

    $ud = $DB->get_record('customlabel_user_data', $params);
    if (!$ud) {
        $ud = new StdClass;
        $ud->userid = $USER->id;
        $ud->customlabelid = $cm->instance;
        $ud->completion1 = 0 + $status;
        $ud->completion2 = 0 + $givenanswer;
        $ud->completion3 = 1; // Number of tries.
        $ud->timecompleted1 = time();
        $ud->id = $DB->insert_record('customlabel_user_data', $ud);
    } else {
        $ud->completion1 = 0 + $status;
        $ud->completion2 = 0 + $givenanswer;
        $ud->completion3 += 1; // Number of tries.
        $DB->update_record('customlabel_user_data', $ud);
    }

    // Mark completion in moodle completion if needed.
    $course = $DB->get_record('course', array('id' => $cm->course));
    $completion = new completion_info($course);
    if (($completion->is_enabled($cm) == COMPLETION_TRACKING_AUTOMATIC) &&
            $instance->completion1enabled) {
        // Unconditionnaly mark. The user has answered.
        $completion->update_state($cm, COMPLETION_COMPLETE, $USER->id);
    }

    if (($completion->is_enabled($cm) == COMPLETION_TRACKING_AUTOMATIC) &&
            $instance->completion2enabled) {
        // Mark only if correct. The user has answered.
        if ($status == $status = CUSTOMLABEL_QUESTION_ANSWER_IS_CORRECT) {
            $completion->update_state($cm, COMPLETION_COMPLETE, $USER->id);
        }
    }

    $attempts = $customlabel->get_data('attempts');
    $locked = false;
    if (!empty($attempts) && ($ud->completion3 >= @$this->data->attempts)) {
        $locked = true;
    }

    $output = new StdClass;
    $output->cmid = $cmid;
    $output->aid = $givenanswer;
    $output->status = $status;
    $output->locked = $locked;

    echo json_encode($output);
}

