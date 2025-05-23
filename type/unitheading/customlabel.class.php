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
 * Main type implementation
 *
 * @package    customlabeltype_unitheading
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');
require_once($CFG->dirroot.'/mod/customlabel/type/customtype_heading.trait.php');

/**
 * Main subtype implementation.
 */
class customlabel_type_unitheading extends customlabel_type {

    use customlabel_trait_heading;

    /**
     * Constructor
     * @param object $data
     */
    public function __construct($data) {
        global $CFG, $PAGE, $OUTPUT;

        parent::__construct($data);
        $this->type = 'unitheading';
        $this->fields = [];

        $this->standard_name_fields();

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
            if (!is_file($CFG->dirroot.'/theme/'.$PAGE->theme->name.'/pix/customlabel_icons/defaultunitheading.png')) {
                $field->default = $OUTPUT->image_url('defaultunitheading', 'customlabeltype_unitheading');
            } else {
                $field->default = $CFG->wwwroot.'/theme/'.$PAGE->theme->name.'/pix/customlabel_icons/defaultunitheading.png';
            }
        } else {
            if ($PAGE->state >= moodle_page::STATE_IN_BODY) {
                $field->default = $OUTPUT->image_url('defaultunitheading', 'customlabeltype_unitheading');
            }
        }
        $this->fields['image'] = $field;

        $this->standard_icon_fields();

    }

    /**
     * If exists, this method can process local alternative values for
     * realizing the template, after all standard translations have been performed.
     * Type information structure and application context dependant.
     * @param object $course
     */
    public function postprocess_data($course = null) {

        // Get virtual fields from course title.
        $storedimage = $this->get_file_url('image');
        $this->data->imageurl = $storedimage;
        if (empty($this->data->imagewidth)) {
            $this->data->imagewidth = "20%";
        }
        if (empty($this->data->imagepositionoption)) {
            $this->data->imagepositionoption = 'none';
            if (!empty($this->data->imageurl)) {
                $this->data->imagepositionoption = 'left';
            }
        }
        if ($this->data->imagepositionoption == 'left') {
            $this->data->toleft = true;
            $this->data->contentpadding = "padding-left:2em";
        } else if ($this->data->imagepositionoption == 'right') {
            $this->data->toright = true;
            $this->data->contentpadding = "padding-right:2em";
        }
    }
}
