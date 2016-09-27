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
 * @package customlabel
 * @category mod
 */

require('../../../../../config.php');
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

$courseid = required_param('id', PARAM_INT);
$customlabelid = required_param('cid', PARAM_INT);

$isvisible = optional_param('isvisible', false, PARAM_BOOL);
$height = optional_param('height', false, PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid));
if (!$course) die;

if (!empty($isvisible)) {
    require_login($course);
}

if (!empty($height)) {
    $customlabel = $DB->get_record('customlabel', array('id' => $customlabelid));
    if ($customlabel->labelclass != 'verticalspacer') {
        die;
    }
    $customlabel->instance = $customlabelid;
    $instance = customlabel_load_class($customlabel);
    $instance->update_data('spacing', $height);
}
