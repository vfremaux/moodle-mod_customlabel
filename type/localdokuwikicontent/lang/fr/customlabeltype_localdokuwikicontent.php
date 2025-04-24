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
 * @package    customlabeltype_localdokuwikicontent
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['localdokuwikicontent:view'] = 'Peut voir le contenu';
$string['localdokuwikicontent:addinstance'] = 'Peut ajouter une instance';

$string['advice'] = 'Attention';
$string['basedir'] = 'Répertoire de base du dokuwiki local ';
$string['accesstoken'] = 'Clef d\'accès';
$string['configbasedir'] = 'Répertoirede base du wiki';
$string['configbasedir_desc'] = 'La localisaton physique des contenus de wiki sur le serveur local. Ce chemin doit pointer la racine d\'installation du dokuwiki.';
$string['configaccesstoken'] = 'Token d\'accès par défaut';
$string['configaccesstoken_desc'] = 'Le token permet de passer les contrôles d\'accès mis en place par un plugin "lib/authtoken" de dokuwiki.';
$string['configdefaultlocal'] = 'Localisation du wiki par défaut';
$string['configdefaultlocal_desc'] = 'Valeur par défaut du commutateur de source. Local : même serveur, ou "distant", accessible par URL.';
$string['configdefaultremotehost'] = 'Hôte distant par défaut';
$string['configdefaultremotehost_desc'] = 'S\'appliquera par défaut aux instances configurées en "distant", sauf si un hôte explicite est mentionné.';
$string['configdefaultremotehost'] = 'Token distant par défaut';
$string['configdefaultremotehost_desc'] = 'S\'appliquera par défaut aux instances configurées en "distant", sauf si un token explicite est mentionné. Le token permet de passer les contrôles d\'accès mis en place par un plugin "lib/authtoken" de dokuwiki.';
$string['configtypename'] = 'Active le type contenu de wiki';
$string['contentnotreachable'] = 'Le contenu ne peut être joint ou décodé du fournisseur distant. Vérifiez l\'ID de page ou la configuration du wiki.';
$string['contentpage'] = 'Page wiki&nbsp;';
$string['errormissingpage'] = 'Page de wiki non trouvée. {$a}';
$string['errornowiki'] = 'Pas de volume wiki dans le chemin défini dans la configuration. {$a}';
$string['heading'] = 'Titre de page ';
$string['hideelements'] = 'Cacher les éléments HTML ';
$string['local'] = 'Contenu local';
$string['localwiki'] = 'contenu de Wiki local';
$string['localcss'] = 'CSS locale ';
$string['notconfigured'] = 'Les coordonnées du Wiki ne sont pas configurées dans les réglages globaux.';
$string['partiallyconfigured'] = 'Le wiki est partiellement configuré dans les réglages globaux. Toutes les langues activées ne trouvent pas un wiki cible valide.';
$string['pluginname'] = 'Elément de cours : Contenu de wiki';
$string['removelinks'] = 'Supprimer les liens ';
$string['typename'] = 'Contenu de wiki';
$string['webroot'] = 'URL Wiki de base&nbsp;';
$string['remotehost'] = 'Fournisseur de contenus distant';

$string['localwiki_help'] = 'Si le wiki est local, le contenu sera directement lu sur le même serveur que Moodle. Sinon, il sera récupéré par URL. L\'accès local est plus performant.';
$string['remotehost_help'] = 'Si le wiki est distant, et que ce paramètre est défini, il s\'agit de l\'URL d\'accès de base au wiki. ';
$string['accesstoken_help'] = 'Si le wiki est distant, et que ce paramètre est défini, il s\'agit du token d\'accès du wiki. ';
$string['contentpage_help'] = 'Entrez l\'identifiant de page. Vous trouvez en général cet identifiant dans le paramètre "id" lors de la consultation directe par "doku.php". ';

