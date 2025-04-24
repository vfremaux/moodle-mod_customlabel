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
 * Capabilities.
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux (www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [

    // Controls creation of customlabel.
    'mod/customlabel:addinstance' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => [
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],

    'mod/customlabel:fullaccess' => [

        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => [
            'manager' => CAP_ALLOW,
        ],
    ],

    'mod/customlabel:managemetadata' => [

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => [
            'manager' => CAP_ALLOW,
        ],
    ],
];

