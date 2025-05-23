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
 * Edits contraints between two classification domains.
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux <valery.fremaux@gmail.com> (www.activeProLearn.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

$config = get_config('customlabel');

$q1 = new StdClass();
$q2 = new StdClass();
$q1->value = optional_param('value1', '', PARAM_TEXT);
$q2->value = optional_param('value2', '', PARAM_TEXT);

if ($q1->value == $q2->value) {
    $noticesametypes = true;
    $q1->value = '';
    $q2->value = '';
}

$classtable = $config->classification_value_table;
$q1->countvalueset = $DB->count_records($classtable, [$config->classification_value_type_key => $q1->value]);
$q2->countvalueset = $DB->count_records($classtable, [$config->classification_value_type_key => $q2->value]);
$q1->valueset = $DB->get_records($classtable, [$config->classification_value_type_key => $q1->value]);
$q2->valueset = $DB->get_records($classtable, [$config->classification_value_type_key => $q2->value]);

if ($action != '') {
    include_once($CFG->dirroot.'/mod/customlabel/metadataconstraints.controller.php');
    $controller = new constraints_controller($q1, $q2);
    $controller->process($action);
}

if ($q1->countvalueset > $q2->countvalueset) {
    $tmp = $q1;
    $q1 = $q2;
    $q2 = $tmp;
}

// Print table.

echo $deferredheader;

$renderer = $PAGE->get_renderer('mod_customlabel');

if (!$DB->count_records($config->classification_type_table, ['type' => 'category'])) {
    echo $OUTPUT->notification(get_string('noclassifiersdefined', 'customlabel'), 'warning');
} else {

    echo $renderer->set_choice($view, $q1, $q2);

    if ($q1->value != $q2->value) {
        echo $renderer->constraints_form($view, $q1, $q2);
    }
}
