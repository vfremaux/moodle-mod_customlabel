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

require_once($CFG->dirroot.'/mod/customlabel/lib.php');

class mod_customlabel_flexpage extends block_flexpagemod_lib_mod {

    public function module_block_setup() {
        global $DB;

        $cm = $this->get_cm();
        $customlabel = $DB->get_record('customlabel', array('id' => $cm->instance));
        $instance = customlabel_load_class($customlabel, $customlabel->labelclass);
        $block = null;
        $context = context_module::instance($cm->id);
        if ($customlabel && has_capability('customlabeltype/'.$instance->labelclass.':view', $context)) {
            $this->append_content($instance->get_content());
        }
    }
}