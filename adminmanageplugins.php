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
 * Allows the admin to manage assignment plugins
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux <valery.fremaux@gmail.com> (www.activeProLearn.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/mod/customlabel/adminlib.php');

$context = context_system::instance();

require_login();
require_capability('mod/customlabel:managemetadata', $context);

// Create the class for this controller.
$pluginmanager = new customlabel_plugin_manager(required_param('subtype', PARAM_PLUGIN));

$PAGE->set_context(context_system::instance());

// Execute the controller.
$pluginmanager->execute(optional_param('action', null, PARAM_PLUGIN),
                        optional_param('plugin', null, PARAM_PLUGIN));
