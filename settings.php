<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/customlabel/adminlib.php');
require_once($CFG->dirroot.'/mod/customlabel/lib.php');

$label = new lang_string('pluginname', 'customlabel');
$ADMIN->add('modsettings', new admin_category('modcustomlabelfolder', $label, $module->is_enabled() === false));

$label = get_string('settings', 'customlabel');
$settings = new admin_settingpage($section, $label, 'moodle/site:config', $module->is_enabled() === false);

if ($ADMIN->fulltree) {
    $menu = array();
    foreach (core_component::get_plugin_list('customlabeltype') as $type => $notused) {
        $visible = !get_config('customlabeltype_' . $type, 'disabled');
        if ($visible) {
            $menu['customlabeltype_' . $type] = new lang_string('pluginname', 'customlabeltype_' . $type);
        }
    }

    $label = get_string('classification', 'customlabel');
    $settingurl = new moodle_url('/mod/customlabel/adminmetadata.php');
    $desc = '<a href="'.$settingurl.'">'.get_string('classification', 'customlabel').'</a>';
    $settings->add(new admin_setting_heading('classification', $label, $desc));

    $settings->add(new admin_setting_heading('apparence', get_string('apparence', 'customlabel'), ''));

    $key = 'customlabel/defaultskin';
    $label = get_string('defaultskin', 'customlabel');
    $desc = get_string('defaultskin_desc', 'customlabel');
    $skinoptions = [
        'default' => get_string('defaultstyle', 'customlabel'),
        'flatstyle' => get_string('flatstyle', 'customlabel'),
        'colored' => get_string('coloredstyle', 'customlabel'),
        'flatstyle colored' => get_string('flatcoloredstyle', 'customlabel')
    ];

    $namedskins = glob($CFG->dirroot.'/mod/customlabel/pix/skins/*');
    if (!empty($namedskins)) {
        foreach ($namedskins as $skinpath) {
            $skinname = basename($skinpath);
            if ($skinname == '.' || $skinname == '..') {
                continue;
            }
            if (!is_dir($skinpath)) {
                continue;
            }
            $skinoptions[$skinname] = $skinname;
        }
    }

    $default = 'default';
    $settings->add(new admin_setting_configselect($key, $label, $desc, $default, $skinoptions));

    $key = 'customlabel/disabled';
    $label = get_string('disabledsubtypes', 'customlabel');
    $desc = get_string('disabledsubtypes_desc', 'customlabel');
<<<<<<< HEAD
    $default = 'genericgoals,genericpractices,processgoals,processpractices';
    $settings->add(new admin_setting_configtextarea($key, $label, $desc, $default, PARAM_RAW, 80, 10));
=======
    $settings->add(new admin_setting_configtextarea($key, $label, $desc, '', PARAM_RAW, 80, 10));
>>>>>>> 3ffadab7e7686124b23744b85fce919514a24c9e

    /*
     * This is a similar metadata binding schema that used in the local_courseindex component in order to provide
     * a loose dependancy link between both components
     */

    $label = get_string('configmetadatabinding', 'customlabel');
    $desc = get_string('configmetadatabinding_desc', 'customlabel');
    $settings->add(new admin_setting_heading('metadatabinding', $label, $desc));

    $key = 'customlabel/course_metadata_table';
    $label = get_string('configcoursemetadatatable', 'customlabel');
    $desc = get_string('configcoursemetadatatable_desc', 'customlabel');
    $settings->add(new admin_setting_configtext($key, $label, $desc, 'customlabel_course_metadata'));

    $key = 'customlabel/course_metadata_course_key';
    $label = get_string('configcoursemetadatacoursekey', 'customlabel');
    $desc = get_string('configcoursemetadatacoursekey_desc', 'customlabel');
    $settings->add(new admin_setting_configtext($key, $label, $desc, 'courseid'));

    $key = 'customlabel/course_metadata_value_key';
    $label = get_string('configcoursemetadatavaluekey', 'customlabel');
    $desc = get_string('configcoursemetadatavaluekey_desc', 'customlabel');
    $settings->add(new admin_setting_configtext($key, $label, $desc, 'valueid'));

    $key = 'customlabel/course_metadata_cmid_key';
    $label = get_string('configcoursemetadatacmidkey', 'customlabel');
    $desc = get_string('configcoursemetadatacmidkey_desc', 'customlabel');
    $settings->add(new admin_setting_configtext($key, $label, $desc, 'cmid'));

    $key = 'customlabel/classification_value_table';
    $label = get_string('configclassificationvaluetable', 'customlabel');
    $desc = get_string('configclassificationvaluetable_desc', 'customlabel');
    $settings->add(new admin_setting_configtext($key, $label, $desc, 'customlabel_mtd_value'));

    $key = 'customlabel/classification_value_type_key';
    $label = get_string('configclassificationvaluetypekey', 'customlabel');
    $desc = get_string('configclassificationvaluetypekey_desc', 'customlabel');
    $settings->add(new admin_setting_configtext($key, $label, $desc, 'typeid'));

    $key = 'customlabel/classification_type_table';
    $label = get_string('configclassificationtypetable', 'customlabel');
    $desc = get_string('configclassificationtypetable_desc', 'customlabel');
    $settings->add(new admin_setting_configtext($key, $label, $desc, 'customlabel_mtd_type'));

    $key = 'customlabel/classification_constraint_table';
    $label = get_string('configclassificationconstrainttable', 'customlabel');
    $desc = get_string('configclassificationconstrainttable_desc', 'customlabel');
    $settings->add(new admin_setting_configtext($key, $label, $desc, 'customlabel_mtd_constraint'));

    if (customlabel_supports_feature('emulate/community') == 'pro') {
        include_once($CFG->dirroot.'/mod/customlabel/pro/prolib.php');
        $promanager = mod_customlabel\pro_manager::instance();
        $promanager->add_settings($ADMIN, $settings);
    } else {
        $label = get_string('plugindist', 'customlabel');
        $desc = get_string('plugindist_desc', 'customlabel');
        $settings->add(new admin_setting_heading('plugindisthdr', $label, $desc));
    }
}

$ADMIN->add('modcustomlabelfolder', $settings);
// Tell core we already added the settings structure.
$settings = null;

$ADMIN->add('modcustomlabelfolder', new admin_category('customlabeltypeplugins',
    new lang_string('customlabelplugins', 'customlabel'), !$module->is_enabled()));
$ADMIN->add('customlabeltypeplugins', new customlabel_admin_page_manage_customlabel_plugins('customlabeltype'));

foreach (core_plugin_manager::instance()->get_plugins_of_type('customlabeltype') as $plugin) {
    $plugin->load_settings($ADMIN, 'customlabelplugins', $hassiteconfig);
}
