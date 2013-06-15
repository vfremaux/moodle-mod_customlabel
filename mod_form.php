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
 * Add customlabel form
 *
 * @package    mod
 * @subpackage customlabel
 * @copyright  2012 Valery Fremaux 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once ($CFG->dirroot.'/mod/customlabel/locallib.php');
$PAGE->requires->yui2_lib('yui_yahoo');
$PAGE->requires->yui2_lib('yui_dom');
$PAGE->requires->yui2_lib('yui_utilities');
$PAGE->requires->yui2_lib('yui_connection');
$PAGE->requires->yui2_lib('yui_json');
$PAGE->requires->js('/mod/customlabel/js/applyconstraints.js', false); // needs being in footer to get oldtype
$PAGE->requires->js('/mod/customlabel/js/modform.js', false); // needs being in footer to get oldtype

class mod_customlabel_mod_form extends moodleform_mod {

	var $editoroptions;

    function definition() {
    	global $COURSE, $DB, $CFG;

    	$context = get_context_instance(CONTEXT_COURSE, $COURSE->id);

		/*
	    // mounts dynamically the label's form
	    if ($form->labelclass == 'text'){
	        $content = @$customlabel->textcontent;
	    }
	    */
	
	    // get classes for custom label        
	    $labelclasses = customlabel_get_classes($context);

		$qoptions = array();	    
	    foreach($labelclasses as $labelclass){
	        $qoptions[$labelclass->id] = $labelclass->name;
	    }
	    asort($qoptions);

        $mform = $this->_form;

		$tomodel = '';
		$customlabel = new StdClass;
		if ($tomodel = optional_param('type', '', PARAM_TEXT)){
			$customlabel->labelclass = $tomodel;
		}
		
		$customlabel->processedcontent = '';
		if (!$tomodel){
			if ($this->current->instance){ // are we updating an existing instance ? 
				$customlabel = $DB->get_record('customlabel', array('id' => $this->current->id));
			} else {
				$customlabel->title = '';
				$customlabel->labelclass = 'text' ;
			}
		}
	    $customclass = customlabel_load_class($customlabel);

	    $section     = optional_param('section', 0, PARAM_INT);
	    $returntomod = optional_param('return', 0, PARAM_BOOL);
        if (has_capability('mod/customlabel:fullaccess', $context) || $customclass->fullaccess){
        	$onchangeadvicestr = str_replace("'", "\'", get_string('changetypeadvice', 'customlabel'));
			// $mform->addElement('select', 'labelclass', get_string('labelclass', 'customlabel'), $qoptions, array('onchange' => "type_change_submit(this,'$onchangeadvicestr')", 'id' => 'menulabelclass'));
			$mform->addElement('select', 'labelclass', get_string('labelclass', 'customlabel'), $qoptions, array('onchange' => "type_change_submit('$onchangeadvicestr', '$COURSE->id', '$section', '$returntomod', '".sesskey()."')", 'id' => 'menulabelclass'));
			$mform->setDefault('labelclass', 'text');
		} else {
			$mform->addElement('static', 'labelclassname', get_string('labelclass', 'customlabel'));
			$mform->addElement('hidden', 'labelclass');
		}

		$mform->addElement('text', 'title', get_string('title', 'customlabel'));
		$customlabel_next_id = $DB->get_field('customlabel', 'MAX(id)', array()) + 1; 
		$mform->setDefault('title', $customlabel->labelclass.'_'.$customlabel_next_id);
        
    	if ($customlabel->labelclass == 'text'){    		    		
    		$mform->addElement('htmleditor', 'textcontent_editor', get_string('content', 'customlabel'));
    	} else {
	        if (!$customclass){
	            print_error("Custom label class lacks of definition");
	        }
        	foreach($customclass->fields as $field){
            	if (!has_capability('mod/customlabel:fullaccess', $context) && !empty($field->admin)) continue ; // no capable users cannot edit lock fields

            	$fieldname = str_replace('[]', '', $field->name); // must take care it is a multiple field
            	$fieldlabel = get_string($field->name, 'customlabeltype_'.$customclass->type);

	            if ($field->type == 'choiceyesno') {
	            	$mform->addElement('selectyesno', $field->name, $fieldlabel);
	            } elseif ($field->type == 'textfield') {
	            	$attrs = array('size' => @$field->size, 'maxlength' => @$field->maxlength);
	            	$mform->addElement('text', $field->name, $fieldlabel, $attrs);
	            } elseif ($field->type == 'editor' || $field->type == 'textarea') {
	            	$mform->addElement('htmleditor', $field->name.'_editor', $fieldlabel, $this->editoroptions);
	            } elseif (preg_match("/list$/", $field->type)) {
	            	if (empty($field->straightoptions)){
		                $options = $customclass->get_options($fieldname);
		            } else {
		            	$options = array_combine($field->options, $field->options);
		            }
	                $select = &$mform->addElement('select', $field->name, $fieldlabel, $options);
	                if (!empty($field->multiple)){
	                	$select->setMultiple(true);
	                }
	            } elseif (preg_match("/datasource$/", $field->type)) {
	                // Very similar to lists, except options come from an external datasource
	                $options = $customclass->get_datasource_options($field);
	                
	                $script = '';
	                if (!empty($field->constraintson)){
	                	$script = " applyconstraints('{$CFG->wwwroot}', '{$customclass->type}', this, '{$field->constraintson}') ";
	                }

					$attrs = array('onchange' => $script);	                
	                $select = &$mform->addElement('select', $field->name, $fieldlabel, $options, $attrs);
	                if (!empty($field->multiple)){
	                	$select->setMultiple(true);
	                }
	            } else {
	            	echo "Unknown or unsupported type : $field->type";
	            }

	    		if (isset($field->mandatory)){
        			$mform->addRule($field->name, null, 'required', null, 'client');        
		    	}

	    		if (!empty($field->help)){
	    			$mform->addHelpButton($field->name, $field->help, 'customlabeltype_'.$customlabel->labelclass);
	    		}

	            $mform->setDefault($fieldname, @$field->default);
	        }
        }

        //-------------------------------------------------------------------------------
        $this->standard_coursemodule_elements();
        //-------------------------------------------------------------------------------
        // buttons
        $this->add_action_buttons();
    }

	function validation($data, $files = null){		
	}

	// we must prepare data, extract dynamic part from instance
	function set_data($customlabel){
		
		if (empty($customlabel->labelclass)){
			 $customlabel->labelclass = 'text';
			 $customlabel->content = '';
			 $customlabel->processedcontent = '';
			 $customlabel->intro = '';
			 $customlabel->introformat = 0;
		}

		$instance = customlabel_load_class($customlabel, $customlabel->labelclass);

		$formdata = $customlabel;

		// get dynamic part of data and add to fixed model part from customlabel record
		$formdatadyn = (array)json_decode(base64_decode($customlabel->content));
		foreach($formdatadyn as $key => $value){
			// discard all moodle core data that should be there
			if (in_array($key, array('coursemodule', 'instance', 'sesskey', 'module', 'section'))) continue;
			// ignore old Moodle 1.9 stuff
			if (in_array($key, array('safe_content', 'usesafe'))) continue;
			$formdata->{$key} = $value;
			if (is_object($formdata->{$key}) && isset($formdata->{$key}->text)){
				$formdata->{$key} = (array)$formdata->{$key};
			}
		}

		// prepare editor for intro standard field

		// prepare editors for all textarea|editor dynamic fields prepared in model
		foreach($instance->fields as $field){
			if (preg_match('/editor|textarea/', $field->type)){
				$editorname = $field->name.'_editor';
				$formdata->$editorname = @$formdata->{$field->name};
			} 
		}

		// prepare type selector value
		if ($tomodel = optional_param('type', '', PARAM_TEXT)){
			$formdata->labelclass = $tomodel;
		}

		$formdata->sesskey = sesskey();
		
		parent::set_data($formdata);
	}
}
