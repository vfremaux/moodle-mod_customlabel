<?php

$string['seealso:view'] = 'Can view the content';
$string['seealso:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : See Also';
$string['typename'] = 'See Also';
$string['configtypename'] = 'Enable subtype See Also';
$string['seealso'] = 'See also';

$string['family'] = 'pedagogic';

$string['template'] = '
<table class="custombox-seealso" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb seealso" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption seealso" width="98%">
        See also!
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content seealso">
        <%%seealso%%>
    </td>
</tr>
</table>';