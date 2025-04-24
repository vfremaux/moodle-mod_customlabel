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
 * Lang file
 *
 * @package    customlabeltype_unitheading
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['unitheading:view'] = 'Peut voir le contenu';
$string['unitheading:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: Titre d\'unité';
$string['typename'] = 'Titre d\'unité';
$string['configtypename'] = 'Active le type Titre d\'unité';
$string['heading'] = 'Titre d\'unité&nbsp;';
$string['shortdesc'] = 'Description courte&nbsp;';
$string['imageposition'] = 'Position de l\'image&nbsp;';
$string['imagewidth'] = 'Largeur de l\'image';
$string['none'] = 'Pas d\'image';
$string['left'] = 'A gauche';
$string['right'] = 'A droite';
$string['imageurl'] = 'Image';
$string['image'] = 'Vignette';
$string['overimagetext'] = 'Texte sur image&nbsp;';

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
