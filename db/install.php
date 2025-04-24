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
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux (www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

/**
 * Defaults as an array of
 * type, code, plugin name, destination table (types)
 * typecode, code, plugin name, destination table (values)
 */
function xmldb_customlabel_qualifier_defaults($typesorvalues = 'types') {

    if ($typesorvalues == 'types') {
        return [
            ['filter', 'WORKEFFORT', 'customlabeltype_worktodo', 'customlabel_mtd_type'],
            ['filter', 'WORKMODE', 'customlabeltype_worktodo', 'customlabel_mtd_type'],
            ['filter', 'WORKTYPE', 'customlabeltype_worktodo', 'customlabel_mtd_type'],
        ];
    }

    return [
        ['WORKEFFORT', 'NQ', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKEFFORT', 'VERYEASY', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKEFFORT', 'EASY', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKEFFORT', 'MEDIUM', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKEFFORT', 'HARD', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKEFFORT', 'VERYHARD', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],

        ['WORKMODE', 'NQ', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKMODE', 'ALONEONLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKMODE', 'ALONEOFFLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKMODE', 'TEAMONLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKMODE', 'TEAMOFFLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKMODE', 'COURSEONLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKMODE', 'COURSEOFFLINE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKMODE', 'COACHSYNCHRONOUS', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKMODE', 'COACHASYNCHRONOUS', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],

        ['WORKTYPE', 'NQ', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKTYPE', 'TRAINING', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKTYPE', 'WRITING', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKTYPE', 'INFOQUEST', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKTYPE', 'EXERCISE', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKTYPE', 'PROJECT', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKTYPE', 'EXPERIMENT', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
        ['WORKTYPE', 'SYNTHESIS', 'customlabeltype_worktodo', 'customlabel_mtd_value', 0],
    ];
}

/**
 * Code run after the quiz module database tables have been created.
 */
function xmldb_customlabel_install() {
    global $DB;

    // Work effort.

    $params = ['type' => 'filter', 'code' => 'WORKEFFORT'];
    if ($DB->record_exists('customlabel_mtd_type', $params)) {
        // Was already installed once.
        return;
    }

    $types = xmldb_customlabel_qualifier_defaults('types');
    $i = 0;
    $installedtypes = [];
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
    if (preg_match('/_desc/', $code)) {
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
