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

$string['tipsandtricks:view'] = 'Peut voir le contenu';
$string['tipsandtricks:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Trucs et astuces';
$string['typename'] = 'Trucs et astuces';
$string['configtypename'] = 'Active le type Trucs et astuces';
$string['tipsandtricks'] = 'Trucs et astuces';

$string['template'] = '
<table class="custombox-tipsandtricks" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb tipsandtricks" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption tipsandtricks" width="98%">
        Trucs et astuces !
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content tipsandtricks">
        <%%tipsandtricks%%>
    </td>
</tr>
</table>
';