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
 * @package     mod_customlabel
 * @category    mod
 * @author      Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright   (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die ();

require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

function xmldb_customlabel_upgrade($oldversion = 0) {
    global $DB;

    $result = true;

    $dbman = $DB->get_manager();

    if ($result && $oldversion < 2012062401) {

        // Define field fallbacktype to be added to customlabel.
        $table = new xmldb_table('customlabel');
        $field = new xmldb_field('fallbacktype');
        $field->set_attributes(XMLDB_TYPE_CHAR, '32', null, null, null, null, 'labelclass');

        $dbman->add_field($table, $field);

        // Customlabel savepoint reached.
        upgrade_mod_savepoint($result, 2012062401, 'customlabel');
    }

    if ($result && $oldversion < 2013041802) {

        // Regenerates all contents to match template changes.
        $sql = "
            SELECT DISTINCT
                c.id,
                c.shortname,
                c.fullname,
                c.idnumber,
                c.summary,
                c.category,
                c.summaryformat
            FROM
                {course} c,
                {course_modules} cm,
                {modules} m
            WHERE
                c.id = cm.course AND
                cm.module = m.id AND
                m.name = 'customlabel'
        ";
        $courses = $DB->get_records_sql($sql);
        if ($courses) {
            echo '<pre>';
            foreach ($courses as $c) {
                customlabel_course_preprocess_filepickers($c);
                customlabel_course_regenerate($c, 'all');
            }
            echo '</pre>';
        }
        upgrade_mod_savepoint($result, 2013041802, 'customlabel');
    }

    if ($oldversion < 2017061300) {

        // Define index ix_typeid (not unique) to be added to customlabel_mtd_value.
        $table = new xmldb_table('customlabel_mtd_value');
        $index = new xmldb_index('ix_typeid', XMLDB_INDEX_NOTUNIQUE, array('typeid'));

        // Conditionally launch add index ix_typeid.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define index ix_courseid_valueid (unique) to be added to customlabel_course_metadata.
        $table = new xmldb_table('customlabel_course_metadata');
        $index = new xmldb_index('ix_courseid_valueid', XMLDB_INDEX_UNIQUE, array('courseid', 'valueid'));

        // Conditionally launch add index ix_courseid_valueid.
        // Secure the index creation.
        $DB->delete_records('customlabel_course_metadata', array('valueid' => 0));
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define index ix_constraint (unique) to be added to customlabel_mtd_constraint.
        $table = new xmldb_table('customlabel_mtd_constraint');
        $index = new xmldb_index('ix_constraint', XMLDB_INDEX_UNIQUE, array('value1', 'value2'));

        // Conditionally launch add index ix_constraint.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Customlabel savepoint reached.
        upgrade_mod_savepoint(true, 2017061300, 'customlabel');
    }

    if ($oldversion < 2018111700) {

        // Define completoin fields to be added to customlabel.
        $table = new xmldb_table('customlabel');
        $field = new xmldb_field('completion1enabled');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'processedcontent');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);

            $field = new xmldb_field('completion2enabled');
            $field->set_attributes(XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'completion1enabled');
            $dbman->add_field($table, $field);

            $field = new xmldb_field('completion3enabled');
            $field->set_attributes(XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'completion2enabled');
            $dbman->add_field($table, $field);
        }

        // Customlabel savepoint reached.
        upgrade_mod_savepoint(true, 2018111700, 'customlabel');
    }

    if ($oldversion < 2018111900) {

        // Define table customlabel_user_data to be created.
        $table = new xmldb_table('customlabel_user_data');

        // Adding fields to table customlabel_user_data.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('customlabelid', XMLDB_TYPE_INTEGER, '11', null, null, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', null, null, null, null);
        $table->add_field('completion1', XMLDB_TYPE_INTEGER, '9', null, XMLDB_NOTNULL, null, 0);
        $table->add_field('completion2', XMLDB_TYPE_INTEGER, '9', null, XMLDB_NOTNULL, null, 0);
        $table->add_field('completion3', XMLDB_TYPE_INTEGER, '9', null, XMLDB_NOTNULL, null, 0);

        // Adding keys to table customlabel_user_data.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table customlabel_user_data.
        $table->add_index('ix_userid_cid', XMLDB_INDEX_UNIQUE, array('userid', 'customlabelid'));

        // Conditionally launch create table for customlabel_user_data.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Customlabel savepoint reached.
        upgrade_mod_savepoint(true, 2018111900, 'customlabel');
    }

    if ($oldversion < 2018120600) {

        $table = new xmldb_table('customlabel_mtd_type');
        $field = new xmldb_field('name');
        $field->set_attributes(XMLDB_TYPE_CHAR, '255', null, null, null, null);

        $dbman->change_field_precision($table, $field);

        // Customlabel savepoint reached.
        upgrade_mod_savepoint(true, 2018120600, 'customlabel');
    }

    if ($oldversion < 2019050900) {

        $table = new xmldb_table('customlabel_course_metadata');
        $field = new xmldb_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0, 'valueid');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Customlabel savepoint reached.
        upgrade_mod_savepoint(true, 2019050900, 'customlabel');
    }

    if ($oldversion < 2019061000) {

        $table = new xmldb_table('customlabel_course_metadata');
        $index = new xmldb_index('mdl_custcourmeta_couval_uix', XMLDB_INDEX_UNIQUE, array('courseid', 'valueid'));

        if ($dbman->index_exists($table, $index)) {
            // Adding indexes to table customlabel_user_data.
            $dbman->drop_index($table, $index);

            $index = new xmldb_index('ix_course_value_cm_id', XMLDB_INDEX_UNIQUE, array('courseid', 'valueid', 'cmid'));
            $dbman->add_index($table, $index);
        }

        // Customlabel savepoint reached.
        upgrade_mod_savepoint(true, 2019061000, 'customlabel');
    }

    return $result;
}

function customlabel_course_preprocess_filepickers($c) {
    global $DB;

    $fs = get_file_storage();

    if ($customlabels = $DB->get_records('customlabel', array('course' => $c->id))) {
        foreach ($customlabels as $c) {
            mtrace("preprocessing customlabel $c->name ");
            $cm = get_coursemodule_from_instance('customlabel', $c->id);
            $c->coursemodule = $cm->id;
            $instance = customlabel_load_class($c);
            foreach ($instance->fields as $f) {
                if ($f->type == 'filepicker') {
                    mtrace("preprocessing filepicker $f->name ");
                    $oldname = $f->name.'url';
                    $content = json_decode(base64_decode($c->content));
                    mtrace("checking field content : ".@$content->$oldname);
                    if (!empty($content->$oldname)) {
                        // We have an old data there, we need get the file record it and add it to the filestore.
                        if (preg_match('#^http#', $content->$oldname)) {
                            // Fire a CURL to get the file, store into filestore.

                            $filerecord = new Stdclass();
                            $filerecord->contextid = context_module::instance($cm->id)->id;
                            $filerecord->component = 'mod_customlabel';
                            $filerecord->filearea = $f->name;
                            $filerecord->itemid = 0;
                            $filerecord->filepath = '/';
                            $filerecord->filename = basename($content->$oldname);
                            try {
                                $fs->create_file_from_url($filerecord, $content->$oldname, null, true);
                            } catch (Exception $e) {
                                assert(1);
                                // Do nothing.
                            }
                            mtrace("file created ");
                        }
                    }
                }
            }
        }
    }
}