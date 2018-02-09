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
 * @package customlabel
 * @subpackage backup-moodle2
 * @copyright 2010 onwards Valery Fremaux (valery.freamux@club-internet.fr)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

/**
 * Define all the restore steps that will be used by the restore_url_activity_task
 */

/**
 * Structure step to restore one customlabel resource
 */
class restore_customlabel_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();

        // Main customlabel record.
        $paths[] = new restore_path_element('customlabel', '/activity/customlabel');

        // Metadata might be different from a moodle to another, so we might inject missing values.
        $p = '/activity/customlabel/metadata/metadatatypes/metadatatype';
        $paths[] = new restore_path_element('metadatatype', $p);
        $p = '/activity/customlabel/metadata/metadatatypes/metadatatype/metadatavalues/metadatavalue';
        $paths[] = new restore_path_element('metadatavalue', $p);
        $p = '/activity/customlabel/metadata/metadatacourse/metadatacoursedatum';
        $paths[] = new restore_path_element('coursemetadata', $p);

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    protected function after_execute() {
        // Add customlabel related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_customlabel', 'safecontent', null);
        $this->add_related_files('mod_customlabel', 'contentfiles', null);

        $areas = customlabel_get_fileareas();
        if (!empty($areas)) {
            foreach ($areas as $a) {
                $this->add_related_files('mod_customlabel', $a, null);
            }
        }
    }

    protected function process_customlabel($data) {
        global $DB;
        static $classes = null;
        static $systemcontext = null;

        // Always restore at system level context. Everything should pass.
        if (is_null($systemcontext)) {
            $systemcontext = context_system::instance();
        }

        if (is_null($classes)) {
            $classes = customlabel_get_classes($systemcontext, false, 'names');
        }

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // check the label subclass and fallback if not available here
        // disabled classes are still restored
        if (!in_array($data->labelclass, $classes)) {
            if (!empty($data->fallbacktype)) {
                $data->labelclass = $data->fallbacktype;
                $data->fallbacktype = '';
            } else {
                $data->labelclass = 'text';
                $data->fallbacktype = '';
            }
        }
        $data->intro = '';
        $data->introformat = 0;

        // insert the data record
        $newitemid = $DB->insert_record('customlabel', $data);

        // postupdate name
        $this->postupdate($data, 'name', $oldid, $newitemid);
        $this->postupdate($data, 'title', $oldid, $newitemid);

        $this->apply_activity_instance($newitemid);
    }

    protected function process_metadatatype($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        // The data is actually inserted into the database later in inform_new_usage_id.
        $newitemid = $DB->insert_record('customlabel_mtd_type', $data);
        $this->set_mapping('customlabel_mtd_type', $oldid, $newitemid, false); // Has no related files
    }

    protected function process_metadatavalue($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->typeid = $this->get_mappingid('customlabel_mtd_type', $data->typeid);

        // The data is actually inserted into the database later in inform_new_usage_id.
        if ($data->typeid) {
            $newitemid = $DB->insert_record('customlabel_mtd_value', $data);
            $this->set_mapping('customlabel_mtd_value', $oldid, $newitemid, false); // Has no related files
        }
    }

    protected function process_coursemetadata($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->valueid = $this->get_mappingid('customlabel_mtd_value', $data->valueid);
        $data->courseid = $this->get_courseid();

        // The data is actually inserted into the database later in inform_new_usage_id.
        if ($data->valueid) {
            $newitemid = $DB->insert_record('customlabel_course_metadata', $data);
            $this->set_mapping('customlabel_course_metadata', $oldid, $newitemid, false); // Has no related files.
        }
    }


    private function postupdate(&$data, $fieldname, $oldid, $newid) {
        global $DB;

        if (preg_match('/^(.*)_(\\d+)$/', $data->$fieldname, $matches)) {
            if ($matches[2] == $oldid) {
                $newname = $matches[1].'_'.$newid;
                $DB->set_field('customlabel', $fieldname, $newname, array('id' => $newid));
            }
        }
    }
}
