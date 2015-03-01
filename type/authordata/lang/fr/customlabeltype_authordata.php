<?php

/**
* This is a local class contextual translation file for field names and 
* list options.
* this file is automatically loaded by the 
* /mod/customlabel/lang/xx_utf8/customlabel.php
* module language file.
*
*/
$string['authordata:view'] = 'Peut voir le contenu';
$string['authordata:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Information sur les auteurs';
$string['typename'] = 'Information sur les auteurs';
$string['configtypename'] = 'Activer le type Information sur les auteurs';
$string['author1'] = 'Auteur 1';
$string['author2'] = 'Auteur 2';
$string['author3'] = 'Auteur 3';
$string['thumb1'] = 'Vignette 1';
$string['thumb2'] = 'Vignette 2';
$string['thumb3'] = 'Vignette 3';
$string['tablecaption'] = 'Titre de table';
$string['contributors'] = 'Contributeurs';
$string['institution'] = 'Institution';
$string['department'] = 'Département';
$string['showcontributors'] = 'Afficher les contributeurs';
$string['showinstitution'] = 'Afficher l\'institution';
$string['showdepartment'] = 'Afficher le départment';

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
            Auteur<%if %%author2%% %>s<%endif %> : 
        </td>
        <td class="custombox-value authordata">
            <%if %%thumb3%% %>
            <img src="<%%thumb3%%>" title="<%%author3%%>" style="float:right" />
            <%endif %>
            <%if %%thumb2%% %>
            <img src="<%%thumb2%%>" title="<%%author2%%>" style="float:right;margin-right:10px" />
            <%endif %>
            <%if %%thumb1%% %>
            <img src="<%%thumb1%%>" title="<%%author1%%>" style="float:right;margin-right:10px" />
            <%endif %>
            <%%author1%%> 
            <%%author2%%>
            <%%author3%%>
        </td>
    </tr>
    <%if %%showinstitution%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Institution :
        </td>
        <td class="custombox-value authordata">
            <%%institution%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showdepartment%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Départment :
        </td>
        <td class="custombox-value authordata">
            <%%department%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showcontributors%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Contributeurs :
        </td>
        <td class="custombox-value authordata">
            <%%contributors%%>
        </td>
    </tr>
    <%endif %>
</table>';