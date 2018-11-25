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

$string['sectionheading:view'] = 'Can view the content';
$string['sectionheading:addinstance'] = 'Can add an indstance';

$string['configtypename'] = 'Enable subtype Section title';
$string['heading'] = 'Section title';
$string['imageposition'] = 'Image position';
$string['imagewidth'] = 'Image width';
$string['left'] = 'Left';
$string['none'] = 'No image';
$string['pluginname'] = 'Course element: Section Heading';
$string['right'] = 'Right';
$string['shortdesc'] = 'Short description';
$string['typename'] = 'Section title';
$string['imageurl'] = 'Alternate Image URL';
$string['image'] = 'Image file';
$string['overimagetext'] = 'Text over image';

$string['family'] = 'structure';

$string['template'] = '
<table class="custombox-sectionheading" width="100%">
<tr valign="top" class="custombox-icon sectionheading">
<%%imageL%%>
<td>
<h2 class="custombox-caption sectionheading"><%%heading%%></h2>
<p class="custombox-description sectionheading"><%%shortdesc%%></p>
</td>
<%%imageR%%>
</tr>
</table>
';