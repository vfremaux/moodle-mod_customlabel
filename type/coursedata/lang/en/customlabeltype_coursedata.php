<?php

/**
 * This is a local class contextual translation file for field names and 
 * list options.
 * this file is automatically loaded by the 
 * /mod/customlabel/lang/xx_utf8/customlabel.php
 * module language file.
 */

$string['coursedata:view'] = 'Can view the content';
$string['coursedata:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Course Information';
$string['typename'] = 'Course information';
$string['configtypename'] = 'Enable subtype Course information';
$string['goals'] = 'Goals ';
$string['concepts'] = 'Key concepts ';
$string['teachingorganization'] = 'Pedagogic organisation';
$string['objectives'] = 'Learner\'s objectives';
$string['duration'] = 'Duration ';
$string['prerequisites'] = 'Prerequisites';
$string['learningmethod'] = 'Learning method ';
$string['tablecaption'] = 'Table caption';
$string['followers'] = 'Following courses ';
$string['showgoals'] = 'Show Goals ';
$string['showconcepts'] = 'Show Key concepts ';
$string['showteachingorganization'] = 'Show Pedagogic organisation';
$string['showobjectives'] = 'Show Learner\'s objectives';
$string['showduration'] = 'Show Duration ';
$string['showprerequisites'] = 'Show Prerequisites';
$string['showlearningmethod'] = 'Show Learning method ';
$string['showfollowers'] = 'Show Following courses ';
$string['showtarget'] = 'Show Target ';
$string['target'] = 'Target ';
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