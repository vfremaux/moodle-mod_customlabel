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
 * This is a local class contextual translation file for field names and list options.
 * this file is automatically loaded by the /mod/customlabel/lang/xx_utf8/customlabel.php
 * module language file.
 *
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['coursedata:view'] = 'Peut voir le contenu';
$string['coursedata:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&ensp;: Information sur le cours';
$string['typename'] = 'Information sur le cours';
$string['configtypename'] = 'Active le type Information sur le cours';
$string['goals'] = 'Objectifs&ensp;';
$string['concepts'] = 'concepts clefs ';
$string['teachingorganization'] = 'Organisation pédagogique ';
$string['objectives'] = 'Acquisitions';
$string['duration'] = 'Durée du parcours&ensp;';
$string['prerequisites'] = 'Pré requis nécessaires';
$string['learningmethod'] = 'Méthode(s) d\'enseignement&ensp;';
$string['followers'] = 'Suites possibles&ensp;';
$string['tablecaption'] = 'Titre de la table';
$string['trainingid'] = 'Identifiant';
$string['public'] = 'Public';
$string['showgoals'] = 'Afficher les objectifs';
$string['showconcepts'] = 'Afficher les concepts clefs&ensp;';
$string['showteachingorganization'] = 'Afficher l\'Organisation pédagogique&ensp;';
$string['showobjectives'] = 'Afficher les acquisitions';
$string['showduration'] = 'Afficher la durée du parcours&ensp;';
$string['showprerequisites'] = 'Afficher les pré requis nécessaires';
$string['showlearningmethod'] = 'Afficher les méthode(s) d\'enseignement&ensp;';
$string['showfollowers'] = 'Afficher les suites possibles&ensp;';
$string['showtarget'] = 'Afficher le public&ensp;';
$string['target'] = 'Public cible&ensp;';
$string['teachingorg'] = 'Organisation pédagogique';
$string['leftcolumnratio'] = 'Largeur colonne gauche';

$string['template'] = '
<table class="custombox-coursedata">
    <tr valign="top">
        <th class="custombox-title coursedata" colspan="2">
            <%%tablecaption%%>
        </th>
    </tr>
    <%if %%showidnumber%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Numéro d\'identification&nbsp;:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%idnumber%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showtarget%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Public&nbsp;:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%target%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showgoals%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Objectifs&nbsp;:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%goals%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showobjectives%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Acquisitions&nbsp;:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%objectives%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showconcepts%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Concepts&nbsp;:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%concepts%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showduration%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Durée :
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%duration%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showteachingorganization%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Organisation de l\'enseignement&nbsp;:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%teachingorganization%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showprerequisites%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Prérequis&nbsp;:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%prerequisites%%>
        </td>
    </tr>
    <%endif %>
    <%if %%showfollowers%% %>
    <tr valign="top">
        <td class="custombox-param coursedata" width="<%%leftcolumnratio%%>">
            Cours suivants&nbsp;:
        </td>
        <td class="custombox-value coursedata" width="<%%rightcolumnratio%%>">
            <%%followers%%>
        </td>
    </tr>
    <%endif %>
</table>';