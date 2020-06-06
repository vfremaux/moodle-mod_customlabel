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
 * xlib.php is a cross-components library for functions that are required
 * from elsewhere in Moodle and not part of the standard Core API
 *
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

/**
 * this function for use in theme_xxx_process_css() function in coordination with a
 * [[customlabel|overrides]] tag placed into any stylesheet of the theme.
 */
function theme_set_customlabelcss($css) {
    $tag = '[[customlabel:overrides]]';
    $config = get_config('mod_customlabel');
    $replacement = @$config->cssoverrides;
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function customlabel_get_authors($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'authordata'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    if (!$potcls) {
        return;
    }

    // At the moment take the first that comes.
    $cl = array_shift($potcls);

    $instance = customlabel_load_class($cl);
    return $instance;
}

function customlabel_get_courseheading($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'courseheading'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    if (!$potcls) {
        return;
    }

    // At the moment take the first that comes.
    $cl = array_shift($potcls);

    $instance = customlabel_load_class($cl);
    return $instance;
}

function customlabel_get_all_classifiers($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'courseclassifier'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    return $potcls;
}

function customlabel_get_classifiers($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'courseclassifier'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    if (!$potcls) {
        return;
    }

    // At the moment take the first that comes.
    $cl = array_shift($potcls);

    $instance = customlabel_load_class($cl);
    return $instance;
}

function customlabel_get_coursedata($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'coursedata'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    if (!$potcls) {
        return;
    }

    // At the moment take the first that comes.
    $cl = array_shift($potcls);

    $instance = customlabel_load_class($cl);
    return $instance;
}