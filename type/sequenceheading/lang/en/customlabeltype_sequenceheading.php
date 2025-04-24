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
 * Lang file.
 *
 * @package    customlabeltype_sequenceheading
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['sequenceheading:view'] = 'Can view the content';
$string['sequenceheading:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element: Sequence Heading';
$string['typename'] = 'Sequence Heading';
$string['configtypename'] = 'Enable subtype Sequence Heading';
$string['heading'] = 'Sequence title';
$string['shortdesc'] = 'Short description';
$string['imageurl'] = 'Alternate Image URL';
$string['image'] = 'Title thumb image';
$string['imageposition'] = 'Image position';
$string['imagewidth'] = 'Image width';
$string['left'] = 'Left';
$string['right'] = 'Right';
$string['none'] = 'Not visible';
$string['overimagetext'] = 'Text over image';
$string['verticalalign'] = 'Vertical alignment';
$string['top'] = 'Top';
$string['middle'] = 'Middle';
$string['bottom'] = 'Bottom';

$string['family'] = 'structure';

$string['template'] = '
<table class="custombox-sequenceheading" width="100%">
<tr valign="<%%verticalalignoption%%>" class="custombox-icon sequenceheading">
<%%imageL%%>
<td>
<h2 class="custombox-caption sequenceheading"><%%heading%%></h2>
<p class="sequenceheading"><%%shortdesc%%></p>
</td>
<%%imageR%%>
</tr>
</table>';
