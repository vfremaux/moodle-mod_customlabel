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
 * Local library.
 *
 * @package    customlabeltype_worktodo
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

/**
 * Get the course modules that can be linked as work to do
 */
function customlabel_get_candidate_modules() {
    global $COURSE, $CFG;

    if (!empty($CFG->upgraderunning)) {
        return;
    }

    $modinfo = get_fast_modinfo($COURSE);
    $modules = ['0' => get_string('unassigned', 'customlabeltype_worktodo')];

    foreach ($modinfo->get_cms() as $cminfo) {
        if (!$cminfo->visible) {
            continue;
        }
        if (preg_match('/label$/', $cminfo->modname)) {
            continue;
        }
        include_once($CFG->dirroot.'/mod/'.$cminfo->modname.'/lib.php');
        $supportf = $cminfo->modname.'_supports';
        if (!$supportf(FEATURE_GRADE_HAS_GRADE) && !$supportf(FEATURE_GRADE_OUTCOMES)) {
            // Module seems it is not gradable so not a worktodo module.
            continue;
        }
        $modules[$cminfo->module] = $cminfo->name;
    }

    return $modules;
}
