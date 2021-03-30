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

$string['NEWTYPE:view'] = 'Peut voir le contenu';
$string['NEWTYPE:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : NEW TYPE';
$string['typename'] = 'Prototype de type de label personnalisé&ensp;';
$string['configtypename'] = 'Active le type NEWTYPE';
$string['smalltext'] = 'Exemple de texte&ensp;';
$string['parag'] = 'Exemple de texte long&ensp;';
$string['list'] = 'Exemple de liste à choix unique&ensp;';
$string['listmultiple'] = 'Exemple de liste à choix multiple&ensp;';
$string['opt1'] = 'Option 1';
$string['opt2'] = 'Option 2';
$string['opt3'] = 'Option 3';
$string['lockedfield'] = 'Champ à autorisation';
$string['lockedsample'] = 'Le contenu de ce champ n\'est modifiable que par certains rôles&ensp;';

$string['template'] = '
<!-- This is a layout template for the custom type NEWTYPE -->
<!-- There should be a template for all used languages -->
<!-- Remind : Template must be UTF8 encoded -->

<!-- The first line calls the customization CSS -->
<div class="labelcontent">
<table class="customlabeldemo">
    <tr>
        <th colspan="2" class="title">
            <%%title%%>
        </th>
    <tr>
    <tr>
        <td class="param">
            Exemple de texte
        </td>
        <td class="value">
            <%%smalltext%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Exemple de texte long
        </td>
        <td class="value">
            <%%parag%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Exemple de liste choix unique
        </td>
        <td class="value">
            <%%list%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Exemple de liste choix multiple
        </td>
        <td class="value">
            <%%listmultiple%%>
        </td>
    </tr>
    <tr class="locked">
        <td class="param">
            Exemple de champ verouillé en écriture
        </td>
        <td class="value">
            <%%lockedfield%%>
        </td>
    </tr>
</table>
</div>';