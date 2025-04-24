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
 * @package    customlabeltype_localdokuwikicontent
 *
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$key = 'customlabeltype_localdokuwikicontent/basedir';
$label = get_string('configbasedir', 'customlabeltype_localdokuwikicontent');
$desc = get_string('configbasedir_desc', 'customlabeltype_localdokuwikicontent');
$settings->add(new admin_setting_configtext($key, $label, $desc, '', PARAM_RAW, ''));

$key = 'customlabeltype_localdokuwikicontent/webroot';
$label = get_string('configwebroot', 'customlabeltype_localdokuwikicontent');
$desc = get_string('configwebroot_desc', 'customlabeltype_localdokuwikicontent');
$settings->add(new admin_setting_configtext($key, $label, $desc, '', PARAM_TEXT, ''));

$key = 'customlabeltype_localdokuwikicontent/accesstoken';
$label = get_string('configaccesstoken', 'customlabeltype_localdokuwikicontent');
$desc = get_string('configaccesstoken_desc', 'customlabeltype_localdokuwikicontent');
$settings->add(new admin_setting_configtext($key, $label, $desc, '', PARAM_RAW, ''));

$key = 'customlabeltype_localdokuwikicontent/defaultlocal';
$label = get_string('configdefaultlocal', 'customlabeltype_localdokuwikicontent');
$desc = get_string('configdefaultlocal_desc', 'customlabeltype_localdokuwikicontent');
$settings->add(new admin_setting_configcheckbox($key, $label, $desc, 1, PARAM_BOOL, ''));

$key = 'customlabeltype_localdokuwikicontent/defaultremotehost';
$label = get_string('configdefaultremotehost', 'customlabeltype_localdokuwikicontent');
$desc = get_string('configdefaultremotehost_desc', 'customlabeltype_localdokuwikicontent');
$settings->add(new admin_setting_configtext($key, $label, $desc, '', PARAM_TEXT, ''));

$key = 'customlabeltype_localdokuwikicontent/defaultremotetoken';
$label = get_string('configdefaultremotetoken', 'customlabeltype_localdokuwikicontent');
$desc = get_string('configdefaultremotetoken_desc', 'customlabeltype_localdokuwikicontent');
$settings->add(new admin_setting_configtext($key, $label, $desc, '', PARAM_TEXT, ''));
