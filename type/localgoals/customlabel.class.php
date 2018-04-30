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

class customlabel_type_localgoals extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'localgoals';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'localgoals';
        $field->type = 'editor';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['localgoals'] = $field;
    }

    public function postprocess_data($course = null) {
        global $OUTPUT;

        $this->data->headerimage = $OUTPUT->image_url('thumb', 'customlabeltype_localgoals');
    }
}
