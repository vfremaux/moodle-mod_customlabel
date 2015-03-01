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
 * @package    mod
 * @subpackage customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

$value1 = optional_param('value1', '', PARAM_TEXT);
$value2 = optional_param('value2', '', PARAM_TEXT);

if ($value1 == $value2) {
    $noticesametypes = true;
    $value1 = '';
    $value2 = '';
}

// Swap to avoid inverted map.
if ($value1 < $value2) {
    $tmp = $value1;
    $value1 = $value2;
    $value2 = $tmp;
}

$value1types = $DB->get_records_menu($CFG->classification_type_table, array(), 'name', 'id,name');
$value2types = $DB->get_records_menu($CFG->classification_type_table, array(), 'name', 'id,name');

$valueset1 = $DB->get_records($CFG->classification_value_table, array($CFG->classification_value_type_key => $value1));
$valueset2 = $DB->get_records($CFG->classification_value_table, array($CFG->classification_value_type_key => $value2));

if ($action != '') {
    include $CFG->dirroot."/mod/customlabel/metadataconstraints.controller.php";
}

$constraints = $DB->get_records($CFG->classification_constraint_table);
if ($constraints) {
    foreach ($constraints as $constraint) {
        $values[$constraint->value1][$constraint->value2] = $constraint->const;
    }
}

// Print table.

echo $deferredheader;

?>
<form name="choosesets" method="POST" action="<?php echo $CFG->wwwroot."/mod/customlabel/adminmetadata.php" ?>">
<input type="hidden" name="view" value="<?php p($view) ?>" />
<table>
    <tr>
        <td>
            <?php echo html_writer::select($value1types, 'value1', $value1) ?>
        </td>
        <td>
            <?php echo html_writer::select($value2types, 'value2', $value2) ?>
        </td>
        <td>
            <input type="submit" name="choose_btn" value="<?php print_string('choose') ?>" />
        </td>
    </tr>
</table>
</form>

<?php
if (isset($noticesametypes)) {
    echo "<br/>";
    notice(get_string('sametypes', 'customlabel'));
    echo "<br/>";
    return;
}
?>

<p>
<form name="constraintsform" method="POST" action="<?php echo $CFG->wwwroot."/mod/customlabel/adminmetadata.php" ?>">
<input type="hidden" name="view" value="<?php p($view) ?>" />
<input type="hidden" name="value1" value="<?php p($value1) ?>" />
<input type="hidden" name="value2" value="<?php p($value2) ?>" />
<input type="hidden" name="what" value="save" />
<table>
    <tr valign="top">
        <td>
        </td>
<?php
// generate first row
if ($valueset1) {
    foreach ($valueset1 as $avalue1) {
        echo "<td align=\"center\"><b>{$avalue1->value}</b></td>";
    }
}
?>
    </tr>
<?php
$options[0] = get_string('none', 'customlabel');
$options[1] = get_string('include', 'customlabel');
$options[2] = get_string('exclude', 'customlabel');

$i = 0;
if ($valueset2) {
    foreach ($valueset2 as $avalue2) {
        $j = 0;
        echo '<tr valign="top">';
        echo "<td align=\"center\">{$avalue2->value}</td>";
        if ($valueset1) {
            foreach ($valueset1 as $avalue1) {        
                echo "<td align=\"center\">";
                echo html_writer::select($options, "ct_{$avalue1->id}_{$avalue2->id}", @$values[$avalue1->id][$avalue2->id]);        
                echo "</td>";
                $j++;
            }
        }
        echo '</tr>';
        $i++;
    }
}
?>
</table>
</p>

<p><table width="100%">
    <tr>
        <td align="center">
            <input type="submit" name="go_btn" value="<?php print_string('update') ?>" />
        </td>
    </tr>
</table></p>
</form>
