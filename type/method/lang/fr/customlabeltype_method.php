<?php

$string['method:view'] = 'Peut voir le contenu';
$string['method:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Méthode';
$string['typename'] = 'Méthode';
$string['configtypename'] = 'Active le type Méthode';
$string['methodtext'] = 'Description de la méthode';

$string['template'] = '
<table class="custombox-method" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb method" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption method" width="98%">
        Méthode
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content methodtext">
        <%%methodtext%%>
    </td>
</tr>
</table>
';