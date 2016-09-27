<?php

/**
* This is a local class contextual translation file for field names and 
* list options.
* this file is automatically loaded by the 
* /mod/customlabel/lang/xx_utf8/customlabel.php
* module language file.
*
*/

$string['authordata:view'] = 'Can view the content';
$string['authordata:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Author Information';
$string['typename'] = 'Author information';
$string['configtypename'] = 'Enable subtype Author information';
$string['author1'] = 'Author 1';
$string['author2'] = 'Author 2';
$string['author3'] = 'Author 3';
$string['thumb1'] = 'Thumb 1';
$string['thumb2'] = 'Thumb 2';
$string['thumb3'] = 'Thumb 3';
$string['tablecaption'] = 'Table caption';
$string['contributors'] = 'Contributors';
$string['institution'] = 'Institution';
$string['department'] = 'Department';
$string['showcontributors'] = 'Show contributors';
$string['showinstitution'] = 'Show institution';
$string['showdepartment'] = 'Show department';

$string['family'] = 'meta';

$string['template'] = '
<table class="custombox-authordata">
    <%if %%tablecaption%% %>
    <tr valign="top">
        <th class="custombox-title authordata" colspan="2">
            <%%tablecaption%%>
        </th>
    </tr>
    <%endif %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Author<%if %%author2%% %>s<%endif %> : 
        </td>
        <td class="custombox-value authordata">
            <%if %%thumb3%% %>
            <img src="<%%thumb3%%>" title="<%%author3%%>" style="float:right" width="80"  height="120"/>
            <%endif %>
            <%if %%thumb2%% %>
            <img src="<%%thumb2%%>" title="<%%author2%%>" style="float:right;margin-right:10px"  width="80"  height="120" />
            <%endif %>
            <%if %%thumb1%% %>
            <img src="<%%thumb1%%>" title="<%%author1%%>" style="float:right;margin-right:10px"  width="80"  height="120" />
            <%endif %>
            <%%author1%%> 
            <%%author2%%>
            <%%author3%%>
        </td>
    </tr>
    <%if %%showinstitution%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Institution:
        </td>
        <td class="custombox-value authordata">
            <%%institution%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showdepartment%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Department:
        </td>
        <td class="custombox-value authordata">
            <%%department%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showcontributors%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Contributors:
        </td>
        <td class="custombox-value authordata">
            <%%contributors%%>
        </td>
    </tr>
    <%endif %>
</table>';