<?php

/**
* This is a local class contextual translation file for field names and 
* list options.
* this file is automatically loaded by the 
* /mod/customlabel/lang/xx_utf8/customlabel.php
* module language file.
*
*/
$string['NEWTYPE:view'] = 'Peut voir le contenu';
$string['NEWTYPE:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : NEW TYPE';
$string['typename'] = 'Prototype de type de label personnalisé ';
$string['configtypename'] = 'Active le type NEWTYPE';
$string['smalltext'] = 'Exemple de texte ';
$string['parag'] = 'Exemple de texte long ';
$string['list'] = 'Exemple de liste à choix unique ';
$string['listmultiple'] = 'Exemple de liste à choix multiple ';
$string['opt1'] = 'Option 1';
$string['opt2'] = 'Option 2';
$string['opt3'] = 'Option 3';
$string['lockedfield'] = 'Champ à autorisation';
$string['lockedsample'] = 'Le contenu de ce champ n\'est modifiable que par certains rôles ';

$string['template'] = '
<!-- This is a layout template for the custom type NEWTYPE -->
<!-- There should be a template for all used languages -->
<!-- Remind : Template must be UTF8 encoded -->

<!-- The first line calls the customization CSS -->
<div class="labelcontent">
<table class="customlabeldemo">
    <tr>
        <th colspan="2" class="title">
            <%%title%%>
        </th>
    <tr>
    <tr>
        <td class="param">
            Exemple de texte
        </td>
        <td class="value">
            <%%smalltext%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Exemple de texte long
        </td>
        <td class="value">
            <%%parag%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Exemple de liste choix unique
        </td>
        <td class="value">
            <%%list%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Exemple de liste choix multiple
        </td>
        <td class="value">
            <%%listmultiple%%>
        </td>
    </tr>
    <tr class="locked">
        <td class="param">
            Exemple de champ verouillé en écriture
        </td>
        <td class="value">
            <%%lockedfield%%>
        </td>
    </tr>
</table>
</div>';