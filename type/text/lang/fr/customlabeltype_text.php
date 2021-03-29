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

$string['text:view'] = 'Peut voir le contenu';
$string['text:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: Texte double niveau';
$string['typename'] = 'Texte double niveau';
$string['configtypename'] = 'Active le type Texte niveau';
$string['textcontent'] = 'Contenu&nbsp;';
$string['readmorecontent'] = 'Texte supplémentaire';
$string['initiallyvisible'] = 'Visible au chargement&nbsp;';
$string['readmore'] = 'Lire plus...';
$string['readless'] = 'Lire moins...';

$string['family'] = 'generic';

$string['template'] = '
<!-- standard default template for unclassed label. Don\'t change -->
<div class="custombox-text">
<%%textcontent%%>
</div>
<%if %%readmorecontent%% %>
<div class="custombox-text readmorelink">
    <a href="javascript:togglecustomstring(\'<%%customid%%>\', \'Lire plus...\', \'Lire moins...\')" ><span id="customctl<%%customid%%>"><%%initialstring%%></span></a>
</div>
<div class="custombox-text readmore" id="custom<%%customid%%>">
<%%readmorecontent%%>
</div>
<script type="text/javascript">
setupcustomstring(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'Lire plus...\', \'Lire moins...\');
</script>
<%endif %>';