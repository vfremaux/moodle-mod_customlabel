<?php

include_once $CFG->dirroot.'/mod/customlabel/lib.php';

class mod_customlabel_flexpage extends block_flexpagemod_lib_mod{

    public function module_block_setup() {
        global $CFG, $COURSE, $DB;

        $cm       = $this->get_cm();
        $customlabel = $DB->get_record('customlabel', array('id' => $cm->instance));
        $instance = customlabel_load_class($customlabel, $customlabel->labelclass);
        $block = null;
        if ($customlabel and !customlabel_is_hidden_byrole($block, $cm->id)) {
        	$this->append_content($instance->get_content());
        }
    }
}