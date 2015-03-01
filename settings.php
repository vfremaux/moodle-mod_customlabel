<?php

$settings->add(new admin_setting_heading('regeneration', get_string('regeneration', 'customlabel'), "<a href=\"{$CFG->wwwroot}/mod/customlabel/admin_updateall.php\">".get_string('regenerate', 'customlabel')."</a>"));

$settings->add(new admin_setting_heading('classification', get_string('classification', 'customlabel'), "<a href=\"{$CFG->wwwroot}/mod/customlabel/adminmetadata.php\">".get_string('classification', 'customlabel')."</a>"));

$settings->add(new admin_setting_heading('apparence', get_string('apparence', 'customlabel'), ''));

$settings->add(new admin_setting_configtextarea("customlabel/cssoverrides", get_string('cssoverrides', 'customlabel'), get_string('cssoverridesdesc', 'customlabel'), '', PARAM_RAW, 80, 10));

$settings->add(new admin_setting_configtextarea("customlabel/disabled", get_string('disabledsubtypes', 'customlabel'), get_string('disabledsubtypesdesc', 'customlabel'), '', PARAM_RAW, 80, 10));
