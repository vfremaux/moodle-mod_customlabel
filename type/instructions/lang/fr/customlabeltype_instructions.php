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

$string['instructions:view'] = 'Peut voir le contenu';
$string['instructions:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: Instructions';
$string['typename'] = 'Instructions';
$string['configtypename'] = 'Active le type Instructions';
$string['instructions'] = 'Texte de l\'instructions&nbsp;';

$string['template'] = '
<table class="custombox-instructions" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb instructions" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption instructions" width="98%">
        Instructions
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content instructions">
        <%%instructions%%>
    </td>
</tr>
</table>
';