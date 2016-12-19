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

$string['theorema:view'] = 'Peut voir le contenu';
$string['theorema:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Théorème';
$string['typename'] = 'Théorème';
$string['configtypename'] = 'Active le type Théorème';
$string['theorema'] = 'Enoncé de théorème';
$string['corollarynum'] = 'Nombre de corollaires';
$string['corollary'] = 'Corollaire';
$string['corollary0'] = 'Corollaire 1';
$string['corollary1'] = 'Corollaire 2';
$string['corollary2'] = 'Corollaire 3';
$string['corollary3'] = 'Corollaire 4';
$string['corollary4'] = 'Corollaire 5';
$string['corollary5'] = 'Corollaire 6';
$string['corollary6'] = 'Corollaire 7';
$string['corollary7'] = 'Corollaire 8';
$string['corollary8'] = 'Corollaire 9';
$string['showdemonstration'] = 'Afficher la demonstration';
$string['demonstration'] = 'Demonstration';

$string['template'] = '
<table class="custombox-theorema" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb theorema" style="background-image : url(<%%icon%%>);" width="2%" rowspan="3">
    </td>
    <td class="custombox-header-caption theorema" width="98%">
        Théorème
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content theorema">
        <%%theorema%%>
    </td>
</tr>
<tr valign="top">
    <td class="custombox-corollaries theorema">
        <%%corollarylist%%>
    </td>
</tr>
<%if %%showdemonstration%% %>
<tr valign="top">
    <td class="custombox-header-foo theorema" width="2%"></td>
    <td class="custombox-demonstration theorema">
        <div class="custombox-demonstration-caption theorema">Démonstration</div>
        <div class="custombox-demonstration theorema"><%%demonstration%%></div>
    </td>
</tr>
<%endif %>
</table>
';