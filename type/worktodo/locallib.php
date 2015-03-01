<?php

/**
 * Get the course modules that can be linked as work to do
 */
function customlabel_get_candidate_modules() {
    global $COURSE, $DB, $CFG;

    if (!empty($CFG->upgraderunning)) {
        return;
    }

    $modinfo = get_fast_modinfo($COURSE);
    $modules = array();

    foreach ($modinfo->get_cms() as $cminfo) {
        if (!$cminfo->visible) continue;
        if (preg_match('/label$/', $cminfo->modname)) continue;
        include_once($CFG->dirroot.'/mod/'.$cminfo->modname.'/lib.php');
        $supportf = $cminfo->modname.'_supports';
        if (!$supportf(FEATURE_GRADE_HAS_GRADE) && !$supportf(FEATURE_GRADE_OUTCOMES)) {
            // Module seems it is not gradable so not a worktodo module
            continue;
        }
        $modules[$cminfo->module] = $cminfo->name;
    }

    return $modules;
}