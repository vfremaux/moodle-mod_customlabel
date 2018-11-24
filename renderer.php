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
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 * @see Acces from adminmetadata.php
 */

defined('MOODLE_INTERNAL') || die();

class mod_customlabel_renderer extends plugin_renderer_base {

    public function set_choice($view, $q1, $q2) {
        global $DB, $OUTPUT;

        $template = new StdClass();

        $config = get_config('customlabel');
        $choosestr = get_string('choose');
        $choiceurl = new moodle_url('/mod/customlabel/adminmetadata.php');
        $table = $config->classification_type_table;
        $valuetypes = $DB->get_records_menu($table, array('type' => 'category'), 'name', 'id,name');

        $template->choiceurl = $choiceurl;
        $template->view = $view;
        $template->valuetypesselect1 = html_writer::select($valuetypes, 'value1', $q1->value);
        $template->valuetypesselect2 = html_writer::select($valuetypes, 'value2', $q2->value);

        if ($q1->value == $q2->value) {
            $template->sametypesignal = $OUTPUT->notification(get_string('sametypes', 'customlabel'));
        }

        return $this->output->render_from_template('mod_customlabel/set_choice', $template);
    }

    public function constraints_form($view, $q1, $q2) {
        global $DB;

        $config = get_config('customlabel');

        $sql = "
            SELECT
                cc.*
            FROM
                {{$config->classification_constraint_table}} cc,
                {{$config->classification_value_table}} cv1,
                {{$config->classification_value_table}} cv2
            WHERE
                cv1.id = cc.value1 AND
                cv2.id = cc.value2 AND
                ((cv1.typeid = ? AND
                cv2.typeid = ?) OR (cv1.typeid = ? AND
                cv2.typeid = ?))
        ";

        $constraints = $DB->get_records_sql($sql, array($q1->value, $q2->value, $q2->value, $q1->value));
        if ($constraints) {
            foreach ($constraints as $constraint) {
                // Get always the longest type ID first in matrix grid (columns).
                $values[$constraint->value1][$constraint->value2] = $constraint->const;
            }
        }

        $template = new StdClass;

        $template->formurl = new moodle_url('/mod/customlabel/adminmetadata.php');
        $template->view = $view;
        $template->value1 = $q1->value;
        $template->value2 = $q2->value;

        // Generate first row.
        if ($q2->valueset) {
            foreach ($q1->valueset as $avalue1) {
                $template->value1set[] = $avalue1;
            }
        }

        $options[0] = get_string('exclude', 'customlabel');
        $options[1] = get_string('include', 'customlabel');

        $i = 0;
        if ($q2->valueset) {
            foreach ($q2->valueset as $avalue2) {
                $value2settpl = $avalue2;
                $j = 0;
                if ($q1->valueset) {
                    foreach ($q1->valueset as $avalue1) {
                        $valuesettpl = clone($avalue1);
                        $valuea = $avalue1->id;
                        $valueb = $avalue2->id;

                        if ($valuea > $valueb) {
                            $tmp = $valueb;
                            $valueb = $valuea;
                            $valuea = $tmp;
                        }

                        $value = @$values[$valuea][$valueb];
                        $valuekey = "ct_{$valuea}_{$valueb}";
                        $class = ($value) ? 'green' : 'red';
                        $attrs = array('class' => 'customlabel-constraint-select '.$class);
                        $valuesettpl->optionsselect = html_writer::select($options, $valuekey, $value, '', $attrs);
                        $value2settpl->valueset[] = $valuesettpl;
                        $j++;
                    }
                }
                $template->value2set[] = $value2settpl;
                $i++;
            }
        }

        return $this->output->render_from_template('mod_customlabel/constraint_form', $template);
    }
}