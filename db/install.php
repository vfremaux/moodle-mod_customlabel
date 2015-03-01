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
 * @package    mod
 * @subpackage customlabel
 * @copyright  2013 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Code run after the quiz module database tables have been created.
 */
function xmldb_customlabel_install() {
    global $DB;

    // Work effort.

    $record = new stdClass();
    $record->type = 'filter';
    $record->code = 'WORKEFFORT';
    $record->name = get_string('WORKEFFORT', 'customlabeltype_worktodo');
    $record->description = get_string('WORKEFFORT_desc', 'customlabeltype_worktodo');
    $record->sortorder = 1;
    $typeid = $DB->insert_record('customlabel_mtd_type', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'NQ';
    $record->value = get_string('NQ', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 1;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'VERYEASY';
    $record->value = get_string('VERYEASY', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'EASY';
    $record->value = get_string('EASY', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 3;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'MEDIUM';
    $record->value = get_string('MEDIUM', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 4;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'HARD';
    $record->value = get_string('HARD', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 5;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'VERYHARD';
    $record->value = get_string('VERYHARD', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 6;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    // Workmodes
    
    $record = new stdClass();
    $record->type = 'filter';
    $record->code = 'WORKMODE';
    $record->name = get_string('WORKMODE', 'customlabeltype_worktodo');
    $record->description = get_string('WORKMODE_desc', 'customlabeltype_worktodo');
    $record->sortorder = 2;
    $typeid = $DB->insert_record('customlabel_mtd_type', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'NQ';
    $record->value = get_string('NQ', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 1;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'ALONEONLINE';
    $record->value = get_string('ALONEONLINE', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'ALONEOFFLINE';
    $record->value = get_string('ALONEOFFLINE', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 3;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'TEAMONLINE';
    $record->value = get_string('TEAMONLINE', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 4;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'TEAMOFFLINE';
    $record->value = get_string('TEAMOFFLINE', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 5;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'COURSEONLINE';
    $record->value = get_string('COURSEONLINE', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 6;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'COURSEOFFLINE';
    $record->value = get_string('COURSEOFFLINE', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 7;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'COACHSYNCHRONOUS';
    $record->value = get_string('COACHSYNCHRONOUS', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 8;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'COACHASYNCHRONOUS';
    $record->value = get_string('COACHASYNCHRONOUS', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 9;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    // Worktypes 
    
    $record = new stdClass();
    $record->type = 'filter';
    $record->code = 'WORKTYPE';
    $record->name = get_string('WORKTYPE', 'customlabeltype_worktodo');
    $record->description = get_string('WORKTYPE_desc', 'customlabeltype_worktodo');
    $record->sortorder = 3;
    $typeid = $DB->insert_record('customlabel_mtd_type', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'NQ';
    $record->value = get_string('NQ', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'TRAINING';
    $record->value = get_string('TRAINING', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'WRITING';
    $record->value = get_string('WRITING', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'INFOQUEST';
    $record->value = get_string('INFOQUEST', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'EXERCISE';
    $record->value = get_string('EXERCISE', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'PROJECT';
    $record->value = get_string('PROJECT', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'EXPERIMENT';
    $record->value = get_string('EXPERIMENT', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

    $record = new stdClass();
    $record->typeid  = $typeid;
    $record->code = 'SYNTHESIS';
    $record->value = get_string('SYNTHESIS', 'customlabeltype_worktodo');
    $record->translatable = 0;
    $record->sortorder = 2;
    $record->parent = 0;
    $DB->insert_record('customlabel_mtd_value', $record);

}
