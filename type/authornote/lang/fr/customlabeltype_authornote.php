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
        <td class="custombox-thumb authornote" style="background-image : url(<%%icon%%>);" width="2%"></td>
        <td class="custombox-content authornote"><%%authornote%%></td>
    </tr>
</table>
</div>
<script type="text/javascript">
setupcustom(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'<%%wwwroot%%>\');
</script>
';
