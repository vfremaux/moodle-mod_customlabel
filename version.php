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
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux (valery.fremaux@gmail.com)
 * @copyright  2013 onwards Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version  = 2019101800; // The current module version (Date: YYYYMMDDXX).
$plugin->requires = 2018112800;
$plugin->component = 'mod_customlabel'; // Full name of the plugin (used for diagnostics).
$plugin->release = '3.6.0 (Build 2019101800)';
$plugin->maturity = MATURITY_STABLE;

// Non moodle attributes.
$plugin->codeincrement = '3.6.0014';
$plugin->privacy = 'dualrelease';
$plugin->prolocations = array(
    'type/coursedata',
    'type/pedagogicadvice',
    'type/pedagogicadvice',
    'type/authordata',
    'type/authornote',
    'type/courseclassifier',
    'type/localdokuwikicontent',
    'type/cssadditions',
    'type/genericpractices',
    'type/genericgoals',
    'type/processgoals',
    'type/processpractices',
);