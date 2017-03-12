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

$string['authordata:view'] = 'Can view the content';
$string['authordata:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Author Information';
$string['typename'] = 'Author information';
$string['configtypename'] = 'Enable subtype Author information';
$string['author1'] = 'Author 1';
$string['author2'] = 'Author 2';
$string['author3'] = 'Author 3';
$string['thumb1'] = 'Thumb 1';
$string['thumb2'] = 'Thumb 2';
$string['thumb3'] = 'Thumb 3';
$string['tablecaption'] = 'Table caption';
$string['contributors'] = 'Contributors';
$string['institution'] = 'Institution';
$string['department'] = 'Department';
$string['showcontributors'] = 'Show contributors';
$string['showinstitution'] = 'Show institution';
$string['showdepartment'] = 'Show department';

$string['family'] = 'meta';

$string['template'] = '
<table class="custombox-authordata">
    <%if %%tablecaption%% %>
    <tr valign="top">
        <th class="custombox-title authordata" colspan="2">
            <%%tablecaption%%>
        </th>
    </tr>
    <%endif %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Author<%if %%author2%% %>s<%endif %> :
        </td>
        <td class="custombox-value authordata">
            <%if %%thumb3%% %>
            <img src="<%%thumb3%%>" title="<%%author3%%>" style="float:right" width="80"  height="120"/>
            <%endif %>
            <%if %%thumb2%% %>
            <img src="<%%thumb2%%>" title="<%%author2%%>" style="float:right;margin-right:10px"  width="80"  height="120" />
            <%endif %>
            <%if %%thumb1%% %>
            <img src="<%%thumb1%%>" title="<%%author1%%>" style="float:right;margin-right:10px"  width="80"  height="120" />
            <%endif %>
            <%%author1%%>
            <%%author2%%>
            <%%author3%%>
        </td>
    </tr>
    <%if %%showinstitution%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Institution:
        </td>
        <td class="custombox-value authordata">
            <%%institution%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showdepartment%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Department:
        </td>
        <td class="custombox-value authordata">
            <%%department%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showcontributors%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Contributors:
        </td>
        <td class="custombox-value authordata">
            <%%contributors%%>
        </td>
    </tr>
    <%endif %>
</table>';