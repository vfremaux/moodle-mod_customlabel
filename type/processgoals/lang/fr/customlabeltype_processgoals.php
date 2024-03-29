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

$string['processgoals:view'] = 'Peut voir le contenu';
$string['processgoals:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Rubrique "Objectifs spécifiques de processus"';
$string['typename'] = 'CMMI : "Objectifs de processus"';
$string['configtypename'] = 'Activer les Objectifs de processus CMMI';
$string['processgoals'] = 'Rubrique "Objectifs spécifiques de processus"';
$string['goals'] = 'Objectifs spécifiques';

$string['template'] = '
<table class="processgoals" cellmargin="0" cellpadding="0">
<tr valign="top">
<td align="left" class="leftmargin"><img src="<%%sideimage%%>" align="left" />
</td>
<td class="goalcontent">
<%%goals%%>
</td>
</table>';