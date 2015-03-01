<?php

$string['contactpoint:view'] = 'Peut voir le contenu';
$string['contactpoint:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Point de contact';
$string['typename'] = 'Point de contact';
$string['configtypename'] = 'Active le type Point de contact';
$string['contactpoint'] = 'Point de contact';
$string['instructions'] = 'Instructions';
$string['contacttype'] = 'Type de contact';
$string['any'] = 'Toute méthode';
$string['anywritten'] = 'Toute méthode écrite';
$string['mail'] = 'Mél';
$string['phone'] = 'Téléphone';
$string['onlinevocal'] = 'Com. en ligne';
$string['chat'] = 'Chat';
$string['meeting'] = 'Réunion virtuelle';
$string['facetoface'] = 'Face à face';

$string['template'] = '
<table class="contactpoint" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb contactpoint <%%contacttypeoption%%>" width="2%" rowspan="4">
    </td>
    <td class="custombox-header-caption contactpoint" width="98%" colspan="2">
        Point de contact...<br/>
        <span class="custombox-header-caption contacttype">Méthode : <%%contacttype%%></span>
    </td>
</tr>
<tr>
    <td class="custombox-content contactpoint" colspan="2">
        <%%instructions%%>
    </td>
</tr>
</table>';