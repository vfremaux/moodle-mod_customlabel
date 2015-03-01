<?php

$string['courseheading:view'] = 'Peut voir le contenu';
$string['courseheading:addinstance'] = 'Peut ajouter une instance';

$string['configtypename'] = 'Active le type En-tête de cours';
$string['imageposition'] = 'Position de l\'image';
$string['imageurl'] = 'Url d\'illustration d\'en-tête';
$string['image'] = 'Vignette d\'en-tête de cours';
$string['left'] = 'A gauche';
$string['moduletype'] = 'Type de module';
$string['none'] = 'Pas d\'image';
$string['overimagetext'] = 'Label sur image';
$string['pluginname'] = 'Elément de cours : En-tête de cours';
$string['right'] = 'A droite';
$string['showcategory'] = 'Afficher la catégorie';
$string['showdescription'] = 'Afficher la description';
$string['showidnumber'] = 'Afficher le numéro d\'identification';
$string['showshortname'] = 'Afficher le nom court';
$string['trainingmodule'] = 'Module de formation';
$string['typename'] = 'En-tête de cours';

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