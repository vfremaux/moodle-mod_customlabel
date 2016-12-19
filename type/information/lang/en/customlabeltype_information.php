<?php

$string['information:view'] = 'Can view the content';
$string['information:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Information';
$string['typename'] = 'Information';
$string['configtypename'] = 'Enable subtype Information';
$string['informationtext'] = 'Information text';

$string['family'] = 'pedagogic';

$string['template'] = '
<table class="custombox-information" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb information" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
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