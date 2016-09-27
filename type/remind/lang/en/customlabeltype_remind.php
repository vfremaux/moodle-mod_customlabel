<?php

$string['remind:view'] = 'Can view the reminder';
$string['remind:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Reminder';
$string['typename'] = 'Remind';
$string['configtypename'] = 'Enable subtype Remind';
$string['remindtext'] = 'Remind text';

$string['family'] = 'pedagogic';

$string['template'] = '
<table class="custombox-remind" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb remind" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption remind" width="98%">
        Remind !
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content remindtext">
        <%%remindtext%%>
    </td>
</tr>
</table>
';