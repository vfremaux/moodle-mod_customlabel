<?php

$string['sectionheading:view'] = 'Can view the content';
$string['sectionheading:addinstance'] = 'Can add an indstance';

$string['configtypename'] = 'Enable subtype Section title';
$string['heading'] = 'Section title';
$string['imageposition'] = 'Image position';
$string['left'] = 'Left';
$string['none'] = 'No image';
$string['pluginname'] = 'Course element : Section Heading';
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