<?php

$string['remind:view'] = 'Peut voir le rappel';
$string['remind:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Rappel';
$string['typename'] = 'Rappel';
$string['configtypename'] = 'Active le type rappel';
$string['remindtext'] = 'Texte du rappel';

$string['template'] = '
<table class="custombox-remind" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb remind" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption remind" width="98%">
        Rappel !
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content remindtext">
        <%%remindtext%%>
    </td>
</tr>
</table>
';