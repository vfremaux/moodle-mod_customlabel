<?php

/**
 * This is a local class contextual translation file for field names and 
 * list options.
 * this file is automatically loaded by the 
 * /mod/customlabel/lang/xx_utf8/customlabel.php
 * module language file.
 */

$string['coursedata:view'] = 'Peut voir le contenu';
$string['coursedata:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Information sur le cours';
$string['typename'] = 'Information sur le cours';
$string['configtypename'] = 'Active le type Information sur le cours';
$string['goals'] = 'Buts ';
$string['concepts'] = 'concepts clefs ';
$string['teachingorganization'] = 'Organisation pédagogique ';
$string['objectives'] = 'Acquisitions';
$string['duration'] = 'Durée du parcours ';
$string['prerequisites'] = 'Pré requis nécessaires';
$string['learningmethod'] = 'Méthode(s) d\'enseignement ';
$string['followers'] = 'Suites possibles ';
$string['tablecaption'] = 'Titre de la table';
$string['showgoals'] = 'Afficher les objectifs';
$string['showconcepts'] = 'Afficher les concepts clefs ';
$string['showteachingorganization'] = 'Afficher l\'Organisation pédagogique ';
$string['showobjectives'] = 'Afficher les acquisitions';
$string['showduration'] = 'Afficher la durée du parcours ';
$string['showprerequisites'] = 'Afficher les pré requis nécessaires';
$string['showlearningmethod'] = 'Afficher les méthode(s) d\'enseignement ';
$string['showfollowers'] = 'Afficher les suites possibles ';
$string['showtarget'] = 'Afficher le public ';
$string['target'] = 'Public cible ';
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
    <%if %%followers%% %>
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