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

$string['method:view'] = 'Peut voir le contenu';
$string['method:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: Méthode';
$string['typename'] = 'Méthode';
$string['configtypename'] = 'Active le type Méthode';
$string['methodtext'] = 'Description de la méthode&nbsp;';

$string['template'] = '
<table class="custombox-method" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb method" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption method" width="98%">
        Méthode
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content methodtext">
        <%%methodtext%%>
    </td>
</tr>
</table>
';