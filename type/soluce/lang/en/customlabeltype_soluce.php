<?php

$string['soluce:view'] = 'Can view the content';
$string['soluce:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : Soluce';
$string['typename'] = 'Soluce';
$string['configtypename'] = 'Enable subtype Soluce';
$string['soluce'] = 'Soluce';
$string['initiallyvisible'] = 'Initially visible';

$string['family'] = 'pedagogic';

$string['template'] = '
<table class="custombox-soluce" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb soluce" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption soluce" width="98%">
        Soluce...
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