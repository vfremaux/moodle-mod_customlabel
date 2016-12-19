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
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

$string['pedagogicadvice:view'] = 'Can view the content';
$string['pedagogicadvice:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : Pedagogic Advice';
$string['typename'] = 'Pedagogic advice';
$string['configtypename'] = 'Enable subtype Pedagogic advice';
$string['advice'] = 'Advising ';
$string['initiallyvisible'] = 'Initially visible';

$string['family'] = 'meta';

$string['template'] = '
<div class="custombox-control pedagogicadvice"><a href="javascript:togglecustom(\'<%%customid%%>\', \'<%%wwwroot%%>\')"><img id="customctl<%%customid%%>" src="<%%initialcontrolimage%%>" /></a>
<span class="custombox-header-caption pedagogicadvice"><b>Pedagogic advice</b></span></div>
<div class="custombox-content pedagogicadvice" id="custom<%%customid%%>">
<p class="custombox-helper pedagogicadvice"><b>(This note is only visible for trainers)</b></p>
<table width="100%" class="custombox-pedagogicadvice">
    <tr valign="top">
        <td class="custombox-thumb pedagogicnote" style="background-image : url(<%%icon%%>);" width="2%"></td>
        <td class="custombox-content pedagogicadvice"><%%advice%%></td>
    </tr>
</table>
</div>
<script type="text/javascript">
setupcustom(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'<%%wwwroot%%>\');
</script>
';