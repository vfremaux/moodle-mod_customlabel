<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Lang file.
 *
 * @package    customlabeltype_sequenceheading
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['sequenceheading:view'] = 'Peut voir le contenu';
$string['sequenceheading:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: Titre de séquence';
$string['typename'] = 'Titre de séquence';
$string['configtypename'] = 'Active le type Titre de séquence';
$string['heading'] = 'Titre de séquence';
$string['shortdesc'] = 'Description courte';
$string['imageurl'] = 'Image';
$string['image'] = 'Image de vignette';
$string['imageposition'] = 'Position de l\'image';
$string['imagewidth'] = 'Largeur de l\'image';
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
