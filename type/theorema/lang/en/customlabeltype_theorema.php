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

$string['theorema:view'] = 'Can view the content';
$string['theorema:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : Theorema';
$string['typename'] = 'Theorema';
$string['configtypename'] = 'Enable subtype Theorema';
$string['theorema'] = 'Theorema text';
$string['corollarynum'] = 'Number of corollaries';
$string['corollary'] = 'Corollary';
$string['corollary0'] = 'Corollary 1';
$string['corollary1'] = 'Corollary 2';
$string['corollary2'] = 'Corollary 3';
$string['corollary3'] = 'Corollary 4';
$string['corollary4'] = 'Corollary 5';
$string['corollary5'] = 'Corollary 6';
$string['corollary6'] = 'Corollary 7';
$string['corollary7'] = 'Corollary 8';
$string['corollary8'] = 'Corollary 9';
$string['showdemonstration'] = 'Show demonstration';
$string['demonstration'] = 'Demonstration';

$string['family'] = 'pedagogic';

$string['template'] = '
<table class="custombox-theorema" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb theorema" style="background-image : url(<%%icon%%>);" width="2%" rowspan="3">
    </td>
    <td class="custombox-header-caption theorema" width="98%">
        Theorema
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
        <div class="custombox-demonstration-caption theorema">Demonstration</div>
        <div class="custombox-demonstration theorema"><%%demonstration%%></div>
    </td>
</tr>
<%endif %>
</table>
';