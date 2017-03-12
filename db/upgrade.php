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

    return $result;
}

function customlabel_course_preprocess_filepickers($c) {
    global $DB;

    $fs = get_file_storage();

    if ($customlabels = $DB->get_records('customlabel', array('course' => $c->id))) {
        foreach ($customlabels as $c) {
            mtrace("preprocessing customlabel $c->name ");
            $cm = get_coursemodule_from_instance('customlabel', $c->id);

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