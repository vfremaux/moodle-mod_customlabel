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

$string['collapsedtext:view'] = 'Can view the content';
$string['collapsedtext:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element: Collapsed Text';
$string['typename'] = 'Collapsed Text';
$string['configtypename'] = 'Enable subtype Collapsed Text';
$string['algorithm'] = 'Algrorithm';
$string['content'] = 'Content';
$string['openall'] = 'Open all';
$string['closeall'] = 'Close all';
$string['chapternum'] = 'Number of chapters';
$string['open'] = 'All open';
$string['closed'] = 'Closed';
$string['firstopen'] = 'First open';
$string['collapsed'] = 'All closed';
$string['toggle'] = 'Toggle';
$string['accordion'] = 'Accordion';
$string['initialstate'] = 'Initial state';
$string['completion1'] = 'User must have open all chapters to complete';

for ($i = 1; $i <= 10; $i++) {
    $string['chaptercaption'.$i] = 'Caption '.$i;
    $string['chaptertext'.$i] = 'Content '.$i;
}

$string['family'] = 'generic';
$string['template'] = '<%%content%%>';
