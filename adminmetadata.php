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
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

require_login();

$view = optional_param('view', 'metadata', PARAM_ALPHA);

$url = new moodle_url('/mod/customlabel/adminmetadata.php?view='.$view);
$PAGE->navbar->add(get_string('administration'), '');
$PAGE->navbar->add(get_string('modulenameplural', 'customlabel'), new moodle_url('/admin/settings.php?section=modsettingcustomlabel'));
$PAGE->navbar->add(get_string('classification', 'customlabel'), new moodle_url('/mod/customlabel/adminmetadata.php'));
$PAGE->navbar->add(get_string($view, 'customlabel'), $url);
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading($SITE->fullname);
$deferredheader = $OUTPUT->header();

$deferredheader .= $OUTPUT->heading(get_string('adminmetadata', 'customlabel'));

$action = optional_param('what', '', PARAM_ALPHA);

if (!preg_match("/classifiers|metadata|constraints|model/", $view)) $view = 'metadata';
$tabname = get_string('classifiers', 'customlabel');
$row[] = new tabobject('classifiers', $CFG->wwwroot."/mod/customlabel/adminmetadata.php?view=classifiers", $tabname);
$tabname = get_string('classification', 'customlabel');
$row[] = new tabobject('metadata', $CFG->wwwroot."/mod/customlabel/adminmetadata.php?view=metadata", $tabname);
$tabname = get_string('constraints', 'customlabel');
$row[] = new tabobject('constraints', $CFG->wwwroot."/mod/customlabel/adminmetadata.php?view=constraints", $tabname);
$tabname = get_string('classificationmodel', 'customlabel');
$row[] = new tabobject('model', $CFG->wwwroot."/mod/customlabel/adminmetadata.php?view=model", $tabname);
$tabrows[] = $row;
$deferredheader .= print_tabs($tabrows, $view, '', '', true);

// MVC debug
// echo "[$view : $action]";

switch ($view) {
    case 'classifiers':
        include $CFG->dirroot.'/mod/customlabel/metadatatypes.php';
        break;

    case 'metadata':
        include $CFG->dirroot."/mod/customlabel/metadatavalues.php";
        break;

    case 'constraints':
        include $CFG->dirroot."/mod/customlabel/metadataconstraints.php";
        break;

    case 'model':
        include $CFG->dirroot."/mod/customlabel/metadatamodel.php";
        break;

}

echo $OUTPUT->footer();

