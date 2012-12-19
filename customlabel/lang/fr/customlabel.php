<?php
global $CFG;

$string['configusesafestorage'] = 'Si activé, le contenu des étiquettes sera converti en stockage sûr au moment de leur édition. Le nouveau stockage sera utilisé à partir de ce moment.';
$string['changetypeadvice'] = 'Vous allez changer la structure de cet élement.\\\\nLes anciennes données ne peuvent être conservées.\\\\nVoulez-vous continuer ?';
$string['customlabel:addinstance'] = 'Peut ajouter une instance';
$string['customlabel:fullaccess'] = 'Accès total ';
$string['customlabeltools'] = 'Outils de masse pour les éléments personnalisables';
$string['doupdate'] = 'Régénérer !!';
$string['exportdata'] = 'Exporter les données en XML';
$string['hiddenrolesfor'] = 'Rôles n\'ayant pas accès au(x) ';
$string['labelclass'] = 'Type d\'élément ';
$string['labelclasses'] = 'Classes d\'éléments';
$string['labelupdater'] = 'Outil de régénération {$a}';
$string['languages'] = 'Langue';
$string['missingfields'] = 'Des champs obligatoires ne sont pas initialisés.';
$string['modulename'] = 'Elément de cours';
$string['pluginname'] = 'Eléments de cours';
$string['modulenameplural'] = 'Elements de cours';
$string['name'] = 'Label';
$string['nocontentforthislanguage'] = 'Aucun contenu disponible pour ce langage<br/>';
$string['pluginadministration'] = 'Paramètres de l\'élément de cours';
$string['regenerate'] = 'Régénerer';
$string['regeneration'] = 'Régénération des contenus';
$string['resourcetypecustomlabel'] = 'Insérer un élément de cours';
$string['roleaccesstoelements'] = 'Gestion des accès par rôle';
$string['sametypes'] = 'Vous ne pouvez mettre des contraines entre un type et lui-même';
$string['storage'] = 'Mode de stockage';
$string['title'] = 'Nom de l\'élément ';
$string['updateall'] = 'Régénérer tous les éléments';
$string['updatelabels'] = 'Régénération des instances de {$a}';
$string['updatescope'] = 'Portée de la mise à jour';
$string['usesafestorage'] = 'Utiliser le stockage sûr (base64)';

// Metadata
$string['adminmetadata'] = 'Administration des classifieurs';
$string['metadata'] = 'Valeurs de classification';
$string['metadataset'] = 'Valeurs de classification';
$string['classifiers'] = 'Classifieurs';
$string['qualifiers'] = 'Valeurs de classification';
$string['classifierstypes'] = 'Types de classifieurs';
$string['classification'] = 'Classification';
$string['constraints'] = 'Contraintes';
$string['commands'] = 'Commandes';
$string['typename'] = 'Nom';
$string['typetype'] = 'Type';
$string['noclassifiers'] = 'Aucun critère de classification';
$string['metadata :'] = 'Métadonnée';
$string['editclass'] = 'Edition de la classe ';
$string['category'] = 'Catégorie ';
$string['filter'] = 'Filtre ';
$string['usedas'] = 'Utilisé en tant que';
$string['none'] = 'Non défini';
$string['include'] = 'Inclure';
$string['exclude'] = 'Exclure';
$string['value'] = 'Valeur';
$string['code'] = 'Code';
$string['novalues'] = 'Aucune valeur enregistrée';
$string['notypes'] = 'Aucun classifieur défini';


// known types
$string['text'] = 'Texte';
$string['content'] = 'Contenu ';

$string['exportdata'] = 'Exporter les données';

if (!function_exists('local_customlabel_get_classes')){
    function local_customlabel_get_classes(){
        global $CFG;
        
        $classes = array();
        $basetypedir = $CFG->dirroot."/mod/customlabel/type";
        
        $classdir = opendir($basetypedir);
        while ($entry = readdir($classdir)){
            if (preg_match("/^[.!]/", $entry)) continue; // ignore what need to be ignored
            if (!is_dir($basetypedir.'/'.$entry)) continue; // ignore real files
            unset($obj);
            $obj->id = $entry;
            $classes[] = $obj;
        }
        closedir($classdir);
        return $classes;
    }
}


// get strings for known types
$classes = local_customlabel_get_classes();
if (!empty($classes)){
    foreach($classes as $atype){
        $typelangfile = $CFG->dirroot."/mod/customlabel/type/{$atype->id}/fr/customlabel.php";
        if (file_exists($typelangfile)){
            include_once($typelangfile);
        }
    }
}
