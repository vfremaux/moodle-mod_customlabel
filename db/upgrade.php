<?php

// This file keeps track of upgrades to 
// the customlabel module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

function xmldb_customlabel_upgrade($oldversion=0) {
    global $CFG, $THEME, $DB;

    $result = true;

    $dbman = $DB->get_manager();

    //===== 1.9.0 upgrade line ======//
    if ($result && $oldversion < 2012062401) {

    // Define field fallbacktype to be added to customlabel.
        $table = new xmldb_table('customlabel');
        $field = new xmldb_field('fallbacktype');
        $field->set_attributes(XMLDB_TYPE_CHAR, '32', null, null, null, null, 'labelclass');

    // Launch add field parent.
        $result = $result || $dbman->add_field($table, $field);

        // customlabel savepoint reached.
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
                        // We have an old data there, we need get the file record it and add it to the filestore
                        if (preg_match('#^http#', $content->$oldname)) {
                            // Fire a CURL to get the file, store into filestore

                            $filerecord = new Stdclass();
                            $filerecord->contextid = context_module::instance($cm->id)->id;
                            $filerecord->component = 'mod_customlabel';
                            $filerecord->filearea = $f->name;
                            $filerecord->itemid = 0;
                            $filerecord->filepath = '/';
                            $filerecord->filename = basename($content->$oldname);
                            try {
                                $fs->create_file_from_url($filerecord, $content->$oldname, null, true);
                            } catch( Exception $e) {
                            }
                            mtrace("file created ");
                        }
                    }
                }
            }
        }
    }
}