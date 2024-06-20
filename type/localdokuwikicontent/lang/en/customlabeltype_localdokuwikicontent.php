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

$string['localdokuwikicontent:view'] = 'Can view the content';
$string['localdokuwikicontent:addinstance'] = 'Can add an instance';

$string['advice'] = 'Warning';
$string['accesstoken'] = 'Access token';
$string['configaccesstoken'] = 'Access token (default)';
$string['configaccesstoken_desc'] = 'Access token allows passing access control for dokuwikies implementing the "lib/authtoken" plugin.';
$string['configbasedir'] = 'Base wiki dir';
$string['configbasedir_desc'] = 'Physical location of the local wiki. This path must point the root dir of the dokuwiki installation.';
$string['configtypename'] = 'Enable subtype Local Doku Wiki';
$string['configwebroot'] = 'Base wiki URL';
$string['configwebroot_desc'] = 'Base wiki URL for local wiki pages';
$string['configdefaultlocal'] = 'Default local wiki';
$string['configdefaultlocal_desc'] = 'Default value for local switch. If set to remote, the remote configuration will be applied, unless instance specific info is given.';
$string['configdefaultremotehost'] = 'Default remote host';
$string['configdefaultremotehost_desc'] = 'Default host for remote pages';
$string['configdefaultremotetoken'] = 'Default remote token';
$string['configdefaultremotetoken_desc'] = 'Default token for remote service';
$string['contentpage'] = 'Wiki Page';
$string['contentnotreachable'] = 'Content not reachable or not decodable from remote end. You may check your page ID or your wiki settings.';
$string['errormissingpage'] = 'Page wiki not found {$a}';
$string['errornowiki'] = 'No wiki found at the configured basedir {$a}';
$string['heading'] = 'Page title';
$string['hideelements'] = 'Hide elements';
$string['local'] = 'Local content';
$string['lang'] = 'Language';
$string['localcss'] = 'Local css';
$string['notconfigured'] = 'Wiki is not setup in global configuration.';
$string['partiallyconfigured'] = 'Wiki is partially setup in global configuration. This means all active languages do not find a proper target for the wiki.';
$string['pluginname'] = 'Course element: Local dokuwiki content';
$string['removelinks'] = 'Remove links';
$string['remotehost'] = 'Remote host';
$string['typename'] = 'Content';

$string['family'] = 'special';

$string['localwiki_help'] = 'when using a local wiki, the content is directly accessed from thre same server than moodle. elsewhere it is fetched by URL. Local access has usually better performance.';
$string['remotehost_help'] = 'If the wiki is remote and this setting is given, it must be set as the base URL access to the wiki. Global settings can give default value.';
$string['accesstoken_help'] = 'If the wiki is remote, and this parameter is defined, it must be set to a valid access token to the content. Global settings can give default value. ';
$string['contentpage_help'] = 'Enter the page identifier, as it would be mentionned in the "id" url attribute value of the request to "doku.php". ';
