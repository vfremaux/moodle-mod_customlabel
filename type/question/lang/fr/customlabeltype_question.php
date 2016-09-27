<?php

$string['question:view'] = 'Peut voir la question';
$string['question:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Question';
$string['typename'] = 'Question';
$string['configtypename'] = 'Active le type question';
$string['questiontext'] = 'Texte de la question';
$string['answertext'] = 'Texte de la réponse';
$string['initiallyvisible'] = 'Réponse visible au chargement';
$string['hint'] = 'Texte d\'indice';
$string['hintinitiallyvisible'] = 'Indice visible au chargement';

$string['template'] = '
<table class="custombox-question" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb question" width="2%" rowspan="6">
    </td>
    <td class="custombox-header-caption question" width="96%">
        Question !
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content questiontext">
        <%%questiontext%%>
    </td>
</tr>
<%if %%hint%% %>
<tr>
    <td class="custombox-header-collapser question" align="left">
        <a href="javascript:togglecustom(\'<%%customid%%>hint\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>hint" src="<%%hintinitialcontrolimage%%>" /></a> Indice
    </td>
</tr>
<tr valign="top" id="custom<%%customid%%>hint">
    <td class="custombox-content hint">
        <%%hint%%>
    </td>
</tr>
<%endif %>
<tr valign="top">
    <td class="custombox-header-collapser question" align="left">
        <a href="javascript:togglecustom(\'<%%customid%%>\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>" src="<%%initialcontrolimage%%>" /></a> Solution
    </td>
</tr>
<tr valign="top" id="custom<%%customid%%>">
    <td class="custombox-content answertext">
        <%%answertext%%>
    </td>
</tr>
</table>
<script type="text/javascript">
setupcustom(\'<%%customid%%>hint\', \'<%%hintinitiallyvisible%%>\', \'<%%wwwroot%%>\');
setupcustom(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'<%%wwwroot%%>\');
</script>
';