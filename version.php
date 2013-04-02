<?php // $Id: version.php,v 1.6 2011-10-15 12:03:18 vf Exp $

/////////////////////////////////////////////////////////////////////////////////
///  Code fragment to define the version of customlabel
///  This fragment is called by moodle_needs_upgrading() and /admin/index.php
/////////////////////////////////////////////////////////////////////////////////

$module->version  = 2012062401;  // The current module version (Date: YYYYMMDDXX)
$module->requires = 2012061700;
$module->component = 'mod_customlabel';   // Full name of the plugin (used for diagnostics)
$module->cron     = 0;           // Period for cron to check this module (secs)
$module->maturity = MATURITY_RC;           // Maturity 
$module->release = "2.3.0 (Build 2012062401)"; // Release
