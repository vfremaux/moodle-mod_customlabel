<?php

/************************************* Add ******************/
if ($action == 'add') {
    $data = $mform->get_data();
    $metadatatype = new StdClass;
    $metadatatype->type = clean_param($data->type, PARAM_TEXT);
    $metadatatype->code = clean_param($data->code, PARAM_ALPHANUM);
    $metadatatype->name = clean_param($data->name, PARAM_CLEANHTML);
    $metadatatype->description = clean_param($data->description, PARAM_CLEANHTML);
    $maxordering = $DB->get_field($CFG->classification_type_table, ' MAX(sortorder) ', array());
    $metadatatype->sortorder = $maxordering + 1;
    if (!$DB->insert_record($CFG->classification_type_table, $metadatatype)) {
        error('Could not insert a new type');
    }
    redirect($url.'?view=classifiers');
}

/************************************* Update ******************/
if ($action == 'update') {
    $data = $mform->get_data();
    $metadatatype = new StdClass;
    $metadatatype->id = clean_param($data->id, PARAM_INT);
    $metadatatype->type = clean_param($data->type, PARAM_TEXT);
    $metadatatype->code = clean_param($data->code, PARAM_ALPHANUM);
    $metadatatype->name = clean_param($data->name, PARAM_CLEANHTML);
    $metadatatype->description = clean_param($data->description, PARAM_CLEANHTML);
    if (!$DB->update_record($CFG->classification_type_table, $metadatatype)) {
        error('Could not insert a new type');
    }
    redirect($url.'?view=classifiers');
}

/*********************************** get a type for editing ************************/
if ($action == 'edit') {
    $typeid = required_param('typeid', PARAM_INT);
    $data = $DB->get_record($CFG->classification_type_table, array('id' => $typeid));    
}

/*********************************** moves up ************************/
if ($action == 'up') {
    $id = required_param('typeid', PARAM_INT);
    classification_tree_up($id);
}

/*********************************** moves down ************************/
if ($action == 'down') {
    $id = required_param('typeid', PARAM_INT);
    classification_tree_down($id);
}

/************************************* Delete safe (only if not used) ******************/
if ($action == 'delete') {
    $id = required_param('typeid', PARAM_INT);
    $typeorder = $DB->get_field($CFG->classification_type_table, 'sortorder', array('id' => $id));
    if (!$DB->delete_records($CFG->classification_type_table, array('id' => $id))) {
        print_error('could not delete classifier');
    }
    // clear all sub values
    if ($valueids = $DB->get_records_menu($CFG->classification_type_table, array('type' => $id), 'id,id')) {

        $DB->delete_records($CFG->classification_value_table, array($CFG->classification_value_type_key => $id));
        // clear constraint records
        $valueidslist = implode("','", array_keys($valueids));
        $DB->delete_records_select($CFG->classification_constraint_table, "value1 IN ('$valueidslist') ");
        $DB->delete_records_select($CFG->classification_constraint_table, "value2 IN ('$valueidslist') ");
        // clear course assignations
        $DB->delete_records_select($CFG->course_metadata_table, " {$CFG->course_metadata_value_key} IN ('$valueidslist') " );
    }

    classification_tree_updateordering($typeorder);
}

/**
 * updates ordering of a tree branch from a specific node, reordering 
 * all subsequent siblings. 
 * @param id the node from where to reorder
 * @param table the table-tree
 */
function classification_tree_updateordering($id) {
    global $CFG, $DB;

    $res =  $DB->get_record($CFG->classification_type_table, array('id' => $id));
    if (!$res) {
        // Fallback : we give the ordering.
        $res->sortorder = $id;
    };
    // Start reorder from the immediate lower (works from sortorder = 0).
    $prev = $res->sortorder - 1;

    // Getting subsequent nodes.
    $query = "
        SELECT 
            id   
        FROM 
            {customlabel_mtd_type}
        WHERE 
            sortorder > {$prev}
        ORDER BY 
            sortorder
    ";

    // reordering subsequent nodes using an object
    if ( $nextsubs = $DB->get_records_sql($query)) {
        $ordering = $res->sortorder;
        foreach ($nextsubs as $asub) {
            $objet->id = $asub->id;
            $objet->sortorder = $ordering;
            $DB->update_record($CFG->classification_type_table, $objet);
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
function classification_tree_up($id) {
    global $CFG, $DB;

    $res = $DB->get_record($CFG->classification_type_table, array('id' => $id));
    if (!$res) return;

    if ($res->sortorder >= 1) {
        $newordering = $res->sortorder - 1;

        $query = "
            SELECT 
                id
            FROM 
                {{$CFG->classification_type_table}}
            WHERE 
                sortorder = $newordering
        ";
        $result =  $DB->get_record_sql($query);
        $resid = $result->id;

        // Swapping.
        $objet->id = $resid;
        $objet->sortorder = $res->sortorder;
        $DB->update_record($CFG->classification_type_table, $objet);

        $objet->id = $id;
        $objet->sortorder = $newordering;
        $DB->update_record($CFG->classification_type_table, $objet);
    }
}

/**
 * lowers a node on its branch. this is done by swapping ordering.
 * @param id the node id
 * @param istree if not set, performs swapping on a single list
 */
function classification_tree_down($id) {
    global $CFG, $DB;

    $res =  $DB->get_record($CFG->classification_type_table, array('id' => $id));

    $query = "
        SELECT 
            MAX(sortorder) AS sortorder
        FROM 
            {{$CFG->classification_type_table}}
    ";

    $resmaxordering = $DB->get_record_sql($query);
    $maxordering = $resmaxordering->sortorder;

    if ($res->sortorder < $maxordering) {
        $newordering = $res->sortorder + 1;

        $query = "
            SELECT 
                id
            FROM    
                {{$CFG->classification_type_table}}
            WHERE 
                sortorder = $newordering
        ";
        $result = $DB->get_record_sql($query);
        $resid = $result->id;

        // Swapping.
        $objet->id = $resid;
        $objet->sortorder = $res->sortorder;
        $DB->update_record($CFG->classification_type_table, $objet);

        $objet->id = $id;
        $objet->sortorder = $newordering;
        $DB->update_record($CFG->classification_type_table, $objet);
    }
}

