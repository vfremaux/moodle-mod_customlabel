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

$string['unitheading:view'] = 'Can view the content';
$string['unitheading:addinstance'] = 'Can add an instance';

$string['configtypename'] = 'Enable subtype Unit title';
$string['heading'] = 'Unit title';
$string['imageposition'] = 'Image position';
$string['imagewidth'] = 'Image width';
$string['left'] = 'Left';
$string['none'] = 'No image';
$string['pluginname'] = 'Course element: Unit Heading';
$string['right'] = 'Right';
$string['shortdesc'] = 'Short description';
$string['typename'] = 'Unit title';
$string['imageurl'] = 'Alternate Image URL';
$string['image'] = 'Alternate Image File';
$string['overimagetext'] = 'Text over image';

$string['family'] = 'structure';

$string['template'] = '
<table class="custombox-unitheading" width="100%">
<tr valign="middle" class="custombox-icon unitheading">
<%%imageL%%>
<td>
<h3 class="custombox-caption unitheading"><%%heading%%></h3>
<p class="custombox-description unitheading"><%%shortdesc%%></p>
</td>
<%%imageR%%>
</tr>
</table>
';