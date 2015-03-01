<?php

if ($action == 'save') {
    $data = data_submitted();
    $value1list = implode("','", array_keys($valueset1));
    $value2list = implode("','", array_keys($valueset2));

    $DB->delete_records_select($CFG->classification_constraint_table, "value1 IN ('{$value1list}') AND value2 IN ('{$value2list}')");
    $DB->delete_records_select($CFG->classification_constraint_table, " value1 IN ('{$value2list}') AND value2 IN ('{$value1list}')");

    $constraintdata = preg_grep("/ct_\\d+_\\d+/", array_keys((array)$data));
    foreach ($constraintdata as $constraintkey) {
        preg_match("/ct_(\\d+)_(\\d+)/", $constraintkey, $matches);
        $id1 = $matches[1];
        $id2 = $matches[2];
        $key = "ct_{$id1}_{$id2}";
        if ($data->{$key} > 0) {
            $constraintrec->value1 = $id1;
            $constraintrec->value2 = $id2;
            $constraintrec->const = $data->{$key};
            try {
                $DB->insert_record($CFG->classification_constraint_table, $constraintrec);
            } catch (Exception $e) {
                echo $OUTPUT->notification("Could not insert constraint");
            }
        }
    } 
}
