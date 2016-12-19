<?php

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
$string['showpeople'] = 'Show the public selector';
$string['uselevels'] = 'Levels to use';
$string['statut'] = 'Course state';

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
