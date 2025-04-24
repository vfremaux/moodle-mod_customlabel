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
 * Administrates metadata
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux <valery.fremaux@gmail.com> (www.activeProLearn.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$context = context_system::instance();

require_login();
require_capability('mod/customlabel:managemetadata', $context);

$view = optional_param('view', 'metadata', PARAM_ALPHA);

$url = new moodle_url('/mod/customlabel/adminmetadata.php', ['view' => $view]);
$PAGE->navbar->add(get_string('administration'), '');
$settingsurl = new moodle_url('/admin/settings.php', ['section' => 'modsettingcustomlabel']);
$PAGE->navbar->add(get_string('modulenameplural', 'customlabel'), $settingsurl);
$metaurl = new moodle_url('/mod/customlabel/adminmetadata.php');
$PAGE->navbar->add(get_string('classification', 'customlabel'), $metaurl);
$PAGE->navbar->add(get_string($view, 'customlabel'), $url);
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading($SITE->fullname);
$deferredheader = $OUTPUT->header();

$deferredheader .= $OUTPUT->heading(get_string('adminmetadata', 'customlabel'));

$action = optional_param('what', '', PARAM_ALPHA);

if (!preg_match("/classifiers|metadata|constraints|model/", $view)) {
    $view = 'metadata';
}
$rows = [
    'classifiers' => 'classifiers',
    'classificationvalues' => 'metadata',
    'constraints' => 'constraints',
    'classificationmodel' => 'model',
];

foreach ($rows as $rowindex => $rowvalue) {
    $taburl = new moodle_url('/mod/customlabel/adminmetadata.php', ['view' => $rowvalue]);
    $row[] = new tabobject($rowvalue, $taburl, get_string($rowindex, 'customlabel'));
}
$tabrows[] = $row;
$deferredheader .= print_tabs($tabrows, $view, '', '', true);

switch ($view) {
    case 'classifiers':
        include($CFG->dirroot.'/mod/customlabel/metadatatypes.php');
        break;

    case 'metadata':
        include($CFG->dirroot.'/mod/customlabel/metadatavalues.php');
        break;

    case 'constraints':
        include($CFG->dirroot.'/mod/customlabel/metadataconstraints.php');
        break;

    case 'model':
        include($CFG->dirroot.'/mod/customlabel/metadatamodel.php');
        break;
}

echo $OUTPUT->footer();
