<?php

$string['sequenceheading:view'] = 'Can view the content';
$string['sequenceheading:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : Sequence Heading';
$string['typename'] = 'Sequence Heading';
$string['configtypename'] = 'Enable subtype Sequence Heading';
$string['heading'] = 'Sequence title';
$string['shortdesc'] = 'Short description';
$string['imageurl'] = 'Alternate Image URL';
$string['image'] = 'Title thumb image';
$string['imageposition'] = 'Image position';
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
