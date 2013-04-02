<?php
/**
 * Moodle - Modular Object-Oriented Dynamic Learning Environment
 *          http://moodle.org
 * Copyright (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    moodle
 * @subpackage local
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->libdir . '/adminlib.php');

// admin_externalpage_setup('metadata');

$view = optional_param('view', 'metadata', PARAM_ALPHA);

$navlinks[] = array(
	'name' => get_string('administration'),
	'link' => '',
	'type' => 'title');
$navlinks[] = array(
	'name' => get_string('modulenameplural', 'customlabel'),
	'link' => $CFG->wwwroot.'/admin/settings.php?section=modsettingcustomlabel',
	'type' => 'url');
$navlinks[] = array(
	'name' => get_string('classification', 'customlabel'),
	'link' => $CFG->wwwroot.'/mod/customlabel/adminmetadata.php',
	'type' => 'url');
$navlinks[] = array(
	'name' => get_string($view, 'customlabel'),
	'link' => $CFG->wwwroot.'/mod/customlabel/adminmetadata.php?view='.$view,
	'type' => 'url');

print_header($SITE->fullname, $SITE->fullname, build_navigation($navlinks));

print_heading(get_string('adminmetadata', 'customlabel'));

$action = optional_param('what', '', PARAM_ALPHA);

if (!preg_match("/classifiers|metadata|constraints/", $view)) $view = 'metadata';
$tabname = get_string('classifiers', 'customlabel');
$row[] = new tabobject('classifiers', $CFG->wwwroot."/mod/customlabel/adminmetadata.php?view=classifiers", $tabname);
$tabname = get_string('classification', 'customlabel');
$row[] = new tabobject('metadata', $CFG->wwwroot."/mod/customlabel/adminmetadata.php?view=metadata", $tabname);
$tabname = get_string('constraints', 'customlabel');
$row[] = new tabobject('constraints', $CFG->wwwroot."/mod/customlabel/adminmetadata.php?view=constraints", $tabname);
$tabrows[] = $row;
print_tabs($tabrows, $view);

// MVC debug
// echo "[$view : $action]";

switch($view){
    case 'classifiers':{
        include $CFG->dirroot.'/mod/customlabel/metadatatypes.php';
        break;
    }
    case 'metadata':{
        include $CFG->dirroot."/mod/customlabel/metadatavalues.php";
        break;
    }
    case 'constraints':{
        include $CFG->dirroot."/mod/customlabel/metadataconstraints.php";
        break;
    }
}

print_footer();


?>
