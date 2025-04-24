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
 * Ajax services.
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux (www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

require('../../../config.php');
require_once($CFG->dirroot.'/mod/customlabel/lib.php');

require_login();

$config = get_config('customlabel');

$type = required_param('type', PARAM_ALPHA); // The qualifier ID that has been choosen.
$cmid = optional_param('cmid', '', PARAM_INT); // The qualifier ID that has been choosen.
$selector = required_param('selector', PARAM_TEXT); // Unused.
$constraints = required_param('constraints', PARAM_RAW); // A list of qualifiers that constrain the actual choice.
$targetstr = required_param('targets', PARAM_TEXT); // The list of qualifiers/fields that are concerned.
$selection = optional_param('selection', null, PARAM_TEXT); // The current selection.
$variant = optional_param('variant', '', PARAM_TEXT);

$constraintsarr = explode(',', $constraints);

// Rebuild proper associative structure from flatten array.
if (!empty($selection)) {
    $selected = json_decode(stripslashes($selection));
    $iskey = true;
    foreach ($selected as $sel) {
        if ($iskey) {
            $tmp = $sel;
            $iskey = false;
        } else {
            $preselection[$tmp] = $sel;
            if (is_array($sel)) {
                $constraintsarr = $constraintsarr + $sel;
            }
            $iskey = true;
        }
    }
}
if (!$targets = explode(',', $targetstr)) {
    exit;
}

// We just need the definition.
if (empty($cmid)) {
    // When first creating a course module.
    $customlabel = new StdClass;
    $customlabel->labelclass = $type;
    $customlabel->title = '';
    $instance = customlabel_load_class($customlabel, true);
    $instance->data = new StdClass;
    $instance->data->uselevels = 2;
} else {
    $cm = $DB->get_record('course_modules', ['id' => $cmid]);
    $customlabelrec = $DB->get_record('customlabel', ['id' => $cm->instance]);
    $instance = customlabel_load_class($customlabelrec);
}

/*
 * Make a structure with options and reduce possible options to
 * acceptable ones
 * this is another writing for "get_all_classification_linked_values".
 */
$included = [];
$used = [];
$includedtrace = [];
$usedtypes = [];

if (!empty($constraints)) {
    while ($constraint = array_pop($constraintsarr)) {

        $used[] = $constraint;

        // Reinforce injection security
        $configconstrainttable = clean_param($config->classification_constraint_table, PARAM_ALPHANUMEXT);
        $configclassvaluetable = clean_param($config->classification_value_table, PARAM_ALPHANUMEXT);

        // Get all the neighbours from this value.
        $sql = "
            SELECT
              c.*,
              v1.typeid as v1type,
              v2.typeid as v2type
            FROM
                {{$configconstrainttable}} c,
                {{$configclassvaluetable}} v1,
                {{$configclassvaluetable}} v2
            WHERE
                c.value1 = v1.id AND
                c.value2 = v2.id AND
                (value1 = ? OR value2 = ?)
        ";

        $params = [];
        $params[] = $constraint;
        $params[] = $constraint;

        if ($constraintpeerrecs = $DB->get_records_sql($sql, $params)) {
            foreach ($constraintpeerrecs as $apeer) {
                if ($apeer->value1 == $constraint) {
                    $peervalue = $apeer->value2;
                    $peertype = $apeer->v2type;
                } else {
                    $peervalue = $apeer->value1;
                    $peertype = $apeer->v1type;
                }
                if ($apeer->const == 1) {
                    $included[$peervalue] = 1;
                    $peer = $DB->get_field($config->classification_value_table, 'value', ['id' => $peervalue]);
                    $usedtypes[] = $DB->get_field($config->classification_value_table, 'typeid', ['id' => $peervalue]);
                    $includedtrace["$peervalue - $peer"] = 1;
                    if (!in_array($peervalue, $used)) {
                        assert(1);
                    }
                }
            }
        }
    }
}
$listvalues = [];

foreach ($targets as $target) {
    if ($target >= $instance->data->uselevels) {
        continue;
    }

    // This filters option lists against constraints.
    if ($typevalues = $instance->get_datasource_options($instance->fields['level'.$target])) {
        $options = [];
        foreach ($typevalues as $id => $value) {
            // We must check if not fully excluded.
            if (!empty($constraints)) {
                if (isset($included[$id])) {
                    $options[$id] = $value;
                }
            } else {
                $options[$id] = $value;
            }
        }
        $field = $instance->fields['level'.$target];

        $selectid = ($variant == 'menu') ? 'menu'.$field->name : 'id_'.$field->name;

        $params = [];
        if (!empty($field->constraintson)) {
            $params['data-constrained'] = 1;
            $params['data-constraints'] = $field->constraintson;
            $params['data-label-type'] = $instance->type;
            if ($cmid) {
                $params['data-cmid'] = $cmid;
            }
        }

        if (empty($field->multiple)) {
            $params['id'] = $selectid;
            $return[$target] = html_writer::select($options, $field->name, @$preselection[$target], [], $params);
        } else {
            $params['multiple'] = 'multiple';
            $params['size'] = '6';
            $params['id'] = $selectid;
            $return[$target] = html_writer::select($options, "{$field->name}[]", @$preselection[$target], [], $params);
        }
    }
}

echo json_encode($return);
