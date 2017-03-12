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
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['pedagogicadvice:view'] = 'Peut voir le contenu';
$string['pedagogicadvice:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: Note pédagogique';
$string['typename'] = 'Note pédagogique';
$string['configtypename'] = 'Active le type Note pédagogique';
$string['typename'] = 'Note pédagogique&nbsp;';
$string['advice'] = 'Conseil&nbsp;';
$string['initiallyvisible'] = 'Visible au chargement de la page&nbsp;';

$string['template'] = '
<div class="custombox-control pedagogicadvice"><a href="javascript:togglecustom(\'<%%customid%%>\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>" src="<%%initialcontrolimage%%>" /></a>
<span class="custombox-header-caption pedagogicadvice"><b>Note pédagogique</b></span></div>
<div class="custombox-content pedagogicadvice" id="custom<%%customid%%>">
<p class="custombox-helper pedagogicadvice"><b>(Cette note ne peut être vue que par les enseignants)</b></p>
<table width="100%" class="custombox-pedagogicadvice">
    <tr valign="top">
        <td class="custombox-thumb pedagogicadvice" style="background-image : url(<%%icon%%>);" width="2%"></td>
        <td class="custombox-content pedagogicadvice"><%%advice%%></td>
    </tr>
</table>
</div>
<script type="text/javascript">
setupcustom(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'<%%wwwroot%%>\');
</script>
';