<?php

/**
* implements a hook for the page_module block to add
* the link allowing live refreshing of the content
*
*
*/

function customlabel_set_instance(&$block){
    global $USER, $CFG, $COURSE, $DB;
    // transfer content from title to content    
    $block->title = '';

    // fake unpacks object's load
    $data = json_decode(base64_decode($block->moduleinstance->content));

    // If failed in getting content. It happens sometimes, ... do nothing to let content be safed manually
    if (is_null($data) || !is_object($data)){
        return false;
    }
    
    // realize a pseudo update
    $data->title = $block->moduleinstance->title;
    $data->content = $block->moduleinstance->content;
    $data->labelclass = $block->moduleinstance->labelclass; // fixes broken serialized contents
    if (!isset($block->moduleinstance->title)) $block->moduleinstance->title = ''; // fixes broken serialized contents
    $instance = customlabel_load_class($data);
    $block->moduleinstance->processedcontent = $instance->make_content();
    $block->moduleinstance->name = $instance->title; // this realizes the template
    $block->moduleinstance->timemodified = time();
    $block->content->text = $block->moduleinstance->processedcontent;
    $block->moduleinstance->title = str_replace("'", "''", $block->moduleinstance->title);
    $result = $DB->update_record('customlabel', $block->moduleinstance);
    return true;    
}

?>