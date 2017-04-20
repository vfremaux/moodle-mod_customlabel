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

$string['tipsandtricks:view'] = 'Can view the content';
$string['tipsandtricks:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element: Tips and Tricks';
$string['typename'] = 'Tips and tricks';
$string['configtypename'] = 'Enable subtype Tips and tricks';
$string['tipsandtricks'] = 'Tips and Tricks';

$string['family'] = 'pedagogic';

$string['template'] = '<table class="custombox-tipsandtricks" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb tipsandtricks" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption tipsandtricks" width="98%">
        Tips and tricks !
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content tipsandtricks">
        <%%tipsandtricks%%>
    </td>
</tr>
</table>';