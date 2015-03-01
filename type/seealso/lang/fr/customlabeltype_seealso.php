<?php

$string['seealso:view'] = 'Peut voir le contenu';
$string['seealso:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Voir aussi';
$string['typename'] = 'Voir aussi';
$string['configtypename'] = 'Active le type Voir aussi';
$string['seealso'] = 'Voir aussi';

$string['template'] = '
<table class="custombox-seealso" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb seealso" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption seealso" width="98%">
        Voir aussi !
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content seealso">
        <%%seealso%%>
    </td>
</tr>
</table>';