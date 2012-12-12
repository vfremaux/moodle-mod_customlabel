<?php

/**
* implements a hook for the page_module block to add
* the link allowing live refreshing of the content
*
*
*/

function customlabel_set_instance(&$block){
    global $USER, $CFG, $COURSE;
    // transfer content from title to content    
    $block->content->text = $block->title;
    $block->title = '';

    // fake unpacks object's load
    $data = json_decode($block->moduleinstance->content);

    // If failed in getting content. It happens sometimes, ... do nothing to let content be safed manually
    if (is_null($data) || !is_object($data)){
        return false;
    }
    // realize a pseudo update
    $data->content = $block->moduleinstance->content;
    $data->labelclass = $block->moduleinstance->labelclass; // fixes broken serialized contents
    if (!isset($block->moduleinstance->title)) $block->moduleinstance->title = ''; // fixes broken serialized contents
    $instance = customlabel_load_class($data);
    $instance->preprocess_data();
    $instance->process_form_fields();
    $instance->process_datasource_fields();
    $instance->postprocess_data($COURSE);
    $block->moduleinstance->name = $instance->get_name(); // this realizes the template
    $block->moduleinstance->timemodified = time();
    $block->moduleinstance->title = str_replace("'", "''", $block->moduleinstance->title);
    $result = $DB->update_record('customlabel', $block->moduleinstance);
    return true;    
}

?>