<?php

$string['unitheading:view'] = 'Can view the content';
$string['unitheading:addinstance'] = 'Can add an indstance';

$string['configtypename'] = 'Enable subtype Unit title';
$string['heading'] = 'Unit title';
$string['imageposition'] = 'Image position';
$string['left'] = 'Left';
$string['none'] = 'No image';
$string['pluginname'] = 'Course element : Unit Heading';
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