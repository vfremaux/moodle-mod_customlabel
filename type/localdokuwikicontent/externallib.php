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

defined('MOODLE_INTERNAL') || die();

/**
 * @package    customlabeltype_localdokuwikicontent
 * @category   report
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir.'/externallib.php');
require_once($CFG->dirroot.'/mod/customlabel/type/localdokuwikicontent/locallib.php');

class customlabeltype_localdokuwikicontent_external extends external_api {

    public static function get_page($page, $lang = false) {
        global $CFG;

        $input = array('page' => $page,
                       'lang' => $lang);
        $params = self::validate_parameters(self::get_page_parameters(), $input);

        $result = new StdClass;

        $result->content = '<div class="remote"> '.customlabeltype_localwikicontent_get_page_content($page, $lang).'</div>';

        $config = get_config('customlabeltype_localdokuwikicontent');
        $webroot = $config->webroot;
        $result->webroot = str_replace('{lang}', $lang, $webroot);

        return $result;
    }

    public static function get_page_parameters() {
        return new external_function_parameters(
            array(
                'page' => new external_value(PARAM_TEXT, 'Wiki page'),
                'lang' => new external_value(PARAM_TEXT, 'Volume language'),
            )
        );
    }

    public static function get_page_returns() {
        return new external_single_structure(
            array(
                'content' => new external_value(PARAM_RAW, 'Wiki page content'),
                'webroot' => new external_value(PARAM_TEXT, 'Wiki page web root'),
            )
        );
    }
}