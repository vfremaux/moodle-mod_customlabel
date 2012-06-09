<?php // $Id: version.php,v 1.6 2011-10-15 12:03:18 vf Exp $

/////////////////////////////////////////////////////////////////////////////////
///  Code fragment to define the version of customlabel
///  This fragment is called by moodle_needs_upgrading() and /admin/index.php
/////////////////////////////////////////////////////////////////////////////////

$module->version  = 2011092200;  // The current module version (Date: YYYYMMDDXX)
$module->requires = 2007021541;  // Requires this Moodle version
$module->cron     = 0;           // Period for cron to check this module (secs)

?>