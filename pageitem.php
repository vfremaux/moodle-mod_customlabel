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
 * This script implements a pageitem content builder for feeding
 * a page_module actvity wrapper.
 *
 * @package   customlabel
 * @copyright 2014 Valery Fremaux (valery.Fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/locallib.php');
require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 * Implements a hook for the page_module block to add
 * the link allowing live refreshing of the content. this method is
 * specifically used for page formatted courses.
 * @param object $block a page_module block surrounding the customlabel resource.
 */
function customlabel_set_instance(&$block) {
    global $DB, $PAGE;

    // Transfer content from title to content.
    $block->title = '';

    // Fake unpacks object's load.
    $data = json_decode(base64_decode($block->moduleinstance->content));

    // If failed in getting content. It happens sometimes, ... do nothing to let content be safed manually.
    if (is_null($data) || !is_object($data)) {
        return false;
    }

    // Realize a pseudo update.
    $data->title = $block->moduleinstance->title;
    $data->content = $block->moduleinstance->content;
    $data->labelclass = $block->moduleinstance->labelclass; // Fixes broken serialized contents.
    $data->instance = $block->moduleinstance->id;
    $data->coursemodule = $block->cm->id;

    if (!customlabel_type::module_is_visible($block->cm, $block->moduleinstance)) {
        return false;
    }

    if (!isset($block->moduleinstance->title)) {
        // Fixes broken serialized contents.
        $block->moduleinstance->title = '';
    }

    $instance = customlabel_load_class($data);
    if (!$instance) {
        print_error("Failed loading cuztomlabel class $data->labelclass for instance ");
    }

    // Force refreshcontent in page format.
    $block->moduleinstance->processedcontent = $instance->make_content();
    $block->moduleinstance->name = $instance->title; // This realizes the template.
    $block->moduleinstance->timemodified = time();
    $block->content->text = $block->moduleinstance->processedcontent;
    $result = $DB->update_record('customlabel', $block->moduleinstance);

    $context = context_module::instance($block->cm->id);

    // Post process each textarea field url replacement.
    $fileprocessedcontent = $block->content->text;
    foreach ($instance->fields as $field) {
        if ($field->type == 'editor') {
            if (!isset($field->itemid) || is_null($field->itemid)) {
                $message = 'Course element textarea subfield needs explicit itemid in definition ';
                $message .= $customlabel->labelclass.'::'.$field->name;
                throw new coding_exception($message);
            }
            $fileprocessedcontent = customlabel_file_rewrite_pluginfile_urls($fileprocessedcontent,
                'pluginfile.php', $context->id, 'mod_customlabel', 'contentfiles', $field->itemid);
        }
    }

    // Pass all filters except multilang and multilangenhanced.
    $filtermanager = filter_manager::instance();
    $filtermanager->setup_page_for_filters($PAGE, $context); // Setup global stuff filters may have.
    $filteroptions = array(
        'noclean' => false,
    );
    $skipfilters = array('multilang', 'multilangenhanced');
    $block->content->text = $filtermanager->filter_text($fileprocessedcontent, $context, $filteroptions, $skipfilters);

    return true;
}

