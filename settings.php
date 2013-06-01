<?php

include_once $CFG->dirroot.'/mod/customlabel/locallib.php';

$classes = customlabel_get_classes(null, false);
$roles = $DB->get_records_menu ('role', array(), 'name', 'id,name') ;

$settings->add(new admin_setting_heading('regeneration', get_string('regeneration', 'customlabel'), "<a href=\"{$CFG->wwwroot}/mod/customlabel/admin_updateall.php\">".get_string('regenerate', 'customlabel')."</a>"));

$settings->add(new admin_setting_heading('classification', get_string('classification', 'customlabel'), "<a href=\"{$CFG->wwwroot}/mod/customlabel/adminmetadata.php\">".get_string('classification', 'customlabel')."</a>"));

$settings->add(new admin_setting_heading('roleaccesstoelements', get_string('roleaccesstoelements', 'customlabel'), ''));

foreach($classes as $class){
    $parmname = "customlabel_{$class->id}_enabled";
    $description = get_string('enabletype', 'customlabel') . ' ' . get_string('typename', 'customlabeltype_'.$class->id);
	$settings->add(new admin_setting_configcheckbox("list_$parmname", get_string('typename', 'customlabeltype_'.$class->id), get_string('configtypename', 'customlabeltype_'.$class->id), 1));
    $description = get_string('hiddenrolesfor', 'customlabel') . ' ' . get_string('typename', 'customlabeltype_'.$class->id);
    $parmname = "customlabel_{$class->id}_hiddenfor";
    $selection = explode(',', @$CFG->$parmname);
    $settings->add (new admin_setting_configmultiselect("$parmname", "customlabel_{$class->id}_hiddenfor", $description, $selection, $rolemenu));
}


