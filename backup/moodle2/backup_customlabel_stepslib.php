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
 * @package    mod
 * @subpackage customlabel
 * @copyright  2010 onwards Valery Fremaux {valery.fremaux@club-internet.fr}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_vodclic_activity_task
 */

/**
 * Define the complete label structure for backup, with file and id annotations
 */
class backup_customlabel_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo
        // $userinfo = $this->get_setting_value('userinfo');
        // these are labels and hav no user bound info

        // Define each element separated
        $customlabel = new backup_nested_element('customlabel', array('id'), array(
            'name', 'title', 'labelclass', 'fallbacktype', 'intro', 'introformat', 'timemodified', 'content', 'processedcontent'));

        $metadata = new backup_nested_element('metadata');

        $metadatatypes = new backup_nested_element('metadatatypes');

        $metadatatype = new backup_nested_element('metadatatype', array('id'), array(
            'type', 'code', 'name', 'description', 'sortorder'));

        $metadatavalues = new backup_nested_element('metadatavalues');

        $metadatavalue = new backup_nested_element('metadatavalue', array('id'), array(
            'typeid', 'code', 'value', 'translatable', 'sortorder', 'parent'));

        $metadataconstraints = new backup_nested_element('metadataconstraints');

        $metadataconstraint = new backup_nested_element('metadataconstraint', array('id'), array(
            'value1', 'value2', 'const'));

        $metadatacourse = new backup_nested_element('metadatacourse');

        $metadatacoursedatum = new backup_nested_element('metadatacoursedatum', array('id'), array(
            'courseid', 'valueid'));

        // Build the tree
        // (love this)
        $customlabel->add_child($metadata);
        $metadata->add_child($metadatatypes);
        $metadata->add_child($metadataconstraints);

        $metadatatypes->add_child($metadatatype);
        $metadatatype->add_child($metadatavalues);
        $metadatavalues->add_child($metadatavalue);

        $metadataconstraints->add_child($metadataconstraint);

        $metadata->add_child($metadatacourse);
        $metadatacourse->add_child($metadatacoursedatum);

        // Define sources
        $customlabel->set_source_table('customlabel', array('id' => backup::VAR_ACTIVITYID));

        $metadatatype->set_source_table('customlabel_mtd_type', array('id' => backup::VAR_ACTIVITYID));

        $metadatavalue->set_source_table('customlabel_mtd_value', array('typeid' => backup::VAR_PARENTID));

        $metadataconstraint->set_source_table('customlabel_mtd_constraint', array());

        $metadatacoursedatum->set_source_table('customlabel_course_metadata', array('courseid' => backup::VAR_COURSEID));

        // Define id annotations
        // (none)

        // Define file annotations
        $customlabel->annotate_files('mod_customlabel', 'contentfiles', null); // This file area hasn't itemid

        // Return the root element (customlabel), wrapped into standard activity structure
        return $this->prepare_activity_structure($customlabel);
    }
}
