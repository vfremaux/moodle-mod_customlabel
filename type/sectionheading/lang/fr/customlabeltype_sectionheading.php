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
 * @package    customlabeltype_sectionheading
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['sectionheading:view'] = 'Peut voir le contenu';
$string['sectionheading:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: Titre de section';
$string['typename'] = 'Titre de section';
$string['configtypename'] = 'Active le type Titre de section';
$string['heading'] = 'Titre de section&nbsp;';
$string['shortdesc'] = 'Description courte&nbsp;';
$string['imageposition'] = 'Position de l\'image&nbsp;';
$string['imagewidth'] = 'Largeur de l\'image';
$string['none'] = 'Pas d\'image';
$string['left'] = 'A gauche';
$string['right'] = 'A droite';
$string['imageurl'] = 'Image';
$string['image'] = 'Fichier vignette';
$string['overimagetext'] = 'Texte sur image&nbsp;';

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
