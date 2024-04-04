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

class customlabel_type_coursedata extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'coursedata';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'tablecaption';
        $field->type = 'textfield';
        $field->default = get_string('defaulttablecaption', 'customlabeltype_coursedata');
        $this->fields['tablecaption'] = $field;

        $field = new StdClass;
        $field->name = 'showtarget';
        $field->type = 'choiceyesno';
        $this->fields['showtarget'] = $field;

        $field = new StdClass;
        $field->name = 'target';
        $field->type = 'editor';
        $field->itemid = 0;
        $this->fields['target'] = $field;

        $field = new StdClass;
        $field->name = 'showgoals';
        $field->type = 'choiceyesno';
        $this->fields['showgoals'] = $field;

        $field = new StdClass;
        $field->name = 'goals';
        $field->type = 'editor';
        $field->itemid = 1;
        $this->fields['goals'] = $field;

        $field = new StdClass;
        $field->name = 'showobjectives';
        $field->type = 'choiceyesno';
        $this->fields['showobjectives'] = $field;

        $field = new StdClass;
        $field->name = 'objectives';
        $field->type = 'editor';
        $field->itemid = 2;
        $this->fields['objectives'] = $field;

        $field = new StdClass;
        $field->name = 'showconcepts';
        $field->type = 'choiceyesno';
        $this->fields['showconcepts'] = $field;

        $field = new StdClass;
        $field->name = 'concepts';
        $field->type = 'editor';
        $field->itemid = 3;
        $field->size = 80;
        $this->fields['concepts'] = $field;

        $field = new StdClass;
        $field->name = 'showduration';
        $field->type = 'choiceyesno';
        $this->fields['showduration'] = $field;

        $field = new StdClass;
        $field->name = 'duration';
        $field->type = 'textfield';
        $field->size = 80;
        $this->fields['duration'] = $field;

        $field = new StdClass;
        $field->name = 'showteachingorganization';
        $field->type = 'choiceyesno';
        $this->fields['showteachingorganization'] = $field;

        $field = new StdClass;
        $field->name = 'teachingorganization';
        $field->type = 'editor';
        $field->itemid = 4;
        $field->size = 80;
        $this->fields['teachingorganization'] = $field;

        $field = new StdClass;
        $field->name = 'showprerequisites';
        $field->type = 'choiceyesno';
        $this->fields['showprerequisites'] = $field;

        $field = new StdClass;
        $field->name = 'prerequisites';
        $field->type = 'editor';
        $field->itemid = 5;
        $this->fields['prerequisites'] = $field;

        $field = new StdClass;
        $field->name = 'showevaluation';
        $field->type = 'choiceyesno';
        $this->fields['showevaluation'] = $field;

        $field = new StdClass;
        $field->name = 'evaluation';
        $field->type = 'editor';
        $field->itemid = 6;
        $this->fields['evaluation'] = $field;

        $field = new StdClass;
        $field->name = 'showoutcomes';
        $field->type = 'choiceyesno';
        $this->fields['showoutcomes'] = $field;

        $field = new StdClass;
        $field->name = 'outcomes';
        $field->type = 'editor';
        $field->itemid = 7;
        $this->fields['outcomes'] = $field;

        $field = new StdClass;
        $field->name = 'showfollowers';
        $field->type = 'choiceyesno';
        $this->fields['showfollowers'] = $field;

        $field = new StdClass;
        $field->name = 'followers';
        $field->type = 'editor';
        $field->itemid = 6;
        $this->fields['followers'] = $field;

        $field = new StdClass;
        $field->name = 'leftcolumnratio';
        $field->type = 'textfield';
        $field->default = '30%';
        $this->fields['leftcolumnratio'] = $field;
        
        $field = new StdClass;
        $field->name = 'showheadteacher';
        $field->type = 'choiceyesno';
        $this->fields['showheadteacher'] = $field;

        $field = new StdClass;
        $field->name = 'headteacher';
        $field->type = 'textfield';
        $field->size = '60';
        $this->fields['headteacher'] = $field;

        $field = new StdClass;
        $field->name = 'showlastupdate';
        $field->type = 'choiceyesno';
        $this->fields['showlastupdate'] = $field;

        $field = new StdClass;
        $field->name = 'lastupdate';
        $field->type = 'textfield';
        $field->size = '15';
        $this->fields['lastupdate'] = $field;
    }

    /**
     *
     *
     */
    public function preprocess_data($course = null) {
        global $COURSE;

        if (preg_match('/%COURSEDESC%/', @$this->data->objectives) || preg_match('/\\{\\{COURSEDESC\\}\\}/', @$this->data->objectives)) {
            $coursesummary = format_text($COURSE->summary, $COURSE->summaryformat);
            $this->data->objectives = str_replace('%COURSEDESC%', $coursesummary, $this->data->objectives);
        }
    }

    public function postprocess_data($course = null) {
        $leftratio = 0 + (int) str_replace('%', '', $this->data->leftcolumnratio ?? '30%');
        $this->data->rightcolumnratio = 100 - $leftratio;
        $this->data->rightcolumnratio .= '%';
    }
}

