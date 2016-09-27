<?php

$string['definition:view'] = 'Can view the content';
$string['definition:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Definition';
$string['typename'] = 'Definition';
$string['configtypename'] = 'Enable subtype Definition';
$string['definition'] = 'Definition text';
$string['subdefsnum'] = 'Number of sub definitions';
$string['subdef'] = 'Subdefinition';
$string['subdef0'] = 'Subdefinition 1';
$string['subdef1'] = 'Subdefinition 2';
$string['subdef2'] = 'Subdefinition 3';
$string['subdef3'] = 'Subdefinition 4';
$string['subdef4'] = 'Subdefinition 5';
$string['subdef5'] = 'Subdefinition 6';
$string['subdef6'] = 'Subdefinition 7';
$string['subdef7'] = 'Subdefinition 8';
$string['subdef7'] = 'Subdefinition 9';
$string['subdef9'] = 'Subdefinition 10';

$string['family'] = 'pedagogic';

$string['template'] = '
<table class="custombox-definition" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb definition" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption definition" width="98%">
        Definition
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content definition">
        <%%definition%%>
    </td>
</tr>
<%if %%hassubdeflist%% %>
<tr valign="top">
    <td class="custombox-foo definition" width="2%"></td>
    <td class="custombox-subdefinitions definition">
        <%%subdeflist%%>
    </td>
</tr>
<%endif %>
</table>';
