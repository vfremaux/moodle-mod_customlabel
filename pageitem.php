<?php

require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

/**
 * implements a hook for the page_module block to add
 * the link allowing live refreshing of the content
 *
 *
 */
function customlabel_set_instance(&$block) {
    global $USER, $CFG, $COURSE, $DB;

    // Transfer content from title to content.
    $block->title = '';

    // Fake unpacks object's load.
    $data = json_decode(base64_decode($block->moduleinstance->content));

    // If failed in getting content. It happens sometimes, ... do nothing to let content be safed manually
    if (is_null($data) || !is_object($data)) {
        return false;
    }
    
    // Realize a pseudo update.
    $data->title = $block->moduleinstance->title;
    $data->content = $block->moduleinstance->content;
    $data->labelclass = $block->moduleinstance->labelclass; // fixes broken serialized contents

    $context = context_module::instance($block->cm->id);
    if (!has_capability('customlabeltype/'.$data->labelclass.':view', $context)) {
        return false;
    }
    
    if (!isset($block->moduleinstance->title)) {
        // Fixes broken serialized contents.
        $block->moduleinstance->title = '';
    }
    $instance = customlabel_load_class($data);
    $block->moduleinstance->processedcontent = $instance->make_content();
    $block->moduleinstance->name = $instance->title; // this realizes the template
    $block->moduleinstance->timemodified = time();
    $block->content->text = $block->moduleinstance->processedcontent;
    $block->moduleinstance->title = str_replace("'", "''", $block->moduleinstance->title);
    $result = $DB->update_record('customlabel', $block->moduleinstance);

    $context = context_module::instance($block->cm->id);
    $block->content->text = file_rewrite_pluginfile_urls($block->content->text, 'pluginfile.php', $context->id, 'mod_customlabel', 'contentfiles', 0);

    return true;
}

