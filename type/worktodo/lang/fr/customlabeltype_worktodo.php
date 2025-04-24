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
 * @package    customlabeltype_worktodo
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['worktodo:view'] = 'Peut voir le contenu';
$string['worktodo:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Eléments de cours&nbsp;: Consignes';
$string['typename'] = 'Consignes';
$string['configtypename'] = 'Active le type Consignes';
$string['worktypefield'] = 'Nature du travail ';
$string['workeffortfield'] = 'Difficulté ';
$string['workmodefield'] = 'Modalité sociale';
$string['showworktypefield'] = 'Montrer la nature du travail';
$string['showworkeffortfield'] = 'Montrer la difficulté';
$string['showworkmodefield'] = 'Montrer la modalité sociale';
$string['nature'] = 'Nature du travail ';
$string['effort'] = 'Effort ';
$string['mode'] = 'Modalité ';
$string['estimatedworktime'] = 'Durée estimée ';
$string['worktodo'] = 'Travail à effectuer ';
$string['linktomodule'] = 'Activité liée ';
$string['unassigned'] = '--- non assigné ---';

// Qualifier values.

$string['NQ'] = 'Non défini';

$string['WORKEFFORT'] = 'Difficulté';
$string['WORKEFFORT_desc'] = 'L\'effort nécessaire (estimé) pour accomplir le travail, par rapport à une moyenne \'normale\'.';
$string['VERYEASY'] = 'Très facile';
$string['EASY'] = 'Facile';
$string['MEDIUM'] = 'Moyen';
$string['HARD'] = 'Difficile';
$string['VERYHARD'] = 'Très difficile';

$string['WORKMODE'] = 'Modalité';
$string['WORKMODE_desc'] = 'L\'environnement relationnel dans lequel se fait le travail.';
$string['ALONEONLINE'] = 'Individuel en ligne';
$string['ALONEOFFLINE'] = 'Individuel hors ligne';
$string['TEAMONLINE'] = 'En équipe en ligne';
$string['TEAMOFFLINE'] = 'En équipe hors ligne';
$string['COURSEONLINE'] = 'En ligne, avec toute la classe';
$string['COURSEOFFLINE'] = 'Hors ligne, avec toute la classe';
$string['COACHSYNCHRONOUS'] = 'Travail synchrone avec le tuteur';
$string['COACHASYNCHRONOUS'] = 'Travail asynchrone avec le tuteur';

$string['WORKTYPE'] = 'Type de travail';
$string['WORKTYPE_desc'] = 'Le type cognitif du travail.';
$string['TRAINING'] = 'Entrainement';
$string['WRITING'] = 'Ecriture de rapport ou de note';
$string['INFOQUEST'] = 'Recherche d\'information';
$string['EXERCISE'] = 'Exercice et application';
$string['PROJECT'] = 'Projet';
$string['EXPERIMENT'] = 'Exéprimentation et découverte';
$string['SYNTHESIS'] = 'Ecriture de synthèse';

$string['template'] = '
<table class="custombox-worktodo" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb worktodo" style="background-image : url(<%%icon%%>);" width="2%" rowspan="3">
    </td>
    <td class="custombox-header-caption worktodo" width="98%" colspan="2">
        Travail à faire
    </td>
</tr>
<tr valign="top">
    <td class="custombox-worktype worktodo" colspan="2">
        <span class="custombox-param worktodo">Type&nbsp;:</span> <span class="custombox-value worktodo"><%%worktypefield%%></span>
    </td>
    <td class="custombox-timeexpected worktodo" align="right" width="40">
        <img src="<%%clock%%>" /> <%%estimatedworktime%%>
    </td>
</tr>
<tr valign="top">
    <td class="custombox-worktype worktodo">
        <span class="custombox-param worktodo">Difficulté&nbsp;:</span> <span class="custombox-value worktodo"><%%workeffortfield%%></span>
    </td>
    <td class="custombox-workmode worktodo" align="right" colspan="3">
        <span class="custombox-param worktodo">Modalité&nbsp;:</span> <span class="custombox-value worktodo"><%%workmodefield%%></span>
    </td>
</tr>
<tr>
    <td class="custombox-content worktodo" colspan="2">
        <%%worktodo%%>
    </td>
</tr>
</table>
';
