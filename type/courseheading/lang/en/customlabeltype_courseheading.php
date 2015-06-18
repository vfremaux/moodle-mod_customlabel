<?php

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
