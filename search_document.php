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
 * @package customlabel
 * @category mod
 * @subpackage document_wrappers
 * @author Valery Fremaux [valery.fremaux@club-internet.fr] > 1.9
 * @date 2008/03/31
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 * document handling for all resources
 * This file contains the mapping between a resource and it's indexable counterpart,
 *
 * Functions for iterating and retrieving the necessary records are now also included
 * in this file, rather than mod/resource/lib.php
 */

require_once($CFG->dirroot.'/local/search/documents/document.php');
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

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
        $doc->contents  = strip_tags($customlabel['name']);
        $doc->url       = customlabel_make_link($customlabel['course']);

        /*
         * module specific information : extract fields from serialized content. Add those who are
         * lists as keyfields
         */
        $content = json_decode(base64_decode($customlabel['processedcontent']));

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
    } //constructor
}

/**
 * constructs valid access links to information
 * @param resourceId the of the resource 
 * @return a full featured link element as a string
 */
function customlabel_make_link($courseid) {

    return new moodle_url('/course/view.php', array('id' => $courseid));
}

/**
 * part of standard API
 *
 */
function customlabel_iterator() {
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
function customlabel_get_content_for_index(&$customlabel) {
    global $CFG, $DB;

    // Starting with Moodle native resources.
    $documents = array();

    $coursemodule = $DB->get_field('modules', 'id', array('name' => 'customlabel'));
    $cm = $DB->get_record('course_modules', array('course' => $customlabel->course, 'module' => $coursemodule, 'instance' => $customlabel->id));
    $context = context_module::instance($cm->id);
    $customclass = customlabel_load_class($customlabel, true);
    if ($customclass) {
        $arr = get_object_vars($customlabel);
        $documents[] = new CustomLabelSearchDocument($arr, $customclass, $context->id);
        mtrace("finished label {$customlabel->id}");
    } else {
        mtrace("ignoring unknown label type {$customlabel->labelclass} instance");
    }
    return $documents;
}

/**
 * part of standard API.
 * returns a single resource search document based on a label id
 * @param id the id of the accessible document
 * @return a searchable object or null if failure
 */
function customlabel_single_document($id, $itemtype) {
    global $CFG, $DB;

    $customlabel = $DB->get_record('customlabel', array('id' => $id));

    if ($customlabel) {
        $coursemodule = $DB->get_field('modules', 'id', array('name' => 'customlabel'));
        $cm = $DB->get_record('course_modules', array('module' => $coursemodule, 'instance' => $customlabel->id));
        $customclass = customlabel_load_class($customlabel, true);
        $context = context_module::instance($cm->id);
        return new CustomLabelSearchDocument(get_object_vars($customlabel), $customclass, $context->id);
    }
    return null;
}

/**
 * dummy delete function that aggregates id with itemtype.
 * this was here for a reason, but I can't remember it at the moment.
 */
function customlabel_delete($info, $itemtype) {
    $object->id = $info;
    $object->itemtype = $itemtype;
    return $object;
}

/**
 * returns the var names needed to build a sql query for addition/deletions
 *
 */
function customlabel_db_names() {
    return array(array('id', 'customlabel', 'timemodified', 'timemodified', 'customlabel', ''));
}

/**
 * customlabel points actually the complete course content and not the customlabel item
 */
function customlabel_search_get_objectinfo($itemtype, $thisid, $contextid = null) {
    global $DB;

    if (!$course = $DB->get_record('course', array('id' => $thisid))) {
        return false;
    }

    if ($contextid) {
        // We still need this case for the global search engine being able to operate.
        $info->context = $DB->get_record('context', array('id' => $contextid));
        $info->cm = $DB->get_record('course_modules', array('id' => $info->context->instanceid));
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
function customlabel_check_text_access($path, $itemtype, $thisid, $user, $groupid, $contextid) {
    global $CFG;

    // This_id binds to $course->id, but course check where already performed.
    if (!$info = customlabel_search_get_objectinfo($itemtype, $thisid, $contextid)) {
        return false;
    }
    $cm = $info->cm;
    $context = $info->context;
    $instance = $info->instance;
    /*
     * check if found course module is visible
     * we cannot consider a content in hidden labels
     */
    if (!$cm->visible && !has_capability('moodle/course:viewhiddenactivities', $context)) {
        return false;
    }
    return true;
}

/**
 * post processes the url for cleaner output.
 * @param string $title
 */
function customlabel_link_post_processing($title) {
    global $CFG;

    if ($CFG->block_search_utf8dir) {
        return mb_convert_encoding("(".shorten_text(clean_text($title), 60)."...) ", 'UTF-8', 'auto');
    }
    return mb_convert_encoding("(".shorten_text(clean_text($title), 60)."...) ", 'auto', 'UTF-8');
}
