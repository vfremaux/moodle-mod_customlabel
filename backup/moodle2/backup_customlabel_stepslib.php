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
 * Define all the backup steps that will be used by the backup_customlabel_activity_task
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux (www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

/**
 * Define the complete choice structure for backup, with file and id annotations
 */
class backup_customlabel_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        /*
         * To know if we are including userinfo
         * $userinfo = $this->get_setting_value('userinfo');
         * these are labels and hav no user bound info
         */

        // Define each element separated.
        $customlabel = new backup_nested_element('customlabel', ['id'], [
            'name', 'title', 'labelclass', 'fallbacktype', 'intro', 'introformat', 'timemodified', 'content', 'processedcontent']);

        $metadata = new backup_nested_element('metadata');

        $metadatatypes = new backup_nested_element('metadatatypes');

        $metadatatype = new backup_nested_element('metadatatype', ['id'], [
            'type', 'code', 'name', 'description', 'sortorder']);

        $metadatavalues = new backup_nested_element('metadatavalues');

        $metadatavalue = new backup_nested_element('metadatavalue', ['id'], [
            'typeid', 'code', 'value', 'translatable', 'sortorder', 'parent']);

        // Do NOT backup constraints.

        $metadatacourse = new backup_nested_element('metadatacourse');

        $metadatacoursedatum = new backup_nested_element('metadatacoursedatum', ['id'], [
            'courseid', 'valueid']);

        // Build the tree.
        $customlabel->add_child($metadata);
        $metadata->add_child($metadatatypes);

        $metadatatypes->add_child($metadatatype);
        $metadatatype->add_child($metadatavalues);
        $metadatavalues->add_child($metadatavalue);

        $metadata->add_child($metadatacourse);
        $metadatacourse->add_child($metadatacoursedatum);

        // Define sources.
        $customlabel->set_source_table('customlabel', ['id' => backup::VAR_ACTIVITYID]);

        // Unless types are overriden (not implemented) this will not produce data.
        $metadatatype->set_source_table('customlabel_mtd_type', ['id' => backup::VAR_ACTIVITYID]);

        // Unless types are overriden (not implemented) this will not produce data.
        $metadatavalue->set_source_table('customlabel_mtd_value', ['typeid' => backup::VAR_PARENTID]);

        $metadatacoursedatum->set_source_table('customlabel_course_metadata', ['courseid' => backup::VAR_COURSEID]);

        // Define id annotations.

        // Define file annotations.
        // Get all itemids you can find (subids of internal fields).
        $customlabel->annotate_files('mod_customlabel', 'contentfiles', null);

        // This will scan every type of label to find filepickers elements.
        $areas = customlabel_get_fileareas();
        if (!empty($areas)) {
            foreach ($areas as $a) {
                $customlabel->annotate_files('mod_customlabel', $a, null); // This file area hasn't itemid.
            }
        }

        // Return the root element (customlabel), wrapped into standard activity structure.
        return $this->prepare_activity_structure($customlabel);
    }
}
