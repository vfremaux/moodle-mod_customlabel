<?php  // $Id: view.php,v 1.3 2011-07-07 14:01:23 vf Exp $

    /**
    * main view of a customlabel is always in its course container, unless
    * we display its XML exportation.
    *
    * @package    mod-customlabel
    * @category   mod
    * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
    * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
    * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
    */
    
    /**
    * Includes and requires
    */
    require_once("../../config.php");
    require_once("{$CFG->dirroot}/mod/customlabel/lib.php");

    $id = optional_param('id',0,PARAM_INT);    // Course Module ID, or
    $l = optional_param('l',0,PARAM_INT);     // Label ID
    $what = optional_param('what', '', PARAM_ALPHA);     // What to be seen
    
    if ($id) {
        if (! $cm = get_coursemodule_from_id('customlabel', $id)) {
            error("Course Module ID was incorrect");
        }
    
        if (! $course = get_record('course', 'id', $cm->course)) {
            error("Course is misconfigured");
        }
    
        if (! $customlabel = get_record('customlabel', 'id', $cm->instance)) {
            error("Course module is incorrect");
        }

    } else {
        if (! $customlabel = get_record('customlabel', 'id', $l)) {
            error("Course module is incorrect");
        }
        if (! $course = get_record('course', 'id', $customlabel->course)) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("customlabel", $customlabel->id, $course->id)) {
            error("Course Module ID was incorrect");
        }
    }

    require_login($course->id);

/// if we are exporting to XML

    if ($what == 'xml'){
        print_header_simple();
        
        $customlabel = get_record('customlabel', 'id', $l);
        $instance = customlabel_load_class($customlabel);
        $xml = $instance->get_xml();
        $xml = str_replace('<', '&lt;', $xml);
        $xml = str_replace('>', '&gt;', $xml);
        echo "<pre>".$xml."</pre>";        
        die;
    }

/// normal view "in-situ"

    redirect($CFG->wwwroot."/course/view.php?id={$course->id}");

?>
