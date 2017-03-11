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
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 *
 * @see Acces from adminmetadata.php
 */
defined('MOODLE_INTERNAL') || die;

/************************************* Add ******************/
if ($action == 'add') {
    $data = $mform->get_data();
    $metadatavalue = new StdClass;
    $metadatavalue->typeid = clean_param($data->typeid, PARAM_INT);
    $metadatavalue->code = clean_param($data->code, PARAM_ALPHANUM);
    $metadatavalue->value = clean_param($data->value, PARAM_CLEANHTML);
    // Get max ordering.
    $params = array($CFG->classification_value_type_key => $data->typeid);
    $maxordering = $DB->get_field($CFG->classification_value_table, ' MAX(sortorder)', $params);
    $metadatavalue->sortorder = 1 + @$maxordering;
    if (!$DB->insert_record($CFG->classification_value_table, $metadatavalue)) {
        print_error('errorinservalue', 'customlabel');
    }
    redirect($url."?view=qualifiers&typeid={$data->typeid}");
}

/************************************* Update ******************/
if ($action == 'update') {
    $data = $mform->get_data();
    $metadatavalue = new StdClass;
    $metadatavalue->id = clean_param($data->id, PARAM_INT);
    $metadatavalue->code = clean_param($data->code, PARAM_ALPHANUM);
    $metadatavalue->value = clean_param($data->value, PARAM_CLEANHTML);
    if (!$DB->update_record($CFG->classification_value_table, $metadatavalue)) {
        print_error('errorupdatevalue', 'customlabel');
    }
    redirect($url."?view=qualifiers&typeid={$data->typeid}");
}

/*********************************** get a value for editing ************************/
if ($action == 'edit') {
    $valueid = required_param('valueid', PARAM_INT);
    $data = $DB->get_record($CFG->classification_value_table, array('id' => $valueid));
}
/*********************************** moves up ************************/
if ($action == 'up') {
    $id = required_param('valueid', PARAM_INT);
    classification_tree_up($id, $type);
    classification_tree_updateordering(0, $type);
}
/*********************************** moves down ************************/
if ($action == 'down') {
    $id = required_param('valueid', PARAM_INT);
    classification_tree_down($id, $type);
    classification_tree_updateordering(0, $type);
}

/************************************* Delete safe (only if not used) ******************/
if ($action == 'delete') {
    /*
     * todo check if there is no instances working with such type.
     * if not, bounce to forcedelete
     */
    $action = 'forcedelete';
}

/************************************* Delete unsafe ******************/
if ($action == 'forcedelete') {
    $id = required_param('valueid', PARAM_INT);
    $value = $DB->get_record($CFG->classification_value_table, array('id' => $id));

    if ($value) {
        // Delete related constraints.
        $DB->delete_records($CFG->classification_constraint_table, array('value1' => $id));
        $DB->delete_records($CFG->classification_constraint_table, array('value2' => $id));

        $DB->delete_records($CFG->classification_value_table, array('id' => $id));
    }
}

/**
 * updates ordering of a tree branch from a specific node, reordering
 * all subsequent siblings.
 * @param id the node from where to reorder
 * @param table the table-tree
 */
function classification_tree_updateordering($id, $type) {
    global $CFG, $DB;

    // Getting ordering value of the current node.

    $res = $DB->get_record($CFG->classification_value_table, array('id' => $id));
    if (!$res) {
        // Fallback : we give the ordering.
        $res->sortorder = $id;
    };
    // Start reorder from the immediate lower (works from ordering = 0).
    $prev = $res->sortorder - 1;

    // Getting subsequent nodes.
    $query = "
        SELECT
            id
        FROM
            {{$CFG->classification_value_table}}
        WHERE
            sortorder > {$prev} AND
            {$CFG->classification_value_type_key} = $type
        ORDER BY
            sortorder
    ";

    // Reordering subsequent nodes using an object.
    if ( $nextsubs = $DB->get_records_sql($query)) {
        $ordering = $res->sortorder;
        foreach ($nextsubs as $asub) {
            $objet->id = $asub->id;
            $objet->sortorder = $ordering;
            $DB->update_record($CFG->classification_value_table, $objet);
            $ordering++;
        }
    }
}

/**
 * raises a node in the tree, reordering all what needed
 * @param id the id of the raised node
 * @param table the table-tree where to operate
 * @return void
 */
function classification_tree_up($id, $type) {
    global $CFG, $DB;

    $res = $DB->get_record($CFG->classification_value_table, array('id' => $id));
    if (!$res) {
        return;
    }

    if ($res->sortorder >= 1) {
        $newordering = $res->sortorder - 1;

        $query = "
            SELECT
                id
            FROM 
                {{$CFG->classification_value_table}}
            WHERE
                sortorder = $newordering AND
                {$CFG->classification_value_type_key} = $type
        ";
        $result = $DB->get_record_sql($query);
        $resid = $result->id;

        // Swapping.
        $objet->id = $resid;
        $objet->sortorder = $res->sortorder;
        $DB->update_record($CFG->classification_value_table, $objet);

        $objet->id = $id;
        $objet->sortorder = $newordering;
        $DB->update_record($CFG->classification_value_table, $objet);
    }
}

/**
 * lowers a node on its branch. this is done by swapping ordering.
 * @param id the node id
 * @param istree if not set, performs swapping on a single list
 */
function classification_tree_down($id, $type) {
    global $CFG, $DB;

    $res = $DB->get_record($CFG->classification_value_table, array('id' => $id));

    $query = "
        SELECT
            MAX(sortorder) AS sortorder
        FROM
            {{$CFG->classification_value_table}}
        WHERE
            {$CFG->classification_value_type_key} = $type
    ";

    $resmaxordering = $DB->get_record_sql($query);
    $maxordering = $resmaxordering->sortorder;

    if ($res->sortorder < $maxordering) {
        $newordering = $res->sortorder + 1;

        $query = "
            SELECT
                id
            FROM
                {{$CFG->classification_value_table}}
            WHERE
                sortorder = $newordering AND
                {$CFG->classification_value_type_key} = $type
        ";
        $result = $DB->get_record_sql($query);
        $resid = $result->id;

        // Swapping.
        $objet->id = $resid;
        $objet->sortorder = $res->sortorder;
        $DB->update_record($CFG->classification_value_table, $objet);

        $objet->id = $id;
        $objet->sortorder = $newordering;
        $DB->update_record($CFG->classification_value_table, $objet);
    }
}

