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
 * A dynamic styling helper.
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux <valery.fremaux@gmail.com> (www.activeProLearn.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

/*
 * In this special case, this is access to CSS resource that should be obtained even if not logged in.
 * phpcs:disable moodle.Files.RequireLogin.Missing
 */

require('../../config.php');

$type = required_param('type', PARAM_TEXT);
$subtype = optional_param('subtype', '', PARAM_TEXT);
$theme = optional_param('theme', '', PARAM_TEXT);

$skin = get_config('customlabel', 'defaultskin');

$themesupportscustomlabeldefaults = false;
if (!empty($theme)) {
    // Is our theme equiped to manage customlabel theme level styling.
    // This styling should override default.
    $themeskin = get_config('theme_'.$theme, 'customlabelskin');
    if (!empty($themeskin)) {
        $themesupportscustomlabeldefaults = true;
        $skin = $themeskin;
    }
}

header('Content-type: text/css');

$baseurl = 'type/'.$type.'/pix';

if (!in_array($skin, ['default', 'flatstyle', 'colored', 'flatstyle colored'])) {
    // Customized type.
    $baseurl = 'pix/skins/'.$skin;
}

$scsscode = '';

// This one NEEDS TO BE.
$csscode = implode('', file($CFG->dirroot.'/mod/customlabel/type/'.$type.'/customlabel.css'));

if (!$themesupportscustomlabeldefaults) {
    // Load defaults (f.e. colors) only if theme did not provide any.
    if (file_exists($CFG->dirroot.'/mod/customlabel/type/'.$type.'/defaults.css')) {
        $csscode .= implode('', file($CFG->dirroot.'/mod/customlabel/type/'.$type.'/defaults.css'));
    }
}
if (file_exists($CFG->dirroot.'/mod/customlabel/type/'.$type.'/skined.css')) {
    // Load skin fragment if there is one.
    $csscode .= implode('', file($CFG->dirroot.'/mod/customlabel/type/'.$type.'/skined.css'));
}

$csscode = str_replace('{{baseurl}}', $baseurl, $csscode);

// If it is the case, add rel base path.
$relpath = preg_replace('#^https?\\:\\/\\/[^\\/]+#', '', $CFG->wwwroot);
$csscode = str_replace('url("/mod', "url(\"{$relpath}/mod", $csscode);

echo $csscode;
