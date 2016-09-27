<?php

$settings->add(new admin_setting_heading('regeneration', get_string('regeneration', 'customlabel'), "<a href=\"{$CFG->wwwroot}/mod/customlabel/admin_updateall.php\">".get_string('regenerate', 'customlabel')."</a>"));

$settings->add(new admin_setting_heading('classification', get_string('classification', 'customlabel'), "<a href=\"{$CFG->wwwroot}/mod/customlabel/adminmetadata.php\">".get_string('classification', 'customlabel')."</a>"));

$settings->add(new admin_setting_heading('apparence', get_string('apparence', 'customlabel'), ''));

$settings->add(new admin_setting_configtextarea("customlabel/cssoverrides", get_string('cssoverrides', 'customlabel'), get_string('cssoverridesdesc', 'customlabel'), '', PARAM_RAW, 80, 10));

$settings->add(new admin_setting_configtextarea("customlabel/disabled", get_string('disabledsubtypes', 'customlabel'), get_string('disabledsubtypesdesc', 'customlabel'), '', PARAM_RAW, 80, 10));

// This is a similar metadata binding schema that used in the local_courseindex component in order to provide 
// a loose dependancy link between both components

$settings->add(new admin_setting_heading('metadatabinding', get_string('configmetadatabinding', 'customlabel'), get_string('configmetadatabinding_desc', 'customlabel')));

$settings->add(new admin_setting_configtext('customlabel/course_metadata_table', get_string('configcoursemetadatatable', 'customlabel'),
                   get_string('configcoursemetadatatable_desc', 'customlabel'), 'customlabel_course_metadata'));

$settings->add(new admin_setting_configtext('customlabel/course_metadata_course_key', get_string('configcoursemetadatacoursekey', 'customlabel'),
                   get_string('configcoursemetadatacoursekey_desc', 'customlabel'), 'courseid'));

$settings->add(new admin_setting_configtext('customlabel/course_metadata_value_key', get_string('configcoursemetadatavaluekey', 'customlabel'),
                   get_string('configcoursemetadatavaluekey_desc', 'customlabel'), 'valueid'));

$settings->add(new admin_setting_configtext('customlabel/classification_value_table', get_string('configclassificationvaluetable', 'customlabel'),
                   get_string('configclassificationvaluetable_desc', 'customlabel'), 'customlabel_mtd_value'));

$settings->add(new admin_setting_configtext('customlabel/classification_value_type_key', get_string('configclassificationvaluetypekey', 'customlabel'),
                   get_string('configclassificationvaluetypekey_desc', 'customlabel'), 'typeid'));

$settings->add(new admin_setting_configtext('customlabel/classification_type_table', get_string('configclassificationtypetable', 'customlabel'),
                   get_string('configclassificationtypetable_desc', 'customlabel'), 'customlabel_mtd_type'));

$settings->add(new admin_setting_configtext('customlabel/classification_constraint_table', get_string('configclassificationconstrainttable', 'customlabel'),
                   get_string('configclassificationconstrainttable_desc', 'customlabel'), 'customlabel_mtd_constraint'));

