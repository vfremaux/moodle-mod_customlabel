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
 * Post-install code for the customlabel module.
 *
 * @package     mod_customlabel
 * @category    mod
 * @copyright   2013 Valery Fremaux
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Defaults as an array of
 * type, code, plugin name, destination table (types)
 * typecode, code, plugin name, destination table (values)
 */
function xmldb_customlabel_qualifier_defaults($typesorvalues = 'types') {

    if ($typesorvalues == 'types') {
        return array(
            array('filter', 'WORKEFFORT', 'customlabeltype_worktodo', 'customlabel_mtd_type'),
            array('filter', 'WORKMODE', 'customlabeltype_worktodo', 'customlabel_mtd_type'),
            array('filter', 'WORKTYPE', 'customlabeltype_worktodo', 'customlabel_mtd_type')
        );
    }

    return array(
        array('WORKEFFORT', 'NQ', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKEFFORT', 'VERYEASY', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKEFFORT', 'EASY', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKEFFORT', 'MEDIUM', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKEFFORT', 'HARD', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKEFFORT', 'VERYHARD', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),

        array('WORKMODE', 'NQ', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKMODE', 'ALONEONLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKMODE', 'ALONEOFFLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKMODE', 'TEAMONLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKMODE', 'TEAMOFFLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKMODE', 'COURSEONLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKMODE', 'COURSEOFFLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKMODE', 'COACHSYNCHRONOUS', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKMODE', 'COACHASYNCHRONOUS', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),

        array('WORKTYPE', 'NQ', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKTYPE', 'TRAINING', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKTYPE', 'WRITING', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKTYPE', 'INFOQUEST', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKTYPE', 'EXERCISE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKTYPE', 'PROJECT', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKTYPE', 'EXPERIMENT', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0),
        array('WORKTYPE', 'SYNTHESIS', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0)
    );
}

/**
 * Code run after the quiz module database tables have been created.
 */
function xmldb_customlabel_install() {
    global $DB;

    // Work effort.

    $params = array('type' => 'filter', 'code' => 'WORKEFFORT');
    if ($DB->record_exists('customlabel_mtd_type', $params)) {
        // Was already installed once.
        return;
    }

    $types = xmldb_customlabel_qualifier_defaults('types');
    $i = 0;
    $installedtypes = array();
    foreach ($types as $type) {
        list($fieldtype, $code, $plugin, $table) = $type;
        $record = new stdClass();
        $record->type = $fieldtype;
        $record->code = $code;
        $record->name = xmldb_customlabel_build_multilang($code, $plugin);
        $record->description = xmldb_customlabel_build_multilang($code.'_desc', $plugin);
        $record->sortorder = $i;
        $installedtypes[$code] = $DB->insert_record($table, $record);
        $i++;
    }

    $values = xmldb_customlabel_qualifier_defaults('values');
    $i = 0;
    foreach ($values as $value) {
        list($fieldcode, $code, $plugin, $table, $parent) = $value;
        $record = new stdClass();
        $record->typeid  = $installedtypes[$fieldcode];
        $record->code = $code;
        $record->value = xmldb_customlabel_build_multilang($code, 'customlabeltype_worktodo');
        $record->translatable = 0;
        $record->sortorder = $i;
        $record->parent = $parent;
        $DB->insert_record($table, $record);
        $i++;
    }
}

/**
 * Reload qualifier default strings with all languages available.
 *
 */
function xmldb_customlabel_build_multilang($code, $plugin) {

    $tag = 'span';
    if (preg_match('/_desc', $code)) {
        $tag = 'div';
    }

    // Find all the languages used.
    if (!empty($CFG->langlist)) {
        $langs = explode(',', $CFG->langlist);
    } else {
        $langs = get_string_manager()->get_list_of_translations();
    }

    foreach (array_keys($langs) as $lang) {
        $langstring = new lang_string($code, $plugin, '', $lang);
        $langvalues[] = '<'.$tag.' class="multilang" lang="'.$lang.'">'.$langstring->out().'</'.$tag.'>';
    }

    return implode('', $langvalues);
}