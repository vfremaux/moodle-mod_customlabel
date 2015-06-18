<?php

$string['pedagogicadvice:view'] = 'Can view the content';
$string['pedagogicadvice:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : Pedagogic Advice';
$string['typename'] = 'Pedagogic advice';
$string['configtypename'] = 'Enable subtype Pedagogic advice';
$string['advice'] = 'Advising ';
$string['initiallyvisible'] = 'Initially visible';

$string['family'] = 'meta';

$string['template'] = '
<div class="custombox-control pedagogicadvice"><a href="javascript:togglecustom(\'<%%customid%%>\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>" src="<%%initialcontrolimage%%>" /></a>
<span class="custombox-header-caption pedagogicadvice"><b>Pedagogic advice</b></span></div>
<div class="custombox-content pedagogicadvice" id="custom<%%customid%%>">
<p class="custombox-helper pedagogicadvice"><b>(This note is only visible for trainers)</b></p>
<table width="100%" class="custombox-pedagogicadvice">
    <tr valign="top">
        <td class="custombox-thumb pedagogicnote"  width="2%"></td>
        <td class="custombox-content pedagogicadvice"><%%advice%%></td>
    </tr>
</table>
</div>
<script type="text/javascript">
setupcustom(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'<%%wwwroot%%>\');
</script>
';