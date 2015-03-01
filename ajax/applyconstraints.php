<?php

require('../../../config.php');
require_once($CFG->dirroot.'/mod/customlabel/lib.php');

$type = required_param('type', PARAM_ALPHA);
$selector = required_param('selector', PARAM_TEXT);
$constraints = required_param('constraints', PARAM_RAW);
$targetstr = required_param('targets', PARAM_TEXT);
$selection = optional_param('selection', null, PARAM_TEXT);
$constraintsarr = explode(',', $constraints);

// debug_trace("type: $type\n$selector: $selector\nconstraints: $constraints\ntargets: $targetstr\nselection $selection");

// rebuild proper associative structure from flatten array
if (!empty($selection)) {
    $selected = json_decode(stripslashes($selection));
    $iskey = true;
    foreach ($selected as $sel) {
        if ($iskey) {
            $tmp = $sel;
            $iskey = false;
        } else {
            $preselection[$tmp] = $sel;
            if (is_array($sel))
                $constraintsarr = $constraintsarr + $sel;
            $iskey = true;
        }
    }
}
if (!$targets = explode(',', $targetstr)) {
    exit;
}

// we just need the definition
$customlabel = new StdClass;
$customlabel->labelclass = $type;
$customlabel->title = '';
$instance = customlabel_load_class($customlabel, true);

// make a structure with options and reduce possible options to
// acceptable ones
// this is another writing for "get_all_classification_linked_values".
$included = array();
$used = array();
$includedtrace = array();
if (!empty($constraints)) {
    while ($constraint = array_pop($constraintsarr)) {
        if (empty($constraint)) continue;
        $used[] = $constraint;
        if ($constraintpeerrecs = $DB->get_records_select($CFG->classification_constraint_table, " value1 = '$constraint' OR value2 = '$constraint' ")) {
            foreach ($constraintpeerrecs as $apeer) {
                if ($apeer->value1 == $constraint) {
                    $peervalue = $apeer->value2;
                } else {
                    $peervalue = $apeer->value1;
                }
                if ($apeer->const == 1) {
                    $included[$peervalue] = 1;
                    $peer = $DB->get_field($CFG->classification_value_table, 'value', array('id' => $peervalue));
                    $includedtrace["$peervalue - $peer"] = 1;
                    if (!in_array($peervalue, $used)) $constraintsarr[] = $peervalue; // aggregate for recursion accepting all newly linked item
                }
            }
        }
    }
}

$listvalues = array();

foreach ($targets as $target) {

    // this filters option lists againt constraints
    if ($typevalues = $instance->get_datasource_options($instance->fields[$target])) {    
        $options = array();
        foreach ($typevalues as $id => $value) {
            // we must check if not fully excluded
            if (!empty($constraints)) {
                if (isset($included[$id])) {
                    $options[$id] = $value;
                }
            } else {
                $options[$id] = $value;
            }
        }
        $field = $instance->fields[$target];

        $script = '';
        if (!empty($field->constraintson)) {
            $script = " applyconstraints('{$CFG->wwwroot}', '{$instance->type}', this, '{$field->constraintson}') ";
        }

        if (empty($field->multiple)) {
            $return[$target] = html_writer::select($options, $field->name, @$preselection[$target], array(), array('onchange' => $script, 'id' => 'id_'.$field->name));
        } else {
            // $values = explode(', ', $value);
            $return[$target] = html_writer::select($options, "{$field->name}[]", @$preselection[$target], array(), array('onchange' => $script, 'multiple' => 'multiple', 'size' => '6', 'id' => 'id_'.$field->name));
        }
    }
}

echo json_encode($return);

