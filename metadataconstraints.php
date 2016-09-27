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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

defined('MOODLE_INTERNAL') || die();

$config = get_config('customlabel');

$q1 = new StdClass;
$q2 = new StdClass;
$q1->value = optional_param('value1', '', PARAM_TEXT);
$q2->value = optional_param('value2', '', PARAM_TEXT);

if ($q1->value == $q2->value) {
    $noticesametypes = true;
    $q1->value = '';
    $q2->value = '';
}

$classtable = $config->classification_value_table;
$q1->countvalueset = $DB->count_records($classtable, array($config->classification_value_type_key => $q1->value));
$q2->countvalueset = $DB->count_records($classtable, array($config->classification_value_type_key => $q2->value));
$q1->valueset = $DB->get_records($classtable, array($config->classification_value_type_key => $q1->value));
$q2->valueset = $DB->get_records($classtable, array($config->classification_value_type_key => $q2->value));

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

echo $renderer->set_choice($view, $q1, $q2);

echo $renderer->constraints_form($view, $q1, $q2);

?>

<p><table width="100%">
    <tr>
        <td align="center">
            <input type="submit" name="go_btn" value="<?php print_string('update') ?>" />
        </td>
    </tr>
</table></p>
</form>
