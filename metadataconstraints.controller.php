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
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 *
 * @see Acces from adminmetadata.php
 */

defined('MOODLE_INTERNAL') || die();

class constraints_controller {

    public function __construct($q1, $q2) {
        $this->q1 = $q1;
        $this->q2 = $q2;
    }

    public function process($action) {
        global $DB;

        $config = get_config('customlabel');

        if ($action == 'save') {
            $data = data_submitted();
            $value1list = implode("','", array_keys($this->q1->valueset));
            $value2list = implode("','", array_keys($this->q2->valueset));

            $table = $config->classification_constraint_table;
            $DB->delete_records_select($table, "value1 IN ('{$value1list}') AND value2 IN ('{$value2list}')");
            $DB->delete_records_select($table, " value1 IN ('{$value2list}') AND value2 IN ('{$value1list}')");

            $constraintdata = preg_grep("/ct_\\d+_\\d+/", array_keys((array)$data));
            foreach ($constraintdata as $constraintkey) {
                preg_match("/ct_(\\d+)_(\\d+)/", $constraintkey, $matches);
                $id1 = $matches[1];
                $id2 = $matches[2];

                // Swap to ensure lower always first.
                if ($id1 > $id2) {
                    $tmp = $id1;
                    $id1 = $id2;
                    $id2 = $tmp;
                }

                $key = "ct_{$id1}_{$id2}";
                if ($data->{$key} > 0) {
                    $constraintrec = new StdClass();
                    $constraintrec->value1 = $id1;
                    $constraintrec->value2 = $id2;
                    $constraintrec->const = $data->{$key};
                    $DB->insert_record($config->classification_constraint_table, $constraintrec);
                }
            }
        }
    }
}
