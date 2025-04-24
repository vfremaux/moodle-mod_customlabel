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
 * Global Search Engine for Moodle
 *
 * @package mod_customlabel
 *
 * @subpackage document_wrappers
 * @author Valery Fremaux [valery.fremaux@gmail.com] > 1.9
 * @date 2008/03/31
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 * document handling for all resources
 * This file contains the mapping between a resource and it's indexable counterpart,
 *
 * Functions for iterating and retrieving the necessary records are now also included
 * in this file, rather than mod/resource/lib.php
 */
namespace local_search;

use StdClass;
use context_module;
use context_course;
use moodle_url;
use context;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/local/search/documents/document.php');
require_once($CFG->dirroot.'/local/search/documents/document_wrapper.class.php');
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');
require_once($CFG->dirroot.'/mod/customlabel/lib.php');

/**
 * constants for document definition
 */
define('X_SEARCH_TYPE_CUSTOMLABEL', 'customlabel');

/**
 * a class for representing searchable information
 *
 */
class CustomLabelSearchDocument extends SearchDocument {

    public function __construct(&$customlabel, &$class, $contextid) {
        // Generic information; required.
        $doc = new StdClass();
        $doc->docid     = $customlabel['course'];
        $doc->documenttype = X_SEARCH_TYPE_CUSTOMLABEL;
        $doc->itemtype     = 'customlabel';
        $doc->contextid    = $contextid;
        $doc->title     = strip_tags($customlabel['title']);
        $doc->date      = $customlabel['timemodified'];
        $doc->author    = '';
        $doc->contents  = strip_tags($customlabel['processedcontent']);
        $doc->url       = customlabel_document_wrapper::make_link($customlabel['course'], $contextid);

        /*
         * module specific information : extract fields from serialized content. Add those who are
         * lists as keyfields
         */
        $content = json_decode(base64_decode($customlabel['content']));

        $additionalkeys = null;

        // Scan field and get as much searchable fields.
        foreach ($class->fields as $afield) {
            if (preg_match("/list$/", $afield->type)) {
                if (!isset($afield->multiple)) {
                    $fieldname = $afield->name;
                    if (!empty($content->{$fieldname})) {
                        $additionalkeys[$fieldname] = $content->{$fieldname};
                    }
                }
            }
        }

        parent::__construct($doc, $data, $customlabel['course'], 0, 0, 'mod/'.X_SEARCH_TYPE_CUSTOMLABEL, $additionalkeys);
    }
}

class customlabel_document_wrapper extends document_wrapper {

    /**
     * constructs valid access links to information
     * @param resourceId the of the resource
     * @return a full featured link element as a string
     */
    public static function make_link($courseid, $contextid = null) {
        global $DB;

        $format = $DB->get_field('course', 'format', ['id' => $courseid]);
        if ($format == 'page') {
            // We need search on which page an occurrence of the search was found.
            $context = context::instance_by_id($contextid);
            $params = ['cmid' => $context->instanceid];
            $pages = $DB->get_records('format_page_items', $params);
            if ($pages) {
                $firstitemoccurence = array_shift($pages);
                $courseurl = new moodle_url('/course/view.php', ['id' => $courseid, 'page' => $firstitemoccurence->pageid]);
            }
        }

        if (empty($courseurl)) {
            $courseurl = new moodle_url('/course/view.php', ['id' => $courseid]);
        }

        return $courseurl;
    }

    /**
     * part of standard API
     *
     */
    public static function get_iterator() {
        global $DB;
        /*
         * trick to leave search indexer functionality intact, but allow
         * this document to only use the below function to return info
         * to be searched
         */
        $labels = $DB->get_records('customlabel');
        return $labels;
    }

    /**
     * part of standard API
     * this function does not need a content iterator, returns all the info
     * itself;
     * @param notneeded to comply API, remember to fake the iterator array though
     * @uses CFG
     * @return an array of searchable documents
     */
    public static function get_content_for_index(&$instance) {
        global $DB;

        // Starting with Moodle native resources.
        $documents = [];

        $coursemodule = $DB->get_field('modules', 'id', ['name' => 'customlabel']);
        $params = ['course' => $instance->course, 'module' => $coursemodule, 'instance' => $instance->id];
        $cm = $DB->get_record('course_modules', $params);
        $context = context_module::instance($cm->id);
        $instance->coursemodule = $cm->id;
        $customclass = customlabel_load_class($instance, true);

        if ($customclass) {

            // Render content.
            $cminfo = new StdClass;
            $cminfo->id = $cm->id;
            $cminfo->instance = $instance->id;
            $instance->processedcontent = customlabel_cm_info_dynamic($cminfo);

            $arr = get_object_vars($instance);
            $documents[] = new CustomLabelSearchDocument($arr, $customclass, $context->id);
            mtrace("finished label {$instance->id}");
        } else {
            mtrace("ignoring unknown label type {$instance->labelclass} instance");
        }
        return $documents;
    }

    /**
     * part of standard API.
     * returns a single resource search document based on a label id
     * @param id the id of the accessible document
     * @return a searchable object or null if failure
     */
    public static function single_document($id, $itemtype) {
        global $DB;

        $instance = $DB->get_record('customlabel', ['id' => $id]);

        if ($customlabel) {
            $coursemodule = $DB->get_field('modules', 'id', ['name' => 'customlabel']);
            $cm = $DB->get_record('course_modules', ['module' => $coursemodule, 'instance' => $instance->id]);
            $instance->coursemodule = $cm->id;
            $customclass = customlabel_load_class($instance, true);
            $context = context_module::instance($cm->id);

            // Render content.
            $cminfo = new StdClass;
            $cminfo->id = $cm->id;
            $cminfo->instance = $instance->id;
            $instance->processedcontent = customlabel_cm_info_dynamic($cminfo);

            return new CustomLabelSearchDocument(get_object_vars($instance), $customclass, $context->id);
        }
        return null;
    }

    /**
     * returns the var names needed to build a sql query for addition/deletions
     *
     */
    public static function db_names() {
        return [['id', 'customlabel', 'timemodified', 'timemodified', 'customlabel', '']];
    }

    /**
     * customlabel points actually the complete course content and not the customlabel item
     */
    protected static function search_get_objectinfo($itemtype, $thisid, $contextid = null) {
        global $DB;

        if (!$course = $DB->get_record('course', ['id' => $thisid])) {
            return false;
        }

        if ($contextid) {
            // We still need this case for the global search engine being able to operate.
            $info->context = $DB->get_record('context', ['id' => $contextid]);
            $info->cm = $DB->get_record('course_modules', ['id' => $info->context->instanceid]);
        } else {
            // This case IS NOT consistant for extracting object information.
            return false;
        }
        $info->instance = $course;
        $info->type = 'mod';
        $info->mediatype = 'composite';
        $info->contenttype = 'html';
        return $info;
    }

    /**
     * handles the access policy to contents indexed as searchable documents. If this
     * function does not exist, the search engine assumes access is allowed.
     * @param path the access path to the module script code
     * @param itemtype the information subclassing (usefull for complex modules, defaults to 'standard')
     * @param this_id the item id within the information class denoted by itemtype. In resources, this id
     * points to the resource record and not to the module that shows it.
     * @param user the user record denoting the user who searches
     * @param group_id the current group used by the user when searching
     * @return true if access is allowed, false elsewhere
     */
    public static function check_text_access($path, $itemtype, $thisid, $user, $groupid, $contextid) {

        // This_id binds to $course->id, but course check where already performed.
        if (!$info = self::search_get_objectinfo($itemtype, $thisid, $contextid)) {
            return false;
        }
        $cm = $info->cm;
        $context = $info->context;

        /*
         * check if found course module is visible
         * we cannot consider a content in hidden labels
         */
        if (!$cm->visible && !has_capability('moodle/course:viewhiddenactivities', $context)) {
            return false;
        }

        if (!$cm->uservisible) {
            return false;
        }
        return true;
    }
}
