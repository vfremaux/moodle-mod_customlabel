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
 * Provides support for the conversion of moodle1 backup to the moodle2 format
 * Based off of a template @ http://docs.moodle.org/dev/Backup_1.9_conversion_for_developers
 *
 * @package    mod
 * @subpackage customlabel
 * @copyright  2011 Valery Fremaux <valery.fremaux@club-internet.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Tracker conversion handler
 */
class moodle1_mod_customlabel_handler extends moodle1_mod_handler {

    /** @var moodle1_file_manager */
    protected $fileman = null;

    /** @var int cmid */
    protected $moduleid = null;


    /**
     * Declare the paths in moodle.xml we are able to convert
     *
     * The method returns list of {@link convert_path} instances.
     * For each path returned, the corresponding conversion method must be
     * defined.
     *
     * Note that the path /MOODLE_BACKUP/COURSE/MODULES/MOD/TRACKER does not
     * actually exist in the file. The last element with the module name was
     * appended by the moodle1_converter class.
     *
     * @return array of {@link convert_path} instances
     */
    public function get_paths() {
        return array(
            new convert_path(
                'customlabel', '/MOODLE_BACKUP/COURSE/MODULES/MOD/CUSTOMLABEL',
                array(
                    'dropfields' => array(
//                         'content',   // Do not remove all fields at start or u loose some valuable content before processing.
                        'usesafe'
                    ),
                    'renamefields' => array(
//                      'safecontent' => 'content' // Do not remove all fields at start or u loose some valuable content before processing.
                    ),
                    'newfields' => array(
                        'intro' => '',
                        'introformat' => 1,
                        'processedcontent' => ''
                    ),
                )
            ),
       );
    }

    /**
     * This is executed every time we have one /MOODLE_BACKUP/COURSE/MODULES/MOD/CUSTOMLABEL
     * data available
     */
    public function process_customlabel($data) {
        // get the course module id and context id
        $instanceid = $data['id'];
        $cminfo     = $this->get_cminfo($instanceid);
        $moduleid   = $cminfo['id'];
        $contextid  = $this->converter->get_contextid(CONTEXT_MODULE, $moduleid);

        // shifts content from name (moodle 1.9 label hacking location) to processed content and
        // computes a new explicit name
        if (!empty($data['safecontent'])) {
            $storedcontent = base64_decode($data['safecontent']);
            // unset($data['safecontent']);
        } else {
            if (!empty($data['content'])) {
                $storedcontent = $data['content'];
            } else {
                // loose the item
                return;
            }
        }
        $customlabel = json_decode($storedcontent);
        $data['processedcontent'] = $data['name'];
        $data['name'] = $customlabel->labelclass.$data['id'];

        // Get a fresh new file manager for this instance.
        $this->fileman = $this->converter->get_file_manager($contextid, 'mod_customlabel');

        // Convert course files embedded into the content.
        $this->fileman->filearea = 'content';
        $this->fileman->itemid   = 0;

        // Try get files and reencode from stored content stub.
        $storedcontent = moodle1_converter::migrate_referenced_files($storedcontent, $this->fileman);
        $data['content'] = base64_encode($storedcontent);

        $data['processedcontent'] = moodle1_converter::migrate_referenced_files($data['processedcontent'], $this->fileman);

        // write inforef.xml.
        $this->open_xml_writer("activities/customlabel_{$moduleid}/inforef.xml");
        $this->xmlwriter->begin_tag('inforef');
        $this->xmlwriter->begin_tag('fileref');
        foreach ($this->fileman->get_fileids() as $fileid) {
            $this->write_xml('file', array('id' => $fileid));
        }
        $this->xmlwriter->end_tag('fileref');
        $this->xmlwriter->end_tag('inforef');
        $this->close_xml_writer();

        // Write customlabel.xml.
        $this->open_xml_writer("activities/customlabel_{$moduleid}/customlabel.xml");
        $this->xmlwriter->begin_tag('activity', array('id' => $instanceid, 'moduleid' => $moduleid, 'modulename' => 'customlabel', 'contextid' => $contextid));

        $this->xmlwriter->begin_tag('customlabel', array('id' => $instanceid));

        foreach ($data as $field => $value) {
            if ($field <> 'id') {
                $this->xmlwriter->full_tag($field, $value);
            }
        }

        // finish writing customlabel.xml
        $this->xmlwriter->end_tag('customlabel');
        $this->xmlwriter->end_tag('activity');
        $this->close_xml_writer();

        return $data;
    }
}
