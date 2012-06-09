<?php

/************************************* Add ******************/
if ($action == 'add'){
    $data = $mform->get_data();
    $metadatavalue->typeid = clean_param($data->typeid, PARAM_INT);
    $metadatavalue->code = addslashes(clean_param($data->code, PARAM_ALPHANUM));
    $metadatavalue->value = addslashes(clean_param($data->value, PARAM_CLEANHTML));
    
    // get max ordering
    $maxordering = get_field('customlabel_mtd_value', ' MAX(ordering)', 'typeid', $data->typeid);
    $metadatavalue->ordering = 1 + @$maxordering;
    
    if (!insert_record('customlabel_mtd_value', $metadatavalue)){
        error('Could not insert a new value');
    }
    redirect($url."?view=qualifiers&typeid={$data->typeid}");
}

/************************************* Update ******************/
if ($action == 'update'){
    $data = $mform->get_data();
    $metadatatype->id = clean_param('valueid', PARAM_INT);
    $metadatavalue->code = addslashes(clean_param($data->code, PARAM_ALPHANUM));
    $metadatatype->value = addslashes(clean_param('value', PARAM_CLEANHTML));
    
    if (!update_record('customlabel_mtd_type', $metadatatype)){
        error('Could not update a new value');
    }
    redirect($url."?view=qualifiers&typeid={$data->typeid}");
}

/*********************************** get a value for editing ************************/
if ($action == 'edit'){
    $valueid = required_param('valueid', PARAM_INT);
    $data = get_record('customlabel_mtd_value', 'id', $valueid);
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
    
    $value = get_record('customlabel_mtd_value', 'id', $id);

    if ($value){
        // delete related constraints
        delete_records('customlabel_mtd_constraint', 'value1', $id);        
        delete_records('customlabel_mtd_constraint', 'value2', $id);        

        delete_records('customlabel_mtd_value', 'id', $id);        
    }
}

/**
* updates ordering of a tree branch from a specific node, reordering 
* all subsequent siblings. 
* @param id the node from where to reorder
* @param table the table-tree
*/
function classification_tree_updateordering($id, $type){

	// getting ordering value of the current node
	global $CFG;

	$res =  get_record('customlabel_mtd_value', 'id', $id);
	if (!$res) { // fallback : we give the ordering
	    $res->ordering = $id;
	};
	
	// start reorder from the immediate lower (works from ordering = 0)
	$prev = $res->ordering - 1;

	// getting subsequent nodes
	$query = "
	    SELECT 
	        id   
	    FROM 
	        {$CFG->prefix}customlabel_mtd_value
	    WHERE 
	        ordering > {$prev} AND
	        typeid = $type
	    ORDER BY 
	        ordering
	";

	// reordering subsequent nodes using an object
	if( $nextsubs = get_records_sql($query)) {
	    $ordering = $res->ordering;
		foreach($nextsubs as $asub){
			$objet->id = $asub->id;
			$objet->ordering = $ordering;
			update_record('customlabel_mtd_value', $objet);
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

	$res = get_record('customlabel_mtd_value', 'id', $id);
	if (!$res) return;

	if($res->ordering >= 1){
		$newordering = $res->ordering - 1;

		$query = "
		    SELECT 
		        id
		    FROM 
		        {$CFG->prefix}customlabel_mtd_value
		    WHERE 
		        ordering = $newordering AND
		        typeid = $type
		";
		
		// echo $query;
		
		$result =  get_record_sql($query);
		$resid = $result->id;

        // swapping
		$objet->id = $resid;
		$objet->ordering = $res->ordering;
		update_record('customlabel_mtd_value', $objet);

		$objet->id = $id;
		$objet->ordering = $newordering;
		update_record('customlabel_mtd_value', $objet);
	}
}

/**
* lowers a node on its branch. this is done by swapping ordering.
* @param id the node id
* @param istree if not set, performs swapping on a single list
*/
function classification_tree_down($id, $type){
	global $CFG;

	$res =  get_record('customlabel_mtd_value', 'id', $id);

	$query = "
	    SELECT 
	        MAX(ordering) AS ordering
	    FROM 
	        {$CFG->prefix}customlabel_mtd_value
	    WHERE
	        typeid = $type
	";
	
	// echo $query;

	$resmaxordering = get_record_sql($query);
	$maxordering = $resmaxordering->ordering;

	if($res->ordering < $maxordering){
		$newordering = $res->ordering + 1;

		$query = "
		    SELECT 
		        id
		    FROM    
		        {$CFG->prefix}customlabel_mtd_value
		    WHERE 
		        ordering = $newordering AND
		        typeid = $type
		";
		$result = get_record_sql($query);
		$resid = $result->id;

        // swapping
		$objet->id = $resid;
		$objet->ordering = $res->ordering;
		update_record('customlabel_mtd_value', $objet);

		$objet->id = $id;
		$objet->ordering = $newordering;
		update_record('customlabel_mtd_value', $objet);
	}
}

?>