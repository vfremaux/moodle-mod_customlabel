<?php

$string['instructions:view'] = 'Peut voir le contenu';
$string['instructions:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Instructions';
$string['typename'] = 'Instructions';
$string['configtypename'] = 'Active le type Instructions';
$string['instructions'] = 'Texte de l\'instructions';

$string['template'] = '
<table class="custombox-instructions" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb instructions" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption instructions" width="98%">
        Instructions
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content instructions">
        <%%instructions%%>
    </td>
</tr>
</table>
';