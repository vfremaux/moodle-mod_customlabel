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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

$string['customlabel:addinstance'] = 'Peut ajouter une instance';
$string['customlabel:fullaccess'] = 'Accès total';
$string['customlabel:managemetadata'] = 'Gérer les métadonnées de classification';

$string['apparence'] = 'Apparence';
$string['coursefilter'] = 'Filtre sur les cours';
$string['configusesafestorage'] = 'Si activé, le contenu des étiquettes sera converti en stockage sûr au moment de leur édition. Le nouveau stockage sera utilisé à partir de ce moment.';
$string['changetypeadvice'] = 'Vous allez changer la structure de cet élement.\\\\nLes anciennes données ne peuvent être conservées.\\\\nVoulez-vous continuer ?';
$string['cleararea'] = 'Vider cette zone de fichiers';
$string['customlabeltools'] = 'Outils de masse pour les éléments personnalisables';
$string['cssoverrides'] = 'Surcharges CSS';
$string['cssoverridesdesc'] = 'Si ce champ est rempli avec des règles de style, alors ces règles seront rajoutées à la feuille de style générale pour modifier l\'apparence des elements de cours. Ceci nécessite une intervention dans le theme pour définir le point d\'insertion. Voir le fichier README.txt';
$string['disabledsubtypes'] = 'Types d\'éléments désactivés';
$string['disabledsubtypesdesc'] = 'Tout type mentionné dans ce champ sera désactivé au niveau site. Les contenus existants pour ce type d\'éléments seront ignorés.';
$string['customlabelplugins'] = 'Plugins';
$string['settings'] = 'Réglages';
$string['managecustomlabeltypeplugins'] = 'Gérer les sous-plugins de l\'élément de cours';
$string['doupdate'] = 'Régénérer !!';
$string['exportdata'] = 'Exporter les données en XML';
$string['hiddenrolesfor'] = 'Rôles n\'ayant pas accès au(x)&ensp;';
$string['labelclass'] = 'Type d\'élément&ensp;';
$string['labelclasses'] = 'Classes d\'éléments';
$string['labelupdater'] = 'Outil de régénération {$a}';
$string['languages'] = 'Langue';
$string['lockedsample'] = 'Exemple de champ verouillé';
$string['missingfields'] = 'Des champs obligatoires ne sont pas initialisés.';
$string['modulename'] = 'Elément de cours';
$string['pluginname'] = 'Eléments de cours';
$string['modulenameplural'] = 'Eléments de cours';
$string['name'] = 'Label';
$string['nocontentforthislanguage'] = 'Aucun contenu disponible pour ce langage<br/>';
$string['pluginadministration'] = 'Paramètres de l\'élément de cours';
$string['regenerate'] = 'Régénerer';
$string['regeneration'] = 'Régénération des contenus';
$string['resourcetypecustomlabel'] = 'Elément de cours';
$string['roleaccesstoelements'] = 'Gestion des accès par rôle';
$string['sametypes'] = 'Vous ne pouvez mettre des contraines entre un type et lui-même';
$string['specifics'] = 'Réglages spécifiques';
$string['storage'] = 'Mode de stockage';
$string['title'] = 'Nom de l\'élément&ensp;';
$string['updateall'] = 'Régénérer tous les éléments';
$string['updatelabels'] = 'Régénération des instances de {$a}';
$string['updatescope'] = 'Portée de la mise à jour';
$string['usesafestorage'] = 'Utiliser le stockage sûr (base64)';
$string['typecode'] = 'Code';
$string['show'] = 'Montrer&ensp;';
$string['typetype'] = 'Type';

$string['typecode_help'] = '
<p>Ce code peut aider les extractions et exploitations de données dans des systèmes tiers à partir d\'extractions produites dans les blocs
Rapports configurables ou le bloc Tableau de Bord.</p>

<p>Notez que certains codes sont implicitement liées à certaines fonctionnalités comme par exemple
l\'indexation de cours (Element Classification de cours). Cet élément est un utilitaire qui permet de
tagguer un cours et de le proposer à un moteur de présetentation de catalogue de cours (Composant local Course Index).</p>
<ul>
<li>LEVEL0 : Utilisez ce code pour construire une première dimension de classification pour le classifieur de cours.</li>
<li>LEVEL1 : Utilisez ce code pour construire une deuxième dimension de classification pour le classifieur de cours.</li>
<li>LEVEL2 : Utilisez ce code pour construire une troisième dimension de classification pour le classifieur de cours.</li>
<li>PEOPLE : Utilisez ce code pour construite un filtre sur les audiences et publics du cours.</li>
</ul>

</p>Pour le type Travail à faire: </p>
<ul>
<li>WORKEFFORT : Créez un qualifier de l\'effort d\'apprentissage</li>
<li>WORKTYPE : Créez un qualifieur de la nature du travail proposé</li>
<li>WORKMODE : Créez un qualifieur du périmètre social du travail proposé</li>
</ul>
';

$string['typetype_help'] = '
<p>Le type peut être:</p>
<ul>
<li>Une catégorie : Elle est éligible à des mécanismes de recherche par critères successifs.</li>
<li>Un filtre : Un tag simple utilisable par des éléments de cours</p>
<li>Un filtre de cours : Un tag simple qui pourra être utilisé par des moteurs de catalogues de cours</p>
</ul>
';

$string['modulename_help'] = 'Les éléments de cours sont des modules de contenus qui constituent des briques pédagogiques.
Les éléments de cours ont un sous-type qui rencontre la plupart des actions d\'apprentissage communes : consigne de travail,
élément de solution, référence accessoire, objectifs, rubrique à retenir, ainsi que des briques éditoriales comme
les titres de cours, ou la bibliographie. Les administrateurs peuvent redéfinir des nouveaux type et y introduire
une politique éditoriale spécifique. Les éléments de cours prennent en charge la "mise en forme" du contenu et facilitent
le travail de l\'auteur.
';

// Metadata.
$string['adminmetadata'] = 'Administration des classifieurs';
$string['metadata'] = 'Valeurs de classification';
$string['metadataset'] = 'Valeurs de classification';
$string['classifiers'] = 'Classifieurs';
$string['qualifiers'] = 'Valeurs de classification';
$string['classifierstypes'] = 'Types de classifieurs';
$string['classification'] = 'Classification';
$string['classificationvalues'] = 'Valeurs';
$string['constraints'] = 'Contraintes';
$string['commands'] = 'Commandes';
$string['typename'] = 'Nom';
$string['typetype'] = 'Type';
$string['noclassifiers'] = 'Aucun critère de classification';
$string['metadata :'] = 'Métadonnée';
$string['editclass'] = 'Edition de la classe&ensp;';
$string['category'] = 'Catégorie&ensp;';
$string['filter'] = 'Filtre&ensp;';
$string['usedas'] = 'Utilisé en tant que';
$string['none'] = 'Non défini';
$string['include'] = 'Inclure';
$string['exclude'] = 'Exclure';
$string['value'] = 'Valeur';
$string['code'] = 'Code';
$string['novalues'] = 'Aucune valeur enregistrée';
$string['notypes'] = 'Aucun classifieur défini';
$string['model'] = 'Data Model';
$string['classificationmodel'] = 'Modèle de données pour classification';
$string['classificationtypetable'] = 'Table des domaines';
$string['classificationtypetable_help'] = 'This table provides domains of classification. A domain holds a set of values.';
$string['classificationvaluetable'] = 'Table des valeurs de domaine';
$string['classificationvaluetable_help'] = 'This table provides all values for all classifiers defined in the Type Table.';
$string['classificationvaluetypekey'] = 'Clef de type pour les valeurs';
$string['classificationvaluetypekey_help'] = 'This must define the table column name that is used to key the type ownership on values.';
$string['classificationconstrainttable'] = 'Table des contraintes';
$string['classificationconstrainttable_help'] = 'This table is capable to map the value pairs wich are not compatible.';
$string['coursemetadatatable'] = 'Table des métadonnées de cours';
$string['coursemetadatatable_help'] = 'This table provides course to metadata bindings.';
$string['coursemetadatavaluekey'] = 'Clef des valeurs (métadonnées de cours)';
$string['coursemetadatavaluekey_help'] = 'This must define a column name in database that maps a record to a metadata value.';
$string['coursemetadatacoursekey'] = 'Clef de cours (métadonnées de cours)';
$string['coursemetadatacoursekey_help'] = 'This must define a column name in database that maps a record to a course ID.';

// Known types.
$string['text'] = 'Texte';
$string['content'] = 'Contenu&ensp;';

$string['exportdata'] = 'Exporter les données';

// Kown families.
$string['familystructure'] = 'Eléments de structure';
$string['familygeneric'] = 'Eléments génériques';
$string['familypedagogic'] = 'Eléments pédagogiques';
$string['familymeta'] = 'Eléments méta';
$string['familyspecial'] = 'Eléments spéciaux';
