<?php

$string['information:view'] = 'Peut voir le contenu';
$string['information:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Information';
$string['typename'] = 'Information';
$string['configtypename'] = 'Active le type Information';
$string['information'] = 'Texte de l\'information';

$string['template'] = '
<table class="custombox-information" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb information" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption information" width="98%">
        Information
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content informationtext">
        <%%informationtext%%>
    </td>
</tr>
</table>
';