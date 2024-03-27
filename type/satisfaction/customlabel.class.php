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
if (is_dir($CFG->dirroot.'/local/vflibs/chartjsplus')) {
    include_once($CFG->dirroot.'/local/vflibs/chartjsplus/chart_pie.php');
}

/**
 *
 */
class customlabel_type_satisfaction extends customlabel_type {

    public function __construct($data) {

        parent::__construct($data);
        $this->type = 'satisfaction';
        $this->fields = array();

        // Not true any more. Using chartjs
        // $this->usesjqplot = true;

        $field = new StdClass;
        $field->name = 'graphtitle';
        $field->type = 'textfield';
        $field->help = 'graphtitle';
        $field->size = 80;
        $this->fields['graphtitle'] = $field;

        $field = new StdClass;
        $field->name = 'legendorientation';
        $field->type = 'list';
        $field->options = ['left', 'top', 'right', 'bottom'];
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

    /**
     * Called y mod/customlabel after_restore to process remapping of specific pointers. Need
     * dig into specific data to remap what needs to be.
     * $param object $restorestep
     */
    public function after_restore(restore_customlabel_activity_structure_step $restorestep) {
        $newid = $restorestep->get_mappingid('course_modules', $this->data->activityscoresource);
        debug_trace("Remapping CMID ".$this->data->activityscoresource.' to '.$newid);
        $this->update_data('activityscoresource', $newid);
        debug_trace($this->data);
    }

    public function preprocess_data() {
        global $DB, $OUTPUT, $COURSE;

        if (empty($this->data->activityscoresource)) {
            $this->data->notconfigured = 1;
            $this->data->notconfigurednotification = $OUTPUT->notification(get_string('notconfigured', 'customlabeltype_satisfaction'));
            return;
        }

        $cm = $DB->get_record('course_modules', ['id' => $this->data->activityscoresource]);
        if (!$cm || $cm->course != $COURSE->id) {
            $this->data->notconfigured = 1;
            $this->data->notconfigurednotification = $OUTPUT->notification(get_string('sourcereferror', 'customlabeltype_satisfaction'));
            return;
        }
        $module = $DB->get_record('modules', ['id' => $cm->module]);

        $this->data->graphtitle = format_string($this->data->graphtitle);

        if ($module->name == 'questionnaire') {
            $satisfaction = $this->get_questionnaire_score($this->data->activityscoresource, $this->data->qposition);
            debug_trace("Getting results for {$this->data->activityscoresource}, {$this->data->qposition} ");
        } else {
            $satisfaction = $this->get_feedback_score($this->data->activityscoresource, $this->data->qposition);
        }

        if (!empty($satisfaction['error'])) {
            $this->data->question = strip_tags($satisfaction['question']);
            $this->data->satisfactiongraph = $OUTPUT->notification(get_string($satisfaction['error'], 'customlabeltype_satisfaction'));
            return;
        }

        $this->data->question = strip_tags($satisfaction['question']);
        if (!empty($satisfaction['results'])) {
            $this->data->satisfactiongraph = "<div style=\"height: {$this->data->gheight}px;width: {$this->data->gwidth}px\">";
            $this->data->satisfactiongraph .= $this->print_moodle_graph('satisfaction', 'donut', $satisfaction['results'], $this->data);
            $this->data->satisfactiongraph .= '</div>';
        } else {
            if ($this->data->legendorientation == 'left' || $this->data->legendorientation == 'right') {
                $emptygraphurl = $OUTPUT->image_url('empty_horiz', 'customlabeltype_satisfaction');
            } else {
                $emptygraphurl = $OUTPUT->image_url('empty_vert', 'customlabeltype_satisfaction');
            }
            $nodatastr = get_string('nodata', 'customlabeltype_satisfaction');
            $this->data->satisfactiongraph = '<img title="'.$nodatastr.'" alt="'.$nodatastr.'" src="'.$emptygraphurl.'" style="width: '.$this->data->gwidth.'px"/>';
        }
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
        if ($numresponses == 0) {
            return ['question' => $question->content, 'results' => null];
        }

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

            if ($allres == 0) {
                return ['question' => $item->name, 'results' => null, 'error' => false];
            }

            foreach ($optionsres as $valueid => $valuecount) {
                // $allres not null if we are here !
                $output[$this->clean($choices[$valueid])] = sprintf('%.2f', $valuecount / $allres);
            }
        }

        return ['question' => $item->name, 'results' => $output, 'error' => false];
    }

    /**
     * Print donuts using moodle internal chartjs
     */
    protected function print_moodle_graph($purpose, $graphmode, $values, $fulldata) {
        global $OUTPUT, $PAGE, $CFG;

        $colornum = count($values);

        $colors = [];
        $i = 0;
        if (!empty($this->data->coloroverrides)) {
            $colors = explode(',', $this->data->coloroverrides);
            foreach ($colors as &$c) {
                $c = trim($c); // remove all spaces.
                $i++;
            }
        }
        // Ensure we have never more colors then required by values.
        $colors = array_slice($colors, 0, $colornum);

        // fill missing colors by generating them randomly.
        for (; $i < $colornum; $i++) {
            $colors[] = $this->generate_color('full');
        }

        if (class_exists('\local_vflibs\chart_pie')) {
            $chart = new \local_vflibs\chart_pie();
            $chart->add_option('cutoutPercentage', 90);
            if (!empty($CFG->forced_plugin_settings['customlabeltype_satisfaction']['forcedpiesize'])) {
                $fulldata->gwidth = $CFG->forced_plugin_settings['customlabeltype_satisfaction']['forcedpiesize'];
                $fulldata->gheight = $CFG->forced_plugin_settings['customlabeltype_satisfaction']['forcedpiesize'];
            }
            $chart->add_option('width', $fulldata->gwidth);
            $chart->add_option('height', $fulldata->gheight);
        } else {
            $chart = new \core\chart_pie();
        }
        $chart->set_doughnut(true); // Calling set_doughnut(true) we display the chart as a doughnut.
        $serie1 = new core\chart_series('', array_values($values));
        $serie1->set_colors($colors);
        $chart->add_series($serie1);
        $chart->set_legend_options(['position' => $this->data->legendorientation]);
        $keys = array_keys($values);
        $chart->set_labels($keys);
        if (class_exists('\local_vflibs\chart_pie')) {
            $PAGE->requires->js_call_amd('local_vflibs/chart_builder');
            $PAGE->requires->js_call_amd('local_vflibs/chart_pie');
            $PAGE->requires->js_call_amd('local_vflibs/chart_base');
            $renderer = $PAGE->get_renderer('local_vflibs');
            return $renderer->render_chart_pie($chart);
        } else {
            // Use standard renderer
            return $OUTPUT->render($chart);
        }
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

    /**
     * Generates a color in full, dark or light tones.
     */
    function generate_color($tone = 'full') {
        if ($tone == 'full') {
            $red = rand(0, 255);
            $green = rand(0, 255);
            $blue = rand(0, 255);
        } else if ($tone = 'dark') {
            $red = rand(0, 127);
            $green = rand(0, 127);
            $blue = rand(0, 127);
        } else {
            $red = rand(128, 255);
            $green = rand(128, 255);
            $blue = rand(127, 255);
        }

        return '#'.dechex($red).dechex($green).dechex($blue);
    }
}

