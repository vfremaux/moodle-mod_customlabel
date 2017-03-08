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

require_once ($CFG->dirroot."/mod/customlabel/type/customtype.class.php");

/**
*
*
*/

class customlabel_type_example extends customlabel_type{

    function __construct($data) {
        parent::__construct($data);
        $this->type = 'example';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'example';
        $field->type = 'editor';
        $field->itemid = 0;
        $field->rows = 20;
        $this->fields['example'] = $field;
    }
    
    function postprocess_data($course = null) {
        global $CFG;

        $this->data->headerimage = $CFG->wwwroot.'/mod/customlabel/type/example/pix/thumb.jpg';
    }
}
 
