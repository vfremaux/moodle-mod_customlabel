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
 * Customlabel external functions and service definitions.
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux (www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

$functions = [

    'mod_customlabel_get_content' => [
        'classname' => 'mod_customlabel_external',
        'methodname' => 'get_content',
        'classpath' => 'mod/customlabel/externallib.php',
        'description' => 'Get the produced content of a course element',
        'type' => 'read',
        'capabilities' => 'moodle/course:view',
    ],

    'mod_customlabel_get_attribute' => [
        'classname' => 'mod_customlabel_external',
        'methodname' => 'get_attribute',
        'classpath' => 'mod/customlabel/externallib.php',
        'description' => 'Get a label attribute',
        'type' => 'read',
        'capabilities' => 'moodle/course:view',
    ],

    'mod_customlabel_set_attribute' => [
        'classname' => 'mod_customlabel_external',
        'methodname' => 'set_attribute',
        'classpath' => 'mod/customlabel/externallib.php',
        'description' => 'Set a label attribute without reprocessing content cache',
        'type' => 'write',
        'capabilities' => 'moodle/course:manageactivities',
    ],

    'mod_customlabel_refresh' => [
        'classname' => 'mod_customlabel_external',
        'methodname' => 'refresh',
        'classpath' => 'mod/customlabel/externallib.php',
        'description' => 'Refresh content of a single course element or a set of elements',
        'type' => 'write',
        'capabilities' => 'moodle/course:manageactivities',
    ],

    'mod_customlabel_get_mtd_domain' => [
        'classname' => 'mod_customlabel_external',
        'methodname' => 'get_mtd_domain',
        'classpath' => 'mod/customlabel/externallib.php',
        'description' => 'Provides the domain items for a metadata qualifier or classifier',
        'type' => 'read',
        'capabilities' => '',
    ],

];
