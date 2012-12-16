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

/**
 * Define all the restore steps that will be used by the restore_url_activity_task
 */

/**
 * Structure step to restore one vodeclic activity
 */
class restore_customlabel_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();

        $paths[] = new restore_path_element('customlabel', '/activity/customlabel');
    	$paths[] = new restore_path_element('metadatatype', '/activity/customlabel/types/type');
    	$paths[] = new restore_path_element('metadatavalue', '/activity/customlabel/types/type/values/value');
    	$paths[] = new restore_path_element('metadataconstraint', '/activity/customlabel/constraints/constraint');
    	$paths[] = new restore_path_element('metadatacourse', '/activity/customlabel/metadatacourse/metadatacoursedatum');
        
        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }


    protected function after_execute() {
        // Add customlabel related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_customlabel', 'safecontent', null);
    }
    
    
    protected function process_customlabel($data){
		global $DB;
		
        $data = (object)$data;        
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the data record
        $newitemid = $DB->insert_record('customlabel', $data);
        $this->apply_activity_instance($newitemid);
    }

    protected function process_metadatatype($data) {
    	global $DB;
    	
        $data = (object)$data;        
        $oldid = $data->id;

        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // The data is actually inserted into the database later in inform_new_usage_id.
        // items with similar code SHOULD NOT be duplicated. Old value will prepend to preserve local system stability.
        if ($oldrecord = $DB->get_record('customlabel_mtd_type', 'code', $data->code)){
        	$itemid = $oldrecord->id;
        } else {
        	$itemid = $DB->insert_record('customlabel_mtd_type', $data);
        }
        $this->set_mapping('customlabel_mtd_type', $oldid, $itemid, false); // Has no related files
    }

    protected function process_metadatavalue($data) {
    	global $DB;
    	
        $data = (object)$data;        
        $oldid = $data->id;

		// If an older record matches the same code, use the local one.
        if ($oldrecord = $DB->get_record('customlabel_mtd_value', 'code', $data->code)){
        	$itemid = $oldrecord->id;
        } else {

	        $data->typeid = $this->get_mappingid('customlabel_mtd_type', $data->typeid);
	        $data->timemodified = $this->apply_date_offset($data->timemodified);

	        // The data is actually inserted into the database later in inform_new_usage_id.
	        $itemid = $DB->insert_record('customlabel_mtd_value', $data);
	    }
        $this->set_mapping('customlabel_mtd_value', $oldid, $itemid, false); // Has no related files
    }

    protected function process_metadataconstraint($data) {
    	global $DB;
    	
        $data = (object)$data;        
        $oldid = $data->id;

        $data->value1 = $this->get_mappingid('customlabel_mtd_value', $data->value1);
        $data->value2 = $this->get_mappingid('customlabel_mtd_value', $data->value2);

		if ($oldconstraint = $DB->get_record_select('customlabel_mtd_constraint', " (value1 = $data->value1 AND value2 = $data->value2) OR (value2 = $data->value1 AND value1 = $data->value2) ")){
			$itemid = $oldconstraint->id;
		} else {	
	        $data->timemodified = $this->apply_date_offset($data->timemodified);	
	        // The data is actually inserted into the database later in inform_new_usage_id.
	        $itemid = $DB->insert_record('customlabel_mtd_value', $data);
	    }
        $this->set_mapping('customlabel_mtd_constraint', $oldid, $itemid, false); // Has no related files
    }

    protected function process_metadatacourse($data) {
    	global $DB;
    	
        $data = (object)$data;
        $oldid = $data->id;

        $data->valueid = $this->get_mappingid('customlabel_mtd_value', $data->valueid);
        $data->course = $this->get_courseid();

        // The data is actually inserted into the database later in inform_new_usage_id.
        $newitemid = $DB->insert_record('customlabel_course_metadata', $data);
        $this->set_mapping('customlabel_course_metadata', $oldid, $newitemid, false); // Has no related files
    }

}
