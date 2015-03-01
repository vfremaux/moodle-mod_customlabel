<?php

$string['pedagogicadvice:view'] = 'Peut voir le contenu';
$string['pedagogicadvice:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Note pédagogique';
$string['typename'] = 'Note pédagogique';
$string['configtypename'] = 'Active le type Note pédagogique';
$string['typename'] = 'Note pédagogique';
$string['advice'] = 'Conseil ';
$string['initiallyvisible'] = 'Visible au chargement de la page';

$string['template'] = '
<div class="custombox-control pedagogicadvice"><a href="javascript:togglecustom(\'<%%customid%%>\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>" src="<%%initialcontrolimage%%>" /></a>
<span class="custombox-header-caption pedagogicadvice"><b>Note pédagogique</b></span></div>
<div class="custombox-content pedagogicadvice" id="custom<%%customid%%>">
<p class="custombox-helper pedagogicadvice"><b>(Cette note ne peut être vue que par les enseignants)</b></p>
<table width="100%" class="custombox-pedagogicadvice">
    <tr valign="top">
        <td class="custombox-thumb pedagogicadvice"  width="2%"></td>
        <td class="custombox-content pedagogicadvice"><%%advice%%></td>
    </tr>
</table>
</div>
<script type="text/javascript">
setupcustom(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'<%%wwwroot%%>\');
</script>
';