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

/**
 *
 *
 */

class customlabel_type_learningindicators extends customlabel_type {

    protected $isltcdriven = false;

    public function __construct($data) {
        global $CFG, $COURSE;

        if (is_dir($CFG->dirroot.'/mod/learningtimecheck')) {
            include_once($CFG->dirroot.'/mod/learningtimecheck/xlib.php');
            if (learningtimecheck_course_has_ltc_tracking($COURSE->id)) {
                $this->isltcdriven = true;
            }
        }

        parent::__construct($data);
        $this->type = 'learningindicators';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'showprogress';
        $field->type = 'choiceyesno';
        $this->fields['showprogress'] = $field;

        if ($this->isltcdriven) {
            $field = new StdClass;
            $field->name = 'progresssource';
            $field->type = 'list';
            $field->options = ['standard', 'ltc'];
            $this->fields['progresssource'] = $field;
        }

        $field = new StdClass;
        $field->name = 'showusetime';
        $field->type = 'choiceyesno';
        $this->fields['showusetime'] = $field;

        $field = new StdClass;
        $field->name = 'showscore';
        $field->type = 'choiceyesno';
        $this->fields['showscore'] = $field;

        $field = new StdClass;
        $field->name = 'scoresource';
        $field->type = 'list';
        $field->options = ['course', 'activity'];
        $this->fields['scoresource'] = $field;

        $field = new StdClass;
        $field->name = 'activityscoresource';
        $field->type = 'datasource';
        $field->source = 'function';
        $field->file = 'mod/customlabel/type/learningindicators/locallib.php';
        $field->function = 'customlabeltype_learningindicators_get_cms';
        $this->fields['activityscoresource'] = $field;
    }

    public function preprocess_data() {
        if (empty($this->data->progresssource)) {
            $this->data->progresssource = 'standard';
        }

        if (!empty($this->data->showprogress)) {
            if ($this->data->progresssource == 'standard') {
                $progress = $this->get_standard_progress();
            } else {
                $progress = $this->get_ltc_progress();
            }
            $this->data->progressgraph = $this->print_graph('progress', 'donut', $progress);
        }

        if (!empty($this->data->showusetime)) {
            $usetime = $this->get_use_time();
            $this->data->usetimegraph = $this->print_graph('usetime', 'time', $usetime);
        }

        if (!empty($this->data->showscore)) {
            if ($this->data->scoresource == 'course') {
                $score = $this->get_course_score();
                // course score is supposed to be a 0-100% value.
            } else {
                $score = $this->get_activity_score();
                // activity score is supposed to be a 0-100% value.
            }
            $this->data->scoregraph = $this->print_graph('score', 'donut', $score);
        }
    }

    /**
     * Get score for current course/current user.
     */
    protected function get_course_score() {
        global $COURSE, $USER;

        $score = 0;

        return $score;
    }

    /**
     * Get the score of a deignated course module for current user.
     */
    protected function get_activity_score() {
        global $USER;
    }

    /**
     * Get the standard completion progress for current course/current user.
     */
    protected function get_standard_progress() {
        global $COURSE, $USER;
    }

    /**
     * Get the LTC completion progress for current course/current user.
     */
    protected function get_ltc_progress() {
        global $USER, $CFG;

        include_once($CFG->dirroot.'/mod/learningtimecheck/xlib.php');

    }

    protected function get_use_time() {
        global $USER, $CFG;

        include_once($CFG->dirroot.'/blocks/use_stats/xlib.php');
    }

    protected function get_ltc_total_time() {
        global $USER, $CFG;

        include_once($CFG->dirroot.'/mod/learningtimecheck/xlib.php');
    }

    protected function print_graph($purpose, $graphmode, $value) {

        $str = '';

        switch ($graphmode) {
            case 'donut': {
                
            }
            break;

            case 'time': {
                if (empty($value)) {
                    $value = '0 sec';
                }
                $str = '<div class="learning-indicators-time">'.$value.'</div>';
            }
        }

        return $str;
    }
}

