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

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');
require_once($CFG->dirroot.'/mod/customlabel/type/satisfaction/locallib.php');
require_once($CFG->dirroot.'/local/vflibs/jqplotlib.php');

/**
 *
 */
class customlabel_type_satisfaction extends customlabel_type {

    public function __construct($data) {

        parent::__construct($data);
        $this->type = 'satisfaction';
        $this->fields = array();

        $this->usesjqplot = true;

        $field = new StdClass;
        $field->name = 'graphtitle';
        $field->type = 'textfield';
        $field->help = 'graphtitle';
        $field->size = 80;
        $this->fields['graphtitle'] = $field;

        $field = new StdClass;
        $field->name = 'legendorientation';
        $field->type = 'list';
        $field->options = ['e', 'n', 's', 'w'];
        $field->help = 'legendorientation';
        $this->fields['legendorientation'] = $field;

        $field = new StdClass;
        $field->name = 'activityscoresource';
        $field->type = 'datasource';
        $field->source = 'function';
        $field->file = 'mod/customlabel/type/satisfaction/locallib.php';
        $field->function = 'customlabeltype_satisfaction_get_cms';
        $field->help = 'activityscoresource';
        $this->fields['activityscoresource'] = $field;

        $field = new StdClass;
        $field->name = 'qposition';
        $field->type = 'textfield';
        $field->help = 'qposition';
        $this->fields['qposition'] = $field;

        $field = new StdClass;
        $field->name = 'gwidth';
        $field->type = 'textfield';
        $field->default = '800';
        $this->fields['gwidth'] = $field;

        $field = new StdClass;
        $field->name = 'gheight';
        $field->type = 'textfield';
        $field->default = '450';
        $this->fields['gheight'] = $field;

        $field = new StdClass;
        $field->name = 'coloroverrides';
        $field->type = 'textfield';
        $field->help = 'coloroverrides';
        $field->size = 40;
        $this->fields['coloroverrides'] = $field;

        $field = new StdClass;
        $field->name = 'hideicon';
        $field->type = 'choiceyesno';
        $this->fields['hideicon'] = $field;
    }

    public function preprocess_data() {
        global $DB, $OUTPUT;

        if (empty($this->data->activityscoresource)) {
            $this->data->notconfigured = 1;
            $this->data->notconfigurednotification = $OUTPUT->notification(get_string('notconfigured', 'customlabeltype_satisfaction'));
            return;
        }

        $cm = $DB->get_record('course_modules', ['id' => $this->data->activityscoresource]);
        $module = $DB->get_record('modules', ['id' => $cm->module]);

        $this->data->graphtitle = format_string($this->data->graphtitle);

        if ($module->name == 'questionnaire') {
            $satisfaction = $this->get_questionnaire_score($this->data->activityscoresource, $this->data->qposition);
        } else {
            $satisfaction = $this->get_feedback_score($this->data->activityscoresource, $this->data->qposition);
        }

        if (!empty($satisfaction['error'])) {
            $this->data->question = strip_tags($satisfaction['question']);
            $this->data->satisfactiongraph = $OUTPUT->notification(get_string($satisfaction['error'], 'customlabeltype_satisfaction'));
            return;
        }

        $this->data->question = strip_tags($satisfaction['question']);
        $this->data->satisfactiongraph = $this->print_graph('satisfaction', 'donut', $satisfaction['results']);
    }

    /**
     * Get the local score of the required question in a questionnaire result.
     */
    protected function get_questionnaire_score($cmid, $qposition) {
        global $DB;

        try {
            $cm = $DB->get_record('course_modules', ['id' => $cmid]);
        } catch (moodle_exception $ex) {
            throw new moodle_exception("Questionnaire with cmid $cmid does not exist. Review settings.");
        }

        $instance = $DB->get_record('questionnaire', ['id' => $cm->instance]);
        if (!empty($instance->sid)) {
            if (!$realinstance = $DB->get_record('questionnaire_survey', ['id' => $instance->sid])) {
                // Origin model has disapeared.
                $realinstance = $instance;
            }
        } else {
            $realinstance = $instance;
        }
        $question = $DB->get_record('questionnaire_question', ['surveyid' => $realinstance->id, 'position' => $qposition]);
        if (!$question) {
            return ['question' => '', 'results' => null, 'error' => 'unknownitemposition'];
        }

        $questiontype = $DB->get_record('questionnaire_question_type', ['id' => $question->type_id]);
        $allanswerssql = "
            SELECT
                qc.id,
                qc.value,
                qc.content,
                SUM(CASE WHEN r.id IS NOT NULL THEN 1 ELSE 0 END) as matches
            FROM
                {questionnaire_question} q,
                {questionnaire_quest_choice} qc
            LEFT JOIN
                {questionnaire_{$questiontype->response_table}} rt
            ON
                rt.choice_id = qc.id
            LEFT JOIN
                {questionnaire_response} r
            ON
                rt.response_id = r.id
            WHERE
                qc.question_id = q.id AND
                qc.question_id = ? AND
                q.surveyid = ? AND
                (r.questionnaireid = ? OR r.questionnaireid IS NULL)
            GROUP BY
                qc.id
        ";
        $params = [$question->id, $realinstance->id, $instance->id];
        $scores = $DB->get_records_sql($allanswerssql, $params);

        $numresponses = $DB->count_records('questionnaire_response', ['questionnaireid' => $instance->id]);

        $return = [];
        if (!empty($scores)) {
            foreach ($scores as $sc) {
                $return[$this->clean($sc->content)] = ($numresponses > 0) ? $sc->matches / $numresponses : 0;
            }
        }

        return ['question' => $question->content, 'results' => $return];
    }

    /**
     * Get the local score of the required question (item) in a feedback result.
     */
    protected function get_feedback_score($cmid, $qposition) {
        global $DB;

        try {
            $cm = $DB->get_record('course_modules', ['id' => $cmid]);
        } catch (moodle_exception $ex) {
            throw new moodle_exception("Feedback with cmid $cmid does not exist. Review settings.");
        }

        $instance = $DB->get_record('feedback', ['id' => $cm->instance]);
        $item = $DB->get_record('feedback_item', ['feedback' => $cm->instance, 'position' => $qposition]);
        if (!$item) {
            return ['question' => '', 'results' => null, 'error' => 'unknownitemposition'];
        }

        if (!in_array($item->typ, ['multichoicerated', 'multichoice'])) {
            return ['question' => $item->name, 'results' => null, 'error' => 'unsuportedtype'];
        }

        $itemchoices = explode('|', str_replace('<<<<<1', '', str_replace('r>>>>>', '', $item->presentation)));
        $choices = [];
        $rates = [];
        $maxrate = 0;
        $i = 1;
        foreach ($itemchoices as $ch) {
            if ($item->typ == 'multichoice') {
                $rate = 1;
                $label = trim($ch);
            } else if ($item->typ == 'multichoicerated') {
                list($rate, $label) = explode('####', trim($ch));
            }
            $choices[$i] = $label;
            $rates[$i] = $rate;
            if ($rate > $maxrate) {
                $maxrate = $rate;
            }
            $i++;
        }

        $allanswerssql = "
            SELECT
                userid,
                value
            FROM
                {feedback_value} fv,
                {feedback_completed} fc
            WHERE
                fc.id = fv.completed AND
                fc.feedback = ? AND
                fv.item = ?
        ";
        $params = [$cm->instance, $item->id];
        $results = $DB->get_records_sql($allanswerssql, $params);

        $optionsres = [];
        $allres = 0;
        $output = [];
        if ($results) {
            foreach ($results as $res) {
                $allres += $maxrate;
                $optionsres[$res->value] = @$optionsres[$res->value] + $rates[$res->value];
            }

            foreach ($optionsres as $valueid => $valuecount) {
                // $allres not null if we are here !
                $output[$this->clean($choices[$valueid])] = sprintf('%.2f', $valuecount / $allres);
            }
        }

        return ['question' => $item->name, 'results' => $output, 'error' => false];
    }

    /**
     * Prints graph using a plotter.
     * @param string $purpose
     * @param string $graphmode
     * @param array $values
     */
    protected function print_graph($purpose, $graphmode, $values) {
        static $htmlix = 0;

        $htmlid = 'satisfaction_grf_'.$htmlix;
        $str = '';

        $attributes = [];
        if (!empty($this->data->coloroverrides)) {
            $attributes['colors'] = explode(',', $this->data->coloroverrides);
            foreach ($attributes['colors'] as &$value) {
                $value = trim($value); // remove all spaces.
            }
        }

        if (!empty($this->data->legendorientation)) {
            $attributes['legendlocation'] = $this->data->legendorientation;
        }

        if (!empty($this->data->gwidth)) {
            $attributes['width'] = $this->data->gwidth;
        }

        if (!empty($this->data->gheight)) {
            $attributes['height'] = $this->data->gheight;
        }

        switch ($graphmode) {
            case 'donut': {
                $str .= local_vflibs_jqplot_simple_donut($values, $htmlid, '', $attributes);
            }
            break;
        }
        $htmlix++;
        return $str;
    }

    protected function clean($txt) {
        $txt = str_replace('à', 'a', $txt);
        $txt = str_replace('â', 'a', $txt);
        $txt = str_replace('é', 'e', $txt);
        $txt = str_replace('é', 'e', $txt);
        $txt = str_replace('ê', 'e', $txt);
        $txt = str_replace('ô', 'o', $txt);
        $txt = str_replace('ù', 'u', $txt);
        $txt = str_replace('û', 'u', $txt);
        $txt = str_replace('î', 'i', $txt);
        $txt = str_replace('ç', 'c', $txt);
        return $txt;
    }
}

