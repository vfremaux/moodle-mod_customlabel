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

$string['fundamental:view'] = 'Peut voir la notion';
$string['fundamental:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: Notion Fondamentale';
$string['typename'] = 'Fondamentaux';
$string['configtypename'] = 'Active le type Notion Fondamentale';
$string['fundamentaltext'] = 'Notion fondamentale';

$string['template'] = '
<table class="custombox-fundamental" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb fundamental" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption fundamental" width="98%">
        Fondamentaux
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content fundamentaltext">
        <%%fundamentaltext%%>
    </td>
</tr>
</table>
';