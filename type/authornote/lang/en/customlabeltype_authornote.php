<?php

$string['authornote:view'] = 'Can view the content';
$string['authornote:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Authoring note';
$string['typename'] = 'Authoring note';
$string['authornote'] = 'Authoring note';
$string['initiallyvisible'] = 'Initially visible';
$string['configtypename'] = 'Enable subtype Authoring note';

$string['family'] = 'meta';

$string['template'] = '
<div class="custombox-control authornote"><a href="javascript:togglecustom(\'<%%customid%%>\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>" src="<%%initialcontrolimage%%>" /></a>
<span class="custombox-header-caption authornote"><b>Authoring note</b></span></div>
<div class="custombox-content authornote" id="custom<%%customid%%>">
<p class="custombox-helper authornote"><b>(This note is only visible for course authors)</b></p>
<table width="100%" class="custombox-authornote">
    <tr valign="top">
        <td class="custombox-thumb authornote"  width="2%"></td>
        <td class="custombox-content authornote"><%%authornote%%></td>
    </tr>
</table>
</div>
<script type="text/javascript">
setupcustom(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'<%%wwwroot%%>\');
</script>
';