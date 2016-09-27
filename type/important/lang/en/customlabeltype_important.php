<?php

$string['important:view'] = 'Can view the content';
$string['important:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : Important';
$string['typename'] = 'Important';
$string['configtypename'] = 'Enable subtype Important';
$string['importantnote'] = 'Important note ';

$string['family'] = 'generic';

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