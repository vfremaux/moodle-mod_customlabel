<?php

$string['worktodo:view'] = 'Peut voir le contenu';
$string['worktodo:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Eléments de cours : Consignes';
$string['typename'] = 'Consignes';
$string['configtypename'] = 'Active le type Consignes';
$string['worktypefield'] = 'Type de travail';
$string['workeffortfield'] = 'Difficulté';
$string['workmodefield'] = 'Modalité';
$string['estimatedworktime'] = 'Durée estimée';
$string['worktodo'] = 'Travail à effectuer';
$string['linktomodule'] = 'Activité liée';

// Qualifier values

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
    <td class="custombox-header-thumb worktodo" width="2%" rowspan="3">
    </td>
    <td class="custombox-header-caption worktodo" width="98%" colspan="2">
        Travail à faire
    </td>
</tr>
<tr valign="top">
    <td class="custombox-worktype worktodo" colspan="2">
        <span class="custombox-param worktodo">Type :</span> <span class="custombox-value worktodo"><%%worktypefield%%></span>
    </td>
    <td class="custombox-timeexpected worktodo" align="right" width="40">
        <img src="/mod/customlabel/type/worktodo/clock.jpg" /> <%%estimatedworktime%%>
    </td>
</tr>
<tr valign="top">
    <td class="custombox-worktype worktodo">
        <span class="custombox-param worktodo">Difficulté :</span> <span class="custombox-value worktodo"><%%workeffortfield%%></span>
    </td>
    <td class="custombox-workmode worktodo" align="right" colspan="3">
        <span class="custombox-param worktodo">Modalité :</span> <span class="custombox-value worktodo"><%%workmodefield%%></span>
    </td>
</tr>
<tr>
    <td class="custombox-content worktodo" colspan="2">
        <%%worktodo%%>
    </td>
</tr>
</table>
';