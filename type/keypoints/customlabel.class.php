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
 * @package    customlabeltype_keypoints
 *
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

class customlabel_type_keypoints extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'keypoints';
        $this->fields = [];
        $this->directdisplay = true;

        $keypointnum = @$this->data->keypointnum;
        if (!empty($data->keypointnum)) {
            // What directly coming from form overrides.
            $keypointnum = $data->keypointnum;
        }

        $field = new StdClass;
        $field->name = 'keypointnum';
        $field->type = 'textfield';
        $field->size = 4;
        $field->default = 3;
        $this->fields['keypointnum'] = $field;

        for ($i = 0; $i < $keypointnum; $i++) {
            $field = new StdClass;
            $field->name = 'keypointitem'.$i;
            $field->type = 'editor';
            $field->itemid = $i;
            $field->size = 60;
            $this->fields['keypointitem'.$i] = $field;
        }
    }

    /**
     * Preprocesses template before getting options and additional inputs
     * from fields.
     */
    public function preprocess_data() {

        if ($this->instance->completion1enabled) {
            $keypointtpl->feedbackmark = true;
        }

        for ($i = 0; $i < $this->data->keypointnum; $i++) {
            $key = 'keypointitem'.$i;
            $keypointtpl = new StdClass;
            $keypointtpl->keypoint = @$this->data->$key;

            $this->data->keypoints[] = $keypointtpl;
        }
    }

    /**
     * Called from the module add_completion_rules @see mod/customlabel/lib.php
     * Add customized per type completion rules (up to 3)
     * @param object $mform the completion form
     */
    public static function add_completion_rules($mform) {

        $mform->addElement('checkbox', 'completion1enabled', '', get_string('completion1', 'customlabeltype_keypoints'));

        return ['completion1enabled'];
    }

    /**
     * Provides the complete value to match for each used completion.
     * @param int $completionix the completion index from 1 to 3.
     */
    public function complete_value($completionix) {

        $return = false;

        switch ($completionix) {
            case 1 : {
                $return = pow(2, $this->get_data('keypointnum')) - 1;
                break;
            }
            case 2 : {
                break;
            }
            case 3 : {
                break;
            }
        }

        return $return;
    }
}

