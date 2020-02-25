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
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 *
 *
 */

class customlabel_type_stealthactivity extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'stealthactivity';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'stealthmodule';
        $field->type = 'datasource';
        $field->source = 'function';
        $field->file = 'mod/customlabel/locallib.php';
        $field->function = 'customlabel_get_stealth_cms';
        $this->fields['stealthmodule'] = $field;

        $field = new StdClass;
        $field->name = 'thumbnail';
        $field->type = 'filepicker';
        $field->acceptedtypes = ['.jpg', '.png', '.gif', '.svg'];
        $field->itemid = 0;
        $this->fields['thumbnail'] = $field;

        $field = new StdClass;
        $field->name = 'layout';
        $field->type = 'list';
        $field->options = ['singleimage', 'singleimageanddescription', 'thumbtitleanddescription', 'thumbandtitle'];
        $this->fields['layout'] = $field;
    }

    public function postprocess_data($course = null) {
        global $OUTPUT, $COURSE, $DB, $USER, $PAGE;

        $modinfo = get_fast_modinfo($COURSE);

        $storedimage = $this->get_file_url('thumbnail');

        if (empty($this->data->stealthmoduleoption)) {
            $this->data->notyetconfigured = true;
            return;
        }

        try {
            $cminfo = $modinfo->get_cm($this->data->stealthmoduleoption);
            $this->data->thumbnail = (!empty($storedimage)) ? $storedimage : $cminfo->get_icon_url();

            if ($description = $DB->get_field($cminfo->modname, 'intro', ['id' => $cminfo->instance])) {
                $this->data->description = format_text($description);
            }

            $this->data->title = format_string($cminfo->name);
            // $this->data->moduleurl = new moodle_url('/mod/'.$cminfo->modname.'/view.php', ['id' => $cminfo->id]);
            $this->data->moduleurl = $cminfo->url;
            $this->data->onclick = $cminfo->onclick;

            $courserenderer = $PAGE->get_renderer('course');
            $this->data->cmlink = $courserenderer->course_section_cm_name($cminfo, array());

            if (empty($this->data->layoutoption)) {
                $this->data->layoutoption = 'thumbandtitle';
            }
            $attrname = $this->data->layoutoption;
            $this->data->$attrname = true;

            $thiscm = $modinfo->get_cm($this->cmid);
            // check and synchronise completion state.
            $completion = new completion_info($COURSE);
            if ($completion->is_enabled($cminfo)) {
                $params = ['userid' => $USER->id, 'coursemoduleid' => $cminfo->id];
                if ($completionrec = $DB->get_record('course_modules_completion', $params)) {
                    $completion->update_state($thiscm, $completionrec->completionstate);
                } else {
                    $completion->update_state($thiscm, COMPLETION_INCOMPLETE);
                }
            }
        } catch (Exception $e) {
            $this->data->brokenmodule = true;
        }
    }

    protected function course_section_cm_classes(cm_info $mod) {
        $linkclasses = '';
        $textclasses = '';
        if ($mod->uservisible) {
            $conditionalhidden = $this->is_cm_conditionally_hidden($mod);
            $accessiblebutdim = (!$mod->visible || $conditionalhidden) &&
                has_capability('moodle/course:viewhiddenactivities', $mod->context);
            if ($accessiblebutdim) {
                $linkclasses .= ' dimmed';
                $textclasses .= ' dimmed_text';
                if ($conditionalhidden) {
                    $linkclasses .= ' conditionalhidden';
                    $textclasses .= ' conditionalhidden';
                }
            }
            if ($mod->is_stealth()) {
                // Stealth activity is the one that is not visible on course page.
                // It still may be displayed to the users who can manage it.
                $linkclasses .= ' stealth';
                $textclasses .= ' stealth';
            }
        } else {
            $linkclasses .= ' dimmed';
            $textclasses .= ' dimmed dimmed_text';
        }
        return array($linkclasses, $textclasses);
    }

    /**
     * Checks if course module has any conditions that may make it unavailable for
     * all or some of the students
     *
     * This function is internal and is only used to create CSS classes for the module name/text
     *
     * @param cm_info $mod
     * @return bool
     */
    protected function is_cm_conditionally_hidden(cm_info $mod) {
        global $CFG;
        $conditionalhidden = false;
        if (!empty($CFG->enableavailability)) {
            $info = new \core_availability\info_module($mod);
            $conditionalhidden = !$info->is_available_for_all();
        }
        return $conditionalhidden;
    }

}

