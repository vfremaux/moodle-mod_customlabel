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
 * This is a local class contextual translation file for field names and list options.
 * this file is automatically loaded by the /mod/customlabel/lang/xx_utf8/customlabel.php
 * module language file.
 *
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['NEWTYPE:view'] = 'Can view the content';
$string['NEWTYPE:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : NEW TYPE';
$string['typename'] = 'Customized label sample';
$string['configtypename'] = 'Enables subtype NEWTYPE';
$string['smalltext'] = 'Text sample';
$string['parag'] = 'Long text sample';
$string['list'] = 'Unique choice list sample';
$string['listmultiple'] = 'Multiple choice sample';
$string['opt1'] = 'Choice 1';
$string['opt2'] = 'Choice 2';
$string['opt3'] = 'Choice 3';
$string['lockedfield'] = 'Locked field';
$string['lockedsample'] = 'Only designated roles can modifiy the content of these fields';

$string['family'] = 'generic';

$string['template'] = '
<!-- This is a layout template for the custom type NEWTYPE -->
<!-- There should be a template for all used languages -->
<!-- Remind : Template must be UTF8 encoded -->

<!-- The first line calls the customization CSS -->
<link href="<%%customlabelcss%%>" rel="stylesheet" type="text/css" />
<div class="labelcontent">
<table class="customlabeldemo">
    <tr>
        <th colspan="2">
            <%%title%%>
        </th>
    <tr>
    <tr>
        <td class="param">
            Text example
        </td>
        <td class="value">
            <%%smalltext%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Long text example
        </td>
        <td class="value">
            <%%parag%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            One choice list
        </td>
        <td class="value">
            <%%list%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Mutiple choice list
        </td>
        <td class="value">
            <%%listmultiple%%>
        </td>
    </tr>
    <tr class="locked">
        <td class="param">
            Sample of field locked in editing for authorized roles
        </td>
        <td>
            <%%lockedfield%%>
        </td>
    </tr>
</table>
</div>';