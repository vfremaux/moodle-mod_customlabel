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
 * Special page format adapter.
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @see blocks/page_module/block_pagge_module.php
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/lib.php');

/**
 * A local dedicated wr apper for page format.
 */
class mod_customlabel_flexpage extends block_flexpagemod_lib_mod {

    /**
     * Setup page module block for page format.
     */
    public function module_block_setup() {
        global $DB;

        $cm = $this->get_cm();
        $customlabel = $DB->get_record('customlabel', ['id' => $cm->instance]);
        $customlabel->coursemodule = $cm->id;
        $instance = customlabel_load_class($customlabel, $customlabel->labelclass);
        $block = null;
        $context = context_module::instance($cm->id);
        if ($customlabel && has_capability('customlabeltype/'.$instance->labelclass.':view', $context)) {
            $this->append_content($instance->get_content());
        }
    }
}
