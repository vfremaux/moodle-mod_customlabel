<?php

$string['authornote:view'] = 'Peut voir le contenu';
$string['authornote:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Note de conception';
$string['typename'] = 'Note de conception';
$string['authornote'] = 'Note de conception';
$string['initiallyvisible'] = 'Visible au chargement';
$string['configtypename'] = 'Activer Note de conception';

$string['template'] = '
<div class="custombox-control authornote"><a href="javascript:togglecustom(\'<%%customid%%>\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>" src="<%%initialcontrolimage%%>" /></a>
<span class="custombox-header-caption authornote"><b>Note de conception</b></span></div>
<div class="custombox-content authornote" id="custom<%%customid%%>">
<p class="custombox-helper authornote"><b>(Cette note ne peut être vue que par les auteurs)</b></p>
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
