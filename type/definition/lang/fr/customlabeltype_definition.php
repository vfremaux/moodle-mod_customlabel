<?php

$string['definition:view'] = 'Peut voir le contenu';
$string['definition:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Définition';
$string['typename'] = 'Définition';
$string['configtypename'] = 'Active le type Définition';
$string['definition'] = 'Définition';
$string['subdefsnum'] = 'Nombre de sous-définitions';
$string['subdef'] = 'Sous-définition';
$string['subdef0'] = 'Sous-définition 1';
$string['subdef1'] = 'Sous-définition 2';
$string['subdef2'] = 'Sous-définition 3';
$string['subdef3'] = 'Sous-définition 4';
$string['subdef4'] = 'Sous-définition 5';
$string['subdef5'] = 'Sous-définition 6';
$string['subdef6'] = 'Sous-définition 7';
$string['subdef7'] = 'Sous-définition 8';
$string['subdef7'] = 'Sous-définition 9';
$string['subdef9'] = 'Sous-définition 10';

$string['template'] = '
<table class="custombox-definition" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb definition" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption definition" width="98%">
        Définition
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
