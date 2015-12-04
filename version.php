<?php

/////////////////////////////////////////////////////////////////////////////////
///  Code fragment to define the version of customlabel
///  This fragment is called by moodle_needs_upgrading() and /admin/index.php
/////////////////////////////////////////////////////////////////////////////////

$plugin->version  = 2013041802; // The current module version (Date: YYYYMMDDXX)
$plugin->requires = 2014110400;
$plugin->component = 'mod_customlabel'; // Full name of the plugin (used for diagnostics)
$plugin->cron     = 0; // Period for cron to check this module (secs)
$plugin->maturity = MATURITY_STABLE; // Maturity
$plugin->release = "2.8.0 (Build 2013041802)"; // Release
