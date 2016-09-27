<?php

$string['unitheading:view'] = 'Peut voir le contenu';
$string['unitheading:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Titre d\'unité';
$string['typename'] = 'Titre d\'unité';
$string['configtypename'] = 'Active le type Titre d\'unité';
$string['heading'] = 'Titre d\'unité';
$string['shortdesc'] = 'Description courte';
$string['imageposition'] = 'Position de l\'image';
$string['none'] = 'Pas d\'image';
$string['left'] = 'A gauche';
$string['right'] = 'A droite';
$string['imageurl'] = 'Image';
$string['image'] = 'Vignette';
$string['overimagetext'] = 'Texte sur image';

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