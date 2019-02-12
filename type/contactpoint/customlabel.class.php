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

class customlabel_type_contactpoint extends customlabel_type {

    public function __construct($data) {
        parent::__construct($data);
        $this->type = 'contactpoint';
        $this->fields = array();

        $field = new StdClass();
        $field->name = 'instructions';
        $field->type = 'editor';
        $field->rows = 20;
        $field->itemid = 0;
        $this->fields['instructions'] = $field;

        $field = new Stdclass();
        $field->name = 'contacttype';
        $field->type = 'list';
        $field->options = array('any', 'anywritten', 'mail', 'phone', 'onlinevocal', 'chat', 'meeting', 'facetoface');
        $this->fields['contacttype'] = $field;
    }

    public function postprocess_icon() {
        global $OUTPUT;

        $iconurl = $OUTPUT->image_url('icon_'.$this->data->contacttypeoption, 'customlabeltype_'.$this->type)->out();
        $this->data->icon = $iconurl;
        $this->data->iconurl = $iconurl;
    }
}
