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
 * When a component wants to have a direct interaction with this module the right way
 * to use is : 
 *
 * if (file_exists($CFG->dirroot.'/path/to/component/xlib.php')) {
 *    include_once($CFG->dirroot.'/path/to/component/xlib.php');
 *    $call_api_xlib_function = 'somefunction';
 *    $call_api_xlib_function();
 * }
 *
 * @author Valery Fremaux (valery.fremaux@gmail.com)
 */
 
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