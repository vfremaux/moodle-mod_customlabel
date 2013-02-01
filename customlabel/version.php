<?php // $Id: version.php,v 1.5 2012-12-28 22:53:38 vf Exp $

/////////////////////////////////////////////////////////////////////////////////
///  Code fragment to define the version of customlabel
///  This fragment is called by moodle_needs_upgrading() and /admin/index.php
/////////////////////////////////////////////////////////////////////////////////

$module->version  = 2012122800;  // The current module version (Date: YYYYMMDDXX)
$module->requires = 2007021541;  // Requires this Moodle version
$module->cron     = 0;           // Period for cron to check this module (secs)

?>