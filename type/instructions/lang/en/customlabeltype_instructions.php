<?php

$string['instructions:view'] = 'Can view the content';
$string['instructions:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Instructions';
$string['typename'] = 'Instructions';
$string['configtypename'] = 'Enable subtype Instructions';
$string['instructions'] = 'Instructions text';

$string['family'] = 'pedagogic';

$string['template'] = '
<table class="custombox-instructions" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb instructions" width="2%" rowspan="2">
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