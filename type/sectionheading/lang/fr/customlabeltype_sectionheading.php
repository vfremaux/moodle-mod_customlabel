<?php

$string['sectionheading:view'] = 'Peut voir le contenu';
$string['sectionheading:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Titre de section';
$string['typename'] = 'Titre de section';
$string['configtypename'] = 'Active le type Titre de section';
$string['heading'] = 'Titre de section';
$string['shortdesc'] = 'Description courte';
$string['imageposition'] = 'Position de l\'image';
$string['none'] = 'Pas d\'image';
$string['left'] = 'A gauche';
$string['right'] = 'A droite';
$string['imageurl'] = 'Image';
$string['image'] = 'Fichier vignette';
$string['overimagetext'] = 'Texte sur image';

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
</table>';
