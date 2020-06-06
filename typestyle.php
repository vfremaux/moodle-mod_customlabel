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
 * Library of functions and constants for module label.
 *
 * disabled length limitation for labels
 * define("LABEL_MAX_NAME_LENGTH", 50);
 */
require('../../config.php');

$type = required_param('type', PARAM_TEXT);
$subtype = optional_param('subtype', '', PARAM_TEXT);
$theme = optional_param('theme', '', PARAM_TEXT);

$skin = get_config('customlabel', 'defaultskin');

if (!empty($theme)) {
    $themeskin = get_config('theme_'.$themename, 'customlabelskin');
    if (!empty($themeskin)) {
        $skin = $themeskin;
    }
}

header('Content-type: text/css');

$baseurl = 'type/'.$type.'/pix';

if (!in_array($skin, ['default', 'flatstyle', 'coloured', 'flatstyle coloured'])) {
    // Customized type.
    $baseurl = 'pix/skins/'.$skin;
}

$csscode = implode('', file($CFG->dirroot.'/mod/customlabel/type/'.$type.'/customlabel.css'));

$csscode = str_replace('{{baseurl}}', $baseurl, $csscode);

echo $csscode;