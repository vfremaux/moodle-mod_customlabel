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
require_once($CFG->dirroot.'/mod/customlabel/type/customtype_heading.trait.php');

class customlabel_type_sequenceheading extends customlabel_type {

    public $localthumb;

    use customlabel_trait_heading;

    public function __construct($data) {
        global $CFG, $PAGE, $OUTPUT;

        parent::__construct($data);
        $this->type = 'sequenceheading';
        $this->fields = array();

        $this->standard_name_fields();

        // Test if theme has an alternative strategy to illustrate course modules.
        $this->localthumb = true;
        $themename = $this->hard_fetch_theme_name(); // Cannot use $PAGE->theme here.
        include_once($CFG->dirroot.'/theme/'.$themename.'/lib.php');
        $modthumbavailabilityfunc = 'theme_'.$themename.'_supports_feature';
        if (function_exists($modthumbavailabilityfunc)) {
            if ($modthumbavailabilityfunc('course/modthumbs')) {
                $this->localthumb = false;
            }
        }

        if ($this->localthumb) {
            $field = new StdClass();
            $field->name = 'image';
            $field->type = 'filepicker';
            $field->destination = 'url';
            $field->default = '';
            if ($PAGE->state >= moodle_page::STATE_IN_BODY) {
                if (!isloggedin()) {
                    // Give a context to the page if missing. f.e when invoking pluginfile.
                    $PAGE->set_context(context_system::instance());
                }
                if (!is_file($CFG->dirroot.'/theme/'.$PAGE->theme->name.'/pix/customlabel_icons/defaultsequenceheading.png')) {
                    $field->default = $OUTPUT->image_url('defaultsequenceheading', 'customlabeltype_sequenceheading');
                } else {
                    $field->default = $CFG->wwwroot.'/theme/'.$PAGE->theme->name.'/pix/customlabel_icons/defaultsequenceheading.png';
                }
            } else {
                if ($PAGE->state >= moodle_page::STATE_IN_BODY) {
                    $field->default = $OUTPUT->image_url('defaultsequenceheading', 'customlabeltype_sequenceheading');
                }
            }
            $this->fields['image'] = $field;

            $this->standard_icon_fields();

            $field = new StdClass();
            $field->name = 'verticalalign';
            $field->type = 'list';
            $field->options = array('top', 'middle', 'bottom');
            $field->default = 'top';
            $this->fields['verticalalign'] = $field;
        }
    }

    /**
     * If exists, this method can process local alternative values for
     * realizing the template, after all standard translations have been performed.
     * Type information structure and application context dependant.
     */
    public function postprocess_data($course = null) {

        // Get virtual fields from course title.
        if ($this->localthumb) {
            $storedimage = $this->get_file_url('image');
            $this->data->imageurl = (!empty($storedimage)) ? $storedimage : $this->fields['image']->default;
            $this->data->valignclass = '';
            if (!empty($this->data->verticalalignoption)) {
                $this->data->valignclass = "aligned-".$this->data->verticalalignoption;
            }
            if (empty($this->data->imagepositionoption)) {
                $this->data->imagepositionoption = 'left';
            }
            $this->data->contentpadding = '';
            if ($this->data->imageurl) {
                $this->data->contentpadding = 'padding-left:15px;';
            }
            if ($this->data->imagepositionoption == 'left') {
                $this->data->toleft = true;
            } else if ($this->data->imagepositionoption == 'right') {
                $this->data->toright = true;
            }

            if (!empty($this->data->imagewidth)) {
                if (is_numeric($this->data->imagewidth)) {
                    $this->data->imagewidth.= 'px';
                }
            }
        }
    }
}