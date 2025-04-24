<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.
// This file is NOT part of Moodle - http://moodle.org/
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
 * Web service for customlabeltype_localdokuwikicontent
 * @package    customlabeltype_localdokuwikicontent
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2013 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

$functions = [

    'customlabeltype_localdokuwikicontent_get_page' => [
            'classname'   => 'customlabeltype_localdokuwikicontent_external',
            'methodname'  => 'get_page',
            'classpath'   => 'mod/customlabel/type/localdokuwikicontent/externallib.php',
            'testclientpath' => 'mod/customlabel/type/localdokuwikicontent/test/testclient_forms.php',
            'description' => 'Get page content of a local dokuwiki',
            'type'        => 'read',
    ],
];

$services = [
    'localdokuwikicontent' => [
        'functions' => ['customlabeltype_localdokuwikicontent_get_page'], // Web service function names.
        'restrictedusers' => 1,
        'enabled' => 0, // Used only when installing the services.
    ],
];
