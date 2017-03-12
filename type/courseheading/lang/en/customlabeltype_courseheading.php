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

$string['courseheading:view'] = 'Can view the content';
$string['courseheading:addinstance'] = 'Can add an instance';

$string['configtypename'] = 'Enable subtype Course Heading';
$string['imageposition'] = 'Image position';
$string['imageurl'] = 'Course Heading Image Url';
$string['image'] = 'Course Heading Image File';
$string['left'] = 'Left';
$string['moduletype'] = 'Module type';
$string['none'] = 'No image';
$string['overimagetext'] = 'Over image text';
$string['pluginname'] = 'Course element : Course Heading';
$string['right'] = 'Right';
$string['showcategory'] = 'Show category';
$string['showdescription'] = 'Show description';
$string['showidnumber'] = 'Show idnumber';
$string['showshortname'] = 'Show shortname';
$string['typename'] = 'Course Heading';
$string['trainingmodule'] = 'Training Module';

$string['family'] = 'structure';

$string['template'] = '
<table class="custombox-courseheading">
<tr valign="middle" class="custombox-icon courseheading">
<%%imageL%%>
<td width="*">
<%if %%showcategory%% %><div class="custombox-category courseheading"><%%category%%></div><%endif %>
<div class="custombox-preheading courseheading"><%%moduletype%%></div>
<div class="custombox-heading courseheading"><%if %%showshortname%% %><%%shortname%%> - <%endif %> <%%courseheading%%> <%if %%showidnumber%% %>(<%%idnumber%%>)<%endif %></div>
<%if %%showdescription%% %><div class="custombox-description courseheading"><%%coursedesc%%></div><%endif %>
</td>
<%%imageR%%>
</tr>
</table>
';
