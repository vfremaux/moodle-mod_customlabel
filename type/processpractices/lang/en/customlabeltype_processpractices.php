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

$string['processpractices:view'] = 'Can view the content';
$string['processpractices:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'CMMI Process Practices';
$string['typename'] = 'CMMI Process practices';
$string['configtypename'] = 'Enable CMMI Process Practices';
$string['processpractices'] = 'Process specific practice list';
$string['practices'] = 'Practices';

$string['family'] = 'special';

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