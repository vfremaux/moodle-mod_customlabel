<?php

/**
* This is a local class contextual translation file for field names and 
* list options.
* this file is automatically loaded by the 
* /mod/customlabel/lang/xx_utf8/customlabel.php
* module language file.
*
*/

$string['formule:view'] = 'Can view the content';
$string['formule:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : Formula';
$string['typename'] = 'Customized label sample ';
$string['configtypename'] = 'Enables subtype formula';
$string['smalltext'] = 'Text sample ';
$string['parag'] = 'Long text sample ';
$string['list'] = 'Unique choice list sample ';
$string['listmultiple'] = 'Multiple choice sample ';
$string['lockedfield'] = 'Locked field';
$string['lockedsample'] = 'Only designated roles can modifiy the content of these fields ';

$string['family'] = 'pedagogic';

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