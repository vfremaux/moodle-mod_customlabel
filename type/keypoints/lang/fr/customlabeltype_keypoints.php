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

$string['keypoints:view'] = 'Peut voir le contenu';
$string['keypoints:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: A retenir';
$string['typename'] = 'A retenir';
$string['configtypename'] = 'Enable subtype Key points';
$string['keypoints'] = 'Rubrique \"A retenir\"&nbsp;';
$string['keypointnum'] = 'Nombre de points&nbsp;';
$string['keypointitem0'] = 'Point 1&nbsp;';
$string['keypointitem1'] = 'Point 2&nbsp;';
$string['keypointitem2'] = 'Point 3&nbsp;';
$string['keypointitem3'] = 'Point 4&nbsp;';
$string['keypointitem4'] = 'Point 5&nbsp;';
$string['keypointitem5'] = 'Point 6&nbsp;';
$string['keypointitem6'] = 'Point 7&nbsp;';
$string['keypointitem7'] = 'Point 8&nbsp;';
$string['keypointitem8'] = 'Point 9&nbsp;';
$string['keypointitem9'] = 'Point 10&nbsp;';
$string['keypointitem10'] = 'Point 11&nbsp;';
$string['keypointitem11'] = 'Point 12&nbsp;';
$string['keypointitem12'] = 'Point 13&nbsp;';
$string['keypointitem13'] = 'Point 14&nbsp;';
$string['keypointitem14'] = 'Point 15&nbsp;';
$string['keypointitem15'] = 'Point 16&nbsp;';

$string['template'] = '
<table class="custombox-keypoints" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb keypoints" style="background-image : url(<%%icon%%>);" width="2%" rowspan="2">
    </td>
    <td class="custombox-header-caption keypoints" width="98%">
        A retenir !
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content keypoints">
        <%%keypointslist%%>
    </td>
</tr>
</table>';