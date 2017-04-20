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

$string['coursedata:view'] = 'Can view the content';
$string['coursedata:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Course Information';
$string['typename'] = 'Course information';
$string['configtypename'] = 'Enable subtype Course information';
$string['goals'] = 'Goals';
$string['concepts'] = 'Key concepts';
$string['teachingorganization'] = 'Pedagogic organisation';
$string['objectives'] = 'Learner\'s objectives';
$string['duration'] = 'Duration ';
$string['prerequisites'] = 'Prerequisites';
$string['learningmethod'] = 'Learning method';
$string['tablecaption'] = 'Table caption';
$string['followers'] = 'Following courses';
$string['showgoals'] = 'Show Goals ';
$string['showconcepts'] = 'Show Key concepts';
$string['showteachingorganization'] = 'Show Pedagogic organisation';
$string['showobjectives'] = 'Show Learner\'s objectives';
$string['showduration'] = 'Show Duration';
$string['showprerequisites'] = 'Show Prerequisites';
$string['showlearningmethod'] = 'Show Learning method';
$string['showfollowers'] = 'Show Following courses ';
$string['showtarget'] = 'Show Target';
$string['target'] = 'Target';
$string['leftcolumnratio'] = 'Left column ratio';

$string['family'] = 'meta';

$string['template'] = '
<table class="custombox-coursedata">
    <%if %%tablecaption%% %>
    <tr valign="top">
        <th class="custombox-title coursedata" colspan="2">
            <%%tablecaption%%>
        </th>
    </tr>
    <%endif %>
    <%if %%showidnumber%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Training ID:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%idnumber%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showtarget%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            People concerned:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%target%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showgoals%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Goals:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%goals%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showobjectives%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Acquisitions:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%objectives%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showconcepts%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Concepts:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%concepts%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showduration%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Duration:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%duration%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showteachingorganization%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Teaching organization:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%teachingorganization%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showprerequisites%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Prerequisites:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%prerequisites%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showfollowers%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Following courses:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%followers%%>
        </td>
    </tr>
    <%endif %>
</table>';