<?php

$string['important:view'] = 'Peut voir le contenu';
$string['important:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Important';
$string['typename'] = 'Important';
$string['configtypename'] = 'Active le type Important';
$string['importantnote'] = 'Avertissement';

$string['template'] = '
<table class="custombox-important" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb important" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption important" width="98%">
        Important !
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content important">
        <%%importantnote%%>
    </td>
</tr>
</table>';