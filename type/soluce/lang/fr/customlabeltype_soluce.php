<?php

$string['soluce:view'] = 'Peut voir le contenu';
$string['soluce:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Solution';
$string['typename'] = 'Solution';
$string['configtypename'] = 'Active le type Solution';
$string['soluce'] = 'Solution, corrigé';
$string['initiallyvisible'] = 'Visible au chargement de la page';

$string['template'] = '
<table class="soluce" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb soluce" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption soluce" width="98%">
        Solution...
    </td>
    <td class="custombox-header-collapser soluce" align="right" width="2%">
        <a href="javascript:togglecustom(\'<%%customid%%>\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>" src="<%%initialcontrolimage%%>" /></a>
    </td>
</tr>
<tr>
    <td colspan="2">
        <table id="custom<%%customid%%>" cellspacing="0" width="100%">
        <tr>
            <td class="custombox-content soluce" colspan="2">
                <%%soluce%%>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>
<script type="text/javascript">
setupcustom(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'<%%wwwroot%%>\');
</script>
';