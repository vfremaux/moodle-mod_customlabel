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
 * Lang File.
 *
 * @package    customlabeltype_processpractices
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['processpractices:view'] = 'Peut voir le contenu';
$string['processpractices:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Rubrique "Pratiques spécifiques de processus"';
$string['configtypename'] = 'Activer les Pratiques spécifiques CMMI';
$string['typename'] = 'CMMI Pratiques spécifiques';
$string['processpractices'] = 'Rubrique "Pratiques spécifiques de processus"&nbsp;';
$string['practices'] = 'Pratiques spécifiques&nbsp;';

$string['template'] = '
<table class="processpractices" cellmargin="0" cellpadding="0">
<tr valign="top">
<td align="left" class="leftmargin"><img src="<%%sideimage%%>" align="left" />
</td>
<td class="practicecontent">
<%%practices%%>
</td>
</table>
';
