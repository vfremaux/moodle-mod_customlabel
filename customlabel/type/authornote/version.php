<?php // $Id: version.php,v 1.6 2011-10-15 12:03:18 vf Exp $

/////////////////////////////////////////////////////////////////////////////////
///  Code fragment to define the version of customlabel
///  This fragment is called by moodle_needs_upgrading() and /admin/index.php
/////////////////////////////////////////////////////////////////////////////////

$plugin = new stdclass;
$plugin->version  = 2012062400;  // The current module version (Date: YYYYMMDDXX)
$plugin->component = 'customlabeltype_authornote';   // Full name of the plugin (used for diagnostics)

?>