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

$string['courseclassifier:view'] = 'Can view the content';
$string['courseclassifier:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Course classifier';
$string['courseclassifier'] = 'Course classifier';
$string['tablecaption'] = 'Table caption';
$string['typename'] = 'Course classifier';
$string['configtypename'] = 'Enable subtype Course Classifier';
$string['level0'] = 'Classification level 1';
$string['level1'] = 'Classification level 2';
$string['level2'] = 'Classification level 3';
$string['people'] = 'People';
$string['fonction'] = 'Function';
$string['showpeople'] = 'Show the public selector';
$string['uselevels'] = 'Levels to use';
$string['status'] = 'Course state';

$string['family'] = 'special';

$string['template'] = '
<table class="custombox-courseclassifier">
    <tr valign="top">
        <th class="custombox-title courseclassifier" colspan="2">
            <%%tablecaption%%>
        </th>
    </tr>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Classifier Level 1:
        </td>
        <td class="custombox-value courseclassifier">
            <%%level0%%>
        </td>
    </tr>
    <%if %%uselevels >= 2%% %>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Classifier level 2:
        </td>
        <td class="custombox-value courseclassifier">
            <%%level1%%>
        </td>
    </tr>
    <%endif %>
    <%if %%uselevels >= 3%% %>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Classifier level3:
        </td>
        <td class="custombox-value courseclassifier">
            <%%level2%%>
        </td>
    </tr>
    <%endif %>
</table>
<%if %%classifiers%% %>
<table class="custombox-courseclassifier other">
    <tr valign="top">
        <th class="custombox-title courseclassifier" colspan="2">
            Other classifying information
        </th>
    </tr>
    <%%classifierrows%%>
</table>
<%endif %>';

$string['classifierrow'] = '
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            {$a->label}:
        </td>
        <td class="custombox-value courseclassifier">
            {$a->values}
        </td>
    </tr>
';
