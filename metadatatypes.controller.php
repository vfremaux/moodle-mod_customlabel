<?php

/************************************* Add ******************/
if ($action == 'add'){
    $data = $mform->get_data();
    $metadatatype->type = clean_param($data->type, PARAM_TEXT);
    $metadatatype->code = clean_param($data->code, PARAM_ALPHANUM);
    $metadatatype->name = addslashes(clean_param($data->name, PARAM_CLEANHTML));
    $metadatatype->description = addslashes(clean_param($data->description, PARAM_CLEANHTML));
    $maxordering = get_field($CFG->classification_type_table, ' MAX(sortorder) ', '', '');
    $metadatatype->sortorder = $maxordering + 1;
    if (!insert_record($CFG->classification_type_table, $metadatatype)){
        error('Could not insert a new type');
    }
    redirect($url.'?view=classifiers');
}

/************************************* Update ******************/
if ($action == 'update'){
    $data = $mform->get_data();
    $metadatatype->id = clean_param($data->id, PARAM_INT);
    $metadatatype->type = clean_param($data->type, PARAM_TEXT);
    $metadatatype->code = clean_param($data->code, PARAM_ALPHANUM);
    $metadatatype->name = addslashes(clean_param($data->name, PARAM_TEXT));
    $metadatatype->description = addslashes(clean_param($data->description, PARAM_TEXT));
    
    if (!update_record($CFG->classification_type_table, $metadatatype)){
        error('Could not insert a new type');
    }
    redirect($url.'?view=classifiers');
}

/*********************************** get a type for editing ************************/
if ($action == 'edit'){
    $typeid = required_param('typeid', PARAM_INT);
    $data = get_record($CFG->classification_type_table, 'id', $typeid);    
}
/*********************************** moves up ************************/
if ($action == 'up'){
    $id = required_param('typeid', PARAM_INT);
    classification_tree_up($id);
}
/*********************************** moves down ************************/
if ($action == 'down'){
    $id = required_param('typeid', PARAM_INT);
    classification_tree_down($id);
}

/************************************* Delete safe (only if not used) ******************/
if ($action == 'delete'){
    $id = required_param('typeid', PARAM_INT);
    $typeorder = get_field($CFG->classification_type_table, 'sortorder', 'id', $id);
        
    if (!delete_records($CFG->classification_type_table, 'id', $id)){
        print_error('could not delete classifier');
    }
    
    // clear all sub values
    if ($valueids = get_records_menu($CFG->classification_type_table, 'type', $id, 'id,id')){

	    delete_records($CFG->classification_value_table, $CFG->classification_value_type_key, $id);
	
	    // clear constraint records
	    $valueidslist = implode("','", array_keys($valueids));
	    delete_records_select($CFG->classification_constraint_table, "value1 IN ('$valueidslist') ");
	    delete_records_select($CFG->classification_constraint_table, "value2 IN ('$valueidslist') ");
	
	    // clear course assignations
	    delete_records_select($CFG->course_metadata_table, $CFG->course_metadata_value_key." IN ('$valueidslist') " );
	}

    classification_tree_updateordering($typeorder);
}

/**
* updates ordering of a tree branch from a specific node, reordering 
* all subsequent siblings. 
* @param id the node from where to reorder
* @param table the table-tree
*/
function classification_tree_updateordering($id){

	// getting ordering type of the current node
	global $CFG;

	$res =  get_record($CFG->classification_type_table, 'id', $id);
	if (!$res) { // fallback : we give the ordering
	    $res->sortorder = $id;
	};
	
	// start reorder from the immediate lower (works from sortorder = 0)
	$prev = $res->sortorder - 1;

	// getting subsequent nodes
	$query = "
	    SELECT 
	        id   
	    FROM 
	        {$CFG->prefix}{$CFG->classification_type_table}
	    WHERE 
	        sortorder > {$prev}
	    ORDER BY 
	        sortorder
	";

	// reordering subsequent nodes using an object
	if( $nextsubs = get_records_sql($query)) {
	    $ordering = $res->sortorder;
		foreach($nextsubs as $asub){
			$objet->id = $asub->id;
			$objet->sortorder = $ordering;
			update_record($CFG->classification_type_table, $objet);
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
function classification_tree_up($id){
	global $CFG;

	$res = get_record($CFG->classification_type_table, 'id', $id);
	if (!$res) return;

	if($res->sortorder >= 1){
		$newordering = $res->sortorder - 1;

		$query = "
		    SELECT 
		        id
		    FROM 
		        {$CFG->prefix}{$CFG->classification_type_table}
		    WHERE 
		        sortorder = $newordering
		";
		
		// echo $query;
		
		$result =  get_record_sql($query);
		$resid = $result->id;

        // swapping
		$objet->id = $resid;
		$objet->sortorder = $res->sortorder;
		update_record($CFG->classification_type_table, $objet);

		$objet->id = $id;
		$objet->sortorder = $newordering;
		update_record($CFG->classification_type_table, $objet);
	}
}

/**
* lowers a node on its branch. this is done by swapping ordering.
* @param id the node id
* @param istree if not set, performs swapping on a single list
*/
function classification_tree_down($id){
	global $CFG;

	$res =  get_record($CFG->classification_type_table, 'id', $id);

	$query = "
	    SELECT 
	        MAX(sortorder) AS sortorder
	    FROM 
	        {$CFG->prefix}{$CFG->classification_type_table}
	";
	
	// echo $query;

	$resmaxordering = get_record_sql($query);
	$maxordering = $resmaxordering->sortorder;

	if($res->sortorder < $maxordering){
		$newordering = $res->sortorder + 1;

		$query = "
		    SELECT 
		        id
		    FROM    
		        {$CFG->prefix}{$CFG->classification_type_table}
		    WHERE 
		        sortorder = $newordering
		";
		$result = get_record_sql($query);
		$resid = $result->id;

        // swapping
		$objet->id = $resid;
		$objet->sortorder = $res->sortorder;
		update_record($CFG->classification_type_table, $objet);

		$objet->id = $id;
		$objet->sortorder = $newordering;
		update_record($CFG->classification_type_table, $objet);
	}
}

?>