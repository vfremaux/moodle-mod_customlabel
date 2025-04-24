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
 * @package    customlabeltype_remind
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['remind:view'] = 'Can view the reminder';
$string['remind:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element: Reminder';
$string['typename'] = 'Remind';
$string['configtypename'] = 'Enable subtype Remind';
$string['remindtext'] = 'Remind text';
$string['remind'] = 'Remind this!';

$string['family'] = 'pedagogic';

$string['template'] = '
<table class="custombox-remind" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb remind" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption remind" width="98%">
        Remind !
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content remindtext">
        <%%remindtext%%>
    </td>
</tr>
</table>
';
