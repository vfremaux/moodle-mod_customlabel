<?php // $Id: index.php,v 1.3 2011-07-07 14:01:21 vf Exp $

    require_once("../../config.php");
    // require_once($CFG->dirroot.'/mod/customlabel/lib.php');

    $id = required_param('id', PARAM_INT);   // course

    redirect($CFG->wwwroot."/course/view.php?id=$id");

?>