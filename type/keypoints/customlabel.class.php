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

require_once ($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 *
 *
 */

class customlabel_type_keypoints extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'keypoints';
        $this->fields = array();

        $storeddata = json_decode(base64_decode(@$this->data->content));

        $keypointnum = (!empty($storeddata->keypointnum)) ? $storeddata->keypointnum : 3;

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

    public function preprocess_data($course = null) {
        global $CFG;

        $this->data->keypointslist = "<ul class=\"customlabel keypoints\">\n";
        for ($i = 0; $i < $this->data->keypointnum; $i++) {
            $key = 'keypointitem'.$i;
            $this->data->keypointslist .= (isset($this->data->$key)) ? '<li>'.$this->data->$key."</li>\n" : '';
        }
        $this->data->keypointslist .= "</ul>\n";
    }
}

