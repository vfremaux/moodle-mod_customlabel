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
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

// Privacy.
$string['privacy:metadata'] = 'Le composant d\'élément de cours CollapsedText ne détient directement aucune donnée relative aux utilisateurs.';

$string['collapsedtext:view'] = 'Peut voir le contenu';
$string['collapsedtext:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Texte à tiroir';
$string['typename'] = 'Texte à tiroir';
$string['configtypename'] = 'Activer le type Texte à tiroir';
$string['content'] = 'Contenu';
$string['caption'] = 'Titre';
$string['openall'] = 'Tout ouvrir';
$string['closeall'] = 'Tout fermer';
$string['algorithm'] = 'Comportement';
$string['chapternum'] = 'Nombre de paragraphes';
$string['open'] = 'Ouvert';
$string['closed'] = 'Fermé';
$string['firstopen'] = 'Le premier ouvert';
$string['collapsed'] = 'Fermé';
$string['toggle'] = 'Basculement';
$string['accordion'] = 'Accordéon';
$string['initialstate'] = 'Etat initial';
$string['completion1'] = 'Les étudiants doivent avoir ouvert tous les chapitres pour achever.';

for ($i = 1; $i <= 30; $i++) {
    $string['chaptercaption'.$i] = 'Titre '.$i;
    $string['chaptertext'.$i] = 'Paragraphe '.$i;
}

$string['family'] = 'generic';
