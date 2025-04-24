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
 * Lang FIle.
 *
 * @package    customlabeltype_question
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
$string['question:view'] = 'Peut voir la question';
$string['question:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbdp;: Question';
$string['typename'] = 'Question';
$string['configtypename'] = 'Active le type question';
$string['questiontext'] = 'Texte de la question&nbsp;';
$string['answertext'] = 'Texte de la réponse&nbsp;';
$string['showansweron'] = 'Afficher la réponse à partir de';
$string['willopenon'] = 'Sera visible le';
$string['notavailableyet'] = 'Pas encore disponible';
$string['initiallyvisible'] = 'Réponse visible au chargement&nbsp;';
$string['hint'] = 'Texte d\'indice&nbsp;';
$string['hintinitiallyvisible'] = 'Indice visible au chargement&nbsp;';
$string['question'] = 'Question';
$string['solution'] = 'Réponse';
$string['isqcmchallenge'] = 'Est une question QCM interactive';
$string['shuffleanswers'] = 'Mélanger les réponses';
$string['attempts'] = 'Nombre de tentatives avant verrouillage';
$string['correctanswer'] = 'Réponse correcte';
$string['submitqcm'] = 'Enregistrer la réponse';

$string['completion1'] = 'L\'étudiant a répondu.';
$string['completion2'] = 'L\'étudiant a répondu et a une bonne réponse.';

$string['answertext_help'] = 'Pour une question simple sans comportement QCM, entrez votre réponse comme du texte
formatté. Pour le comportement QCM, entrez une suite de réponses comme des paragraphes en les séparant par un quadruple tiret : "----" :

<p>Exemple :</p>

<pre>
Réponse 1
----
Réponse 2
----
Réponse 3
</pre>

';

$string['isqcmchallenge_help'] = 'Une question interactive QCM permet de répondre directement dans la question et d\'enregistrer sa réponse.
Selon les conditions données pour l\'achèvement, elle pourra participer à l\'achèvement du cours.';

$string['correctanswer_help'] = 'Assurez-vous d\'avoir effectivement une réponse à cet indice. Les réponses sont numérotées implicitement à partir de 1.';
