<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

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
    <td class="custombox-header-thumb question" style="background-image : url(<%%icon%%>);" width="2%" rowspan="6">
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