<?php

$valuetypekey = $CFG->classification_value_type_key;

/************************************* Add ******************/
if ($action == 'add'){
	
    $data = $mform->get_data();
    $metadatavalue->$valuetypekey = clean_param($data->typeid, PARAM_INT);
    $metadatavalue->code = addslashes(clean_param($data->code, PARAM_ALPHANUM));
    $metadatavalue->value = addslashes(clean_param($data->value, PARAM_CLEANHTML));
    
    // get max ordering
    $maxordering = get_field($CFG->classification_value_table, ' MAX(sortorder)', $valuetypekey, $data->typeid);
    $metadatavalue->sortorder = 1 + @$maxordering;
    
    if (!insert_record($CFG->classification_value_table, $metadatavalue)){
        error('Could not insert a new value', $url."?view=qualifiers&typeid={$data->typeid}");
    }
    redirect($url."?view=qualifiers&typeid={$data->typeid}");
}

/************************************* Update ******************/
if ($action == 'update'){
    $data = $mform->get_data();
    $metadatavalue->id = clean_param($data->id, PARAM_INT);
    $metadatavalue->$valuetypekey = addslashes(clean_param($data->typeid, PARAM_ALPHANUM));
    $metadatavalue->code = addslashes(clean_param($data->code, PARAM_TEXT));
    $metadatavalue->value = addslashes(clean_param($data->value, PARAM_CLEANHTML));

    if (!update_record($CFG->classification_value_table, $metadatavalue)){
        error('Could not update the value', $url."?view=qualifiers&typeid={$data->typeid}");
    }
    redirect($url."?view=qualifiers&typeid={$data->typeid}");
}

/*********************************** get a value for editing ************************/
if ($action == 'edit'){
    $valueid = required_param('valueid', PARAM_INT);
    $data = get_record($CFG->classification_value_table, 'id', $valueid);
}
/*********************************** moves up ************************/
if ($action == 'up'){
    $id = required_param('valueid', PARAM_INT);
    classification_tree_up($id, $type);
    classification_tree_updateordering(0, $type);
}
/*********************************** moves down ************************/
if ($action == 'down'){
    $id = required_param('valueid', PARAM_INT);
    classification_tree_down($id, $type);
    classification_tree_updateordering(0, $type);
}

/************************************* Delete safe (only if not used) ******************/
if ($action == 'delete'){
    // todo check if there is no instances working with such type.
    // if not, bounce to forcedelete
    $action = 'forcedelete';
}

/************************************* Delete unsafe ******************/
if ($action == 'forcedelete'){
    $id = required_param('valueid', PARAM_INT);
    
    $value = get_record($CFG->classification_value_table, 'id', $id);

    if ($value){
        // delete related constraints
        delete_records($CFG->classification_constraint_table, 'value1', $id);        
        delete_records($CFG->classification_constraint_table, 'value2', $id);        

        delete_records($CFG->classification_value_table, 'id', $id);        
    }
}

/**
* updates ordering of a tree branch from a specific node, reordering 
* all subsequent siblings. 
* @param id the node from where to reorder
* @param table the table-tree
*/
function classification_tree_updateordering($id, $type){

	// getting sortorder value of the current node
	global $CFG;

	$res =  get_record($CFG->classification_value_table, 'id', $id);
	if (!$res) { // fallback : we give the sortorder
	    $res->sortorder = $id;
	};
	
	// start reorder from the immediate lower (works from sortorder = 0)
	$prev = $res->sortorder - 1;

	// getting subsequent nodes
	$query = "
	    SELECT 
	        id   
	    FROM 
	        {$CFG->prefix}{$CFG->classification_value_table}
	    WHERE 
	        sortorder > {$prev} AND
	        {$CFG->classification_value_type_key} = $type
	    ORDER BY 
	        sortorder
	";

	// reordering subsequent nodes using an object
	if( $nextsubs = get_records_sql($query)) {
	    $ordering = $res->sortorder;
		foreach($nextsubs as $asub){
			$objet->id = $asub->id;
			$objet->sortorder = $ordering;
			update_record($CFG->classification_value_table, $objet);
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
function classification_tree_up($id, $type){
	global $CFG;

	$res = get_record($CFG->classification_value_table, 'id', $id);
	if (!$res) return;

	if($res->sortorder >= 1){
		$newordering = $res->sortorder - 1;

		$query = "
		    SELECT 
		        id
		    FROM 
		        {$CFG->prefix}{$CFG->classification_value_table}
		    WHERE 
		        sortorder = $newordering AND
		        {$CFG->classification_value_type_key} = $type
		";
		
		// echo $query;
		
		$result =  get_record_sql($query);
		$resid = $result->id;

        // swapping
		$objet->id = $resid;
		$objet->sortorder = $res->sortorder;
		update_record($CFG->classification_value_table, $objet);

		$objet->id = $id;
		$objet->sortorder = $newordering;
		update_record($CFG->classification_value_table, $objet);
	}
}

/**
* lowers a node on its branch. this is done by swapping ordering.
* @param id the node id
* @param istree if not set, performs swapping on a single list
*/
function classification_tree_down($id, $type){
	global $CFG;

	$res =  get_record($CFG->classification_value_table, 'id', $id);

	$query = "
	    SELECT 
	        MAX(sortorder) AS sortorder
	    FROM 
	        {$CFG->prefix}{$CFG->classification_value_table}
	    WHERE
	        {$CFG->classification_value_type_key} = $type
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
		        {$CFG->prefix}{$CFG->classification_value_table}
		    WHERE 
		        sortorder = $newordering AND
		        {$CFG->classification_value_type_key} = $type
		";
		$result = get_record_sql($query);
		$resid = $result->id;

        // swapping
		$objet->id = $resid;
		$objet->sortorder = $res->sortorder;
		update_record($CFG->classification_value_table, $objet);

		$objet->id = $id;
		$objet->sortorder = $newordering;
		update_record($CFG->classification_value_table, $objet);
	}
}

?>