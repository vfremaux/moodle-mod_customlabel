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
 * Version details.
 *
 * @package     mod_customlabel
 * @category    mod
 * @author      Valery Fremaux (valery.fremaux@gmeil.com)
 * @copyright   2008 onwards Valery Fremaux (valery.fremaux@gmeil.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version  = 2013041802; // The current module version (Date: YYYYMMDDXX)
$plugin->requires = 2015050500;
$plugin->component = 'mod_customlabel'; // Full name of the plugin (used for diagnostics)
$plugin->maturity = MATURITY_STABLE; // Maturity
$plugin->release = "2.9.0 (Build 2013041802)"; // Release

// Non moodle attributes.
$plugin->codeincrement = '2.9.0000';