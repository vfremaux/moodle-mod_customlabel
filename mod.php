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
 * this page is a proxy to course/mod.php in order to setup
 * a potential customlabel type change in session memory so passing
 * thru the standard modedit.php path.
 */
require('../../config.php');

$labelclass = required_param('type', PARAM_TEXT);
$update = required_param('update', PARAM_INT);
$sectionreturn = required_param('sr', PARAM_INT);

require_login();
require_sesskey();

$SESSION->customlabel = new StdClass();
$SESSION->customlabel->update_type_change = $labelclass;
$redirect = new moodle_url('/course/modedit.php', array('update' => $update, 'sr' => $sectionreturn, 'sesskey' => sesskey(), 'type' => $labelclass));
redirect($redirect);