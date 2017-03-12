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

$string['contactpoint:view'] = 'Peut voir le contenu';
$string['contactpoint:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Point de contact';
$string['typename'] = 'Point de contact';
$string['configtypename'] = 'Active le type Point de contact';
$string['contactpoint'] = 'Point de contact';
$string['instructions'] = 'Instructions';
$string['contacttype'] = 'Type de contact';
$string['any'] = 'Toute méthode';
$string['anywritten'] = 'Toute méthode écrite';
$string['mail'] = 'Mél';
$string['phone'] = 'Téléphone';
$string['onlinevocal'] = 'Com. en ligne';
$string['chat'] = 'Chat';
$string['meeting'] = 'Réunion virtuelle';
$string['facetoface'] = 'Face à face';

$string['template'] = '
<table class="contactpoint" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb contactpoint <%%contacttypeoption%%>" style="background-image : url(<%%icon%%>);" width="2%" rowspan="4">
    </td>
    <td class="custombox-header-caption contactpoint" width="98%" colspan="2">
        Point de contact...<br/>
        <span class="custombox-header-caption contacttype">Méthode : <%%contacttype%%></span>
    </td>
</tr>
<tr>
    <td class="custombox-content contactpoint" colspan="2">
        <%%instructions%%>
    </td>
</tr>
</table>';