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
 * This is a local class contextual translation file for field names and list options.
 * this file is automatically loaded by the /mod/customlabel/lang/xx_utf8/customlabel.php
 * module language file.
 *
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
$string['question:view'] = 'Can view the question';
$string['question:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element: Question';
$string['typename'] = 'Question';
$string['question'] = 'Question';
$string['solution'] = 'Solution';
$string['showansweron'] = 'Show answer on';
$string['configtypename'] = 'Enable subtype Question';
$string['questiontext'] = 'Question text';
$string['answertext'] = 'Answer text';
$string['willopenon'] = 'Will open on';
$string['notavailableyet'] = 'Not yet available';
$string['initiallyvisible'] = 'Answer initially visible';
$string['hint'] = 'Hint';
$string['hintinitiallyvisible'] = 'Hint initially visible';

$string['family'] = 'pedagogic';

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
    <td class="custombox-header-collapser question" align="left" >
        <a href="javascript:togglecustom(\'<%%customid%%>hint\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>hint" src="<%%hintinitialcontrolimage%%>" /></a> Hint
    </td>
</tr>
<tr valign="top" id="custom<%%customid%%>hint">
    <td class="custombox-content hint">
        <%%hint%%>
    </td>
</tr>
<%endif %>
<tr>
    <td class="custombox-header-collapser question" align="left">
        <a href="javascript:togglecustom(\'<%%customid%%>\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>" src="<%%initialcontrolimage%%>" /></a> Solution
    </td>
</tr>
<tr valign="top" id="custom<%%customid%%>">
    <td class="custombox-content questiontext">
        <%%answertext%%>
    </td>
</tr>
</table>
<script type="text/javascript">
setupcustom(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'<%%wwwroot%%>\');
setupcustom(\'<%%customid%%>hint\', \'<%%hintinitiallyvisible%%>\', \'<%%wwwroot%%>\');
</script>
';