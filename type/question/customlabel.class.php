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
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

define('CUSTOMLABEL_QUESTION_ANSWERED', 1);
define('CUSTOMLABEL_QUESTION_ANSWER_IS_CORRECT', 2);

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 *
 *
 */

class customlabel_type_question extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'question';
        $this->fields = array();

        $this->hasamd = true;

        if (isset($data->content)) {
            // $data is a customlabel record not yet decoded. This comes from modedit.php
            $preset = json_decode(base64_decode($data->content));
            if (!empty($preset)) {
                // Decode content and append members to $data.
                foreach ($preset as $key => $value) {
                    $data->$key = $value;
                }
            }
        }
        $answeroptions = array(1);
        if (!empty($data->isqcmchallenge)) {
            $answers = explode("\n", $data->answertext);
            $answernum = count($answers);
            $answeroptions = array();
            for ($i = 2 ; $i <= $answernum - 1 ; $i++) {
                $answeroptions[] = $i;
            }

            if (!empty($data->correctanswer)) {
                // Given correct answer is out of bounds.
                if ($data->correctanswer > $answernum) {
                    unset($data->correctanswer);
                }
            }
        }

        $field = new StdClass;
        $field->name = 'questiontext';
        $field->type = 'editor';
        $field->rows = 20;
        $field->itemid = 0;
        $this->fields['questiontext'] = $field;

        $field = new StdClass;
        $field->name = 'hint';
        $field->type = 'editor';
        $field->rows = 20;
        $field->itemid = 1;
        $this->fields['hint'] = $field;

        $field = new StdClass;
        $field->name = 'hintinitiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 0;
        $this->fields['hintinitiallyvisible'] = $field;

        $field = new StdClass;
        $field->name = 'answertext';
        $field->type = 'editor';
        $field->rows = 20;
        $field->itemid = 2;
        $this->fields['answertext'] = $field;

        $field = new StdClass;
        $field->name = 'initiallyvisible';
        $field->type = 'choiceyesno';
        $field->default = 0;
        $this->fields['initiallyvisible'] = $field;

        $field = new StdClass;
        $field->name = 'showansweron';
        $field->type = 'datetime';
        $field->default = time() + DAYSECS * 7;
        $this->fields['showansweron'] = $field;

        $field = new StdClass;
        $field->name = 'isqcmchallenge';
        $field->type = 'choiceyesno';
        $field->default = false;
        $this->fields['isqcmchallenge'] = $field;

        $field = new StdClass;
        $field->name = 'shuffleanswers';
        $field->type = 'choiceyesno';
        $field->default = true;
        $field->disabledif = array('isqcmchallenge', 'neq', 1);
        $this->fields['shuffleanswers'] = $field;

        $field = new StdClass;
        $field->name = 'attempts';
        $field->type = 'text';
        $field->default = 0;
        $field->disabledif = array('isqcmchallenge', 'neq', 1);
        $this->fields['attempts'] = $field;

        $field = new StdClass;
        $field->name = 'correctanswer';
        $field->type = 'list';
        $field->options = $answeroptions;
        $field->straightoptions = true;
        $field->disabledif = array('isqcmchallenge', 'neq', 1);
        $this->fields['correctanswer'] = $field;
    }

    public function preprocess_data() {
        global $OUTPUT, $COURSE, $USER;

        $minusurl = $OUTPUT->image_url('minus', 'customlabel');
        $plusurl = $OUTPUT->image_url('plus', 'customlabel');
        $this->data->initialcontrolimageurl = ($this->data->initiallyvisible) ? $minusurl : $plusurl;
        $this->data->hintinitialcontrolimageurl = ($this->data->hintinitiallyvisible) ? $minusurl : $plusurl;
        $this->data->initialclass = ($this->data->initiallyvisible) ? '' : 'hidden';
        $this->data->hintinitialclass = ($this->data->hintinitiallyvisible) ? '' : 'hidden';
        $this->data->customid = $this->cmid;
        if (!empty($this->data->showansweron) && $this->data->showansweron->enabled) {
            $qdate = mktime($this->data->showansweron->hour,
                            $this->data->showansweron->minute,
                            0,
                            $this->data->showansweron->month,
                            $this->data->showansweron->day,
                            $this->data->showansweron->year);
            if ($qdate < time()) {
                $this->data->canshow = true;
            }
            $this->data->opentime = userdate($qdate);
        } else {
            $this->data->canshow = true;
        }

        $this->data->cmid = $this->cmid;

        // Prepare questions
        if (!empty($this->data->isqcmchallenge)) {
            $this->data->answertext = str_replace('</p><p>', "</p><p>\n", $this->data->answertext);
            $answers = explode("\n", $this->data->answertext);
            $correctanswer = $answers[$this->data->correctanswer];

            // Get eventual stored answer.
            $params = array('customlabelid' => $this->instance->id, 'userid' => $USER->id);
            $userdata = $DB->get_record('customlabel_user_data', $params);

            // Prepare a reverse index array.
            $i = 1;
            foreach ($answers as $answer) {
                $reverseanswers[$answer] = $i;
                $i++;
            }

            if (!empty($this->data->shuffleanswers)) {
                shuffle($answers);
            }

            foreach ($answers as $answer) {
                $qcmanswertpl = new StdClass;
                $qcmanswertpl->answertext = $answer;
                $qcmanswertpl->aid = $reverseanswers[$answer];
                if ($userdata) {
                    if ($qcmanswertpl->aid == $userdata->completion2) {
                        $qcmanswertpl->checked = 'checked="checked"';

                        if ($userdata->completion2 == $this->data->correctanswer) {
                            $qcmanswertpl->answerclass = 'success';
                        } else {
                            $qcmanswertpl->answerclass = 'error';
                        }
                    }

                    if ($this->data->attempts && ($userdata->completion3 >= $this->data->attempts)) {
                        $this->data->locked = true;
                    }
                }
                $this->data->qcmanswer[] = $qcmanswertpl;
            }
        }

        $context = context_course::instance($COURSE->id);
        if (has_capability('moodle/course:manageactivities', $context)) {
            $this->data->hascap = true;
        }
    }

    /**
     * Called from the module add_completion_rules @see mod/customlabel/lib.php
     * Add customized per type completion rules (up to 3)
     * @param object $mform the completion form
     */
    static public function add_completion_rules($mform) {

        $mform->addElement('checkbox', 'completion1enabled', '', get_string('completion1', 'customlabeltype_question'));
        $mform->addElement('checkbox', 'completion2enabled', '', get_string('completion2', 'customlabeltype_question'));

        return array('completion1enabled', 'completion2enabled');
    }

    /**
     * Provides the complete value to match for each used completion.
     * @param int $completionix the completion index from 1 to 3.
     */
    public function complete_value($completionix) {
        global $DB, $USER;

        $return = false;

        switch ($completionix) {
            case 1 : {
                $return = CUSTOMLABEL_QUESTION_ANSWERED;
                break;
            }
            case 2 : {
                $return = CUSTOMLABEL_QUESTION_ANSWER_IS_CORRECT;
                break;
            }
            case 3 : {
                break;
            }
        }

        return $return;
    }
}
