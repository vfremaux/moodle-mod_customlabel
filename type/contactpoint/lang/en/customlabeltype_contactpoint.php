<?php

$string['contactpoint:view'] = 'Can view the content';
$string['contactpoint:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Contact Point';
$string['typename'] = 'Contact point';
$string['configtypename'] = 'Enable subtype Contact point';
$string['instructions'] = 'Instructions';
$string['contacttype'] = 'Contact type';
$string['any'] = 'Any method';
$string['anywritten'] = 'Any method but written';
$string['mail'] = 'Mail';
$string['phone'] = 'Phone';
$string['onlinevocal'] = 'On line vocal';
$string['chat'] = 'Chat';
$string['meeting'] = 'Online meeting';
$string['facetoface'] = 'Physical meeting';

$string['family'] = 'special';

$string['template'] = '
<table class="custombox-contactpoint" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb contactpoint <%%contacttypeoption%%>" width="2%" rowspan="4">
    </td>
    <td class="custombox-header-caption contactpoint" width="98%" colspan="2">
        Contact point...<br/>
        <span class="custombox-header-caption contacttype">Method : <%%contacttype%%></span>
    </td>
</tr>
<tr>
    <td class="custombox-content contactpoint" colspan="2">
        <%%instructions%%>
    </td>
</tr>
</table>';

