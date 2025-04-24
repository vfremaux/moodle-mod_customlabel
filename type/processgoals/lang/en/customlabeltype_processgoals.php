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
 * @package    customlabeltype_processgoals
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['processgoals:view'] = 'Can view the content';
$string['processgoals:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Process specific goal list';
$string['typename'] = 'CMMI Elements : Process specific goals';
$string['configtypename'] = 'Enable CMMI Process specific goals';
$string['processgoals'] = 'Process specific goal list';
$string['goals'] = 'Specific goals';

$string['family'] = 'special';

$string['template'] = '
<table class="processgoals" cellmargin="0" cellpadding="0">
<tr valign="top">
<td align="left" class="leftmargin"><img src="<%%sideimage%%>" align="left" />
</td>
<td class="goalcontent">
<%%goals%%>
</td>
</table>';
