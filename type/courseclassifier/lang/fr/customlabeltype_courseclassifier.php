<?php

$string['courseclassifier:view'] = 'Peut voir le contenu';
$string['courseclassifier:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Element de cours : Classification de cours';
$string['courseclassifier'] = 'Classification de cours';
$string['tablecaption'] = 'Titre de la table';
$string['typename'] = 'Classification de cours';
$string['configtypename'] = 'Active le type Classification de cours';
$string['level0'] = 'Classification 1';
$string['level1'] = 'Classification 2';
$string['level2'] = 'Classification 3';
$string['people'] = 'Public';
$string['showpeople'] = 'Afficher le critère de public';
$string['uselevels'] = 'Niveaux actifs';

$string['template'] = '
<table class="custombox-courseclassifier">
    <tr valign="top">
        <th class="custombox-title courseclassifier" colspan="2">
            <%%tablecaption%%>
        </th>
    </tr>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Niveau 1 :
        </td>
        <td class="custombox-value courseclassifier">
            <%%level0%%>
        </td>
    </tr>
    <%if %%uselevels >= 2%% %>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Niveau 2 :
        </td>
        <td class="custombox-value courseclassifier">
            <%%level1%%>
        </td>
    </tr>
    <%endif %>
    <%if %%uselevels >= 3%% %>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Niveau 3 :
        </td>
        <td class="custombox-value courseclassifier">
            <%%level2%%>
        </td>
    </tr>
    <%endif %>
</table>
<%if %%showpeople%% %>
<table class="custombox-courseclassifier other">
    <tr valign="top">
        <th class="custombox-title courseclassifier" colspan="2">
            Autres informations
        </th>
    </tr>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Public :
        </td>
        <td class="custombox-value courseclassifier">
            <%%people%%>
        </td>
    </tr>
</table>
<%endif %>';
