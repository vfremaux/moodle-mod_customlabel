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

defined('MOODLE_INTERNAL') || die();

/**
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 * @see Acces from adminmetadata.php
 */

class mod_customlabel_renderer extends plugin_renderer_base {

    function set_choice($view, $q1, $q2) {
        global $DB, $OUTPUT;

        $str = '';

        $config = get_config('customlabel');
        $choosestr = get_string('choose');
        $choiceurl = new moodle_url('/mod/customlabel/adminmetadata.php');
        $valuetypes = $DB->get_records_menu($config->classification_type_table, array(), 'name', 'id,name');

        $str .= '<form name="choosesets" method="POST" action="'.$choiceurl.'">';
        $str .= '<input type="hidden" name="view" value="'.$view.'" />';
        $str .= '<table>';
        $str .= '<tr>';
        $str .= '<td>';
        $str .= html_writer::select($valuetypes, 'value1', $q1->value);
        $str .= '</td>';
        $str .= '<td>';
        $str .= html_writer::select($valuetypes, 'value2', $q2->value);
        $str .= '</td>';
        $str .= '<td>';
        $str .= '<input type="submit" name="choose_btn" value="'.$choosestr.'" />';
        $str .= '</td>';
        $str .= '</tr>';
        $str .= '</table>';
        $str .= '</form>';

        if ($q1->value == $q2->value) {
            $str .= '<br/>';
            $str .= $OUTPUT->notification(get_string('sametypes', 'customlabel'));
            $str .= '<br/>';
            $str .= $OUTPUT->footer();
            echo $str;
            die;
        }

        return $str;
    }

    function constraints_form($view, $q1, $q2) {
        global $DB;

        $config = get_config('customlabel');

        $constraints = $DB->get_records($config->classification_constraint_table);
        if ($constraints) {
            foreach ($constraints as $constraint) {
                // Get always the longest type ID first in matrix grid (columns).
                $values[$constraint->value1][$constraint->value2] = $constraint->const;
            }
        }

        $str = '';

        $formurl = new moodle_url('/mod/customlabel/adminmetadata.php');
        $str .= '<form name="constraintsform" method="POST" action="'.$formurl.'">';
        $str .= '<input type="hidden" name="view" value="'.$view.'" />';
        $str .= '<input type="hidden" name="value1" value="'.$q1->value.'" />';
        $str .= '<input type="hidden" name="value2" value="'.$q2->value.'" />';
        $str .= '<input type="hidden" name="what" value="save" />';
        $str .= '<table>';
        $str .= '<tr valign="top">';
        $str .= '<td>';
        $str .= '</td>';

        // generate first row
        if ($q2->valueset) {
            foreach ($q1->valueset as $avalue1) {
                $str .= '<td align="center"><b>'.$avalue1->value.'</b></td>';
            }
        }
        $str .= '</tr>';

        $options[0] = get_string('exclude', 'customlabel');
        $options[1] = get_string('include', 'customlabel');

        $i = 0;
        if ($q2->valueset) {
            foreach ($q2->valueset as $avalue2) {
                $j = 0;
                $str .= '<tr valign="top">';
                $str .= '<td align="center">'.$avalue2->value.'</td>';
                if ($q1->valueset) {
                    foreach ($q1->valueset as $avalue1) {
                        $valuea = $avalue1->id;
                        $valueb = $avalue2->id;

                        if ($valuea > $valueb) {
                            $tmp = $valueb;
                            $valueb = $valuea;
                            $valuea = $tmp;
                        }

                        $value = @$values[$valuea][$valueb];
                        $valuekey = "ct_{$valuea}_{$valueb}";
                        $class = ($value) ? 'included' : '';
                        $str .= '<td class="constraint-cell '.$class.'">';
                        $str .= html_writer::select($options, $valuekey, $value);
                        $str .= '</td>';
                        $j++;
                    }
                }
                $str .= '</tr>';
                $i++;
            }
        }
        $str .= '</table>';
        $str .= '</p>';

        return $str;
    }
}