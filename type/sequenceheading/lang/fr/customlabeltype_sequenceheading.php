<?php

$string['sequenceheading:view'] = 'Peut voir le contenu';
$string['sequenceheading:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Titre de séquence';
$string['typename'] = 'Titre de séquence';
$string['configtypename'] = 'Active le type Titre de séquence';
$string['heading'] = 'Titre de séquence';
$string['shortdesc'] = 'Description courte';
$string['imageurl'] = 'Image';
$string['image'] = 'Image de vignette';
$string['imageposition'] = 'Position de l\'image';
$string['left'] = 'Gauche';
$string['right'] = 'Droite';
$string['none'] = 'Non visible';
$string['overimagetext'] = 'Texte sur image';
$string['verticalalign'] = 'Position de l\'image';
$string['top'] = 'Haut';
$string['middle'] = 'Milieu';
$string['bottom'] = 'Bas';

$string['template'] = '
<table class="custombox-sequenceheading" width="100%">
<tr valign="<%%verticalalignoption%%>" class="custombox-icon sequenceheading">
<%%imageL%%>
<td>
<h2 class="custombox-caption sequenceheading"><%%heading%%></h2>
<p class="custombox-description sequenceheading"><%%shortdesc%%></p>
</td>
<%%imageR%%>
</tr>
</table>';