<?php

if ($action == 'save'){
    $data = data_submitted();
        
    $value1list = implode("','", array_keys($valueset1));
    $value2list = implode("','", array_keys($valueset2));

    delete_records_select('customlabel_mtd_constraint', "value1 IN ('{$value1list}')");
    delete_records_select('customlabel_mtd_constraint', "value2 IN ('{$value2list}')");
    delete_records_select('customlabel_mtd_constraint', "value1 IN ('{$value2list}')");
    delete_records_select('customlabel_mtd_constraint', "value2 IN ('{$value1list}')");

    $constraintdata = preg_grep("/ct_\\d+_\\d+/", array_keys((array)$data));
    foreach($constraintdata as $constraintkey){
        preg_match("/ct_(\\d+)_(\\d+)/", $constraintkey, $matches);
        $id1 = $matches[1];
        $id2 = $matches[2];
        $key = "ct_{$id1}_{$id2}";
        if ($data->{$key} > 0){
            $constraintrec->value1 = $id1;
            $constraintrec->value2 = $id2;
            $constraintrec->const = $data->{$key};
            if (!insert_record('customlabel_mtd_constraint', $constraintrec)){
                notify("Could not insert constraint");
            }
        }
    }
}

?>