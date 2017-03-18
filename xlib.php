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