<?php
    /**
     * Moodle - Modular Object-Oriented Dynamic Learning Environment
     *          http://moodle.org
     * Copyright (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
     *
     * This program is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 2 of the License, or
     * (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with this program.  If not, see <http://www.gnu.org/licenses/>.
     *
     * @package    moodle
     * @subpackage local
     * @author     Penny Leach <penny@catalyst.net.nz>, Valery Fremaux <valery.fremaux@club-internet.fr>
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
     * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
     *
     * @see Acces from adminmetadata.php
     */

	if (!defined('MOODLE_INTERNAL')) die("You cannot use this script this way");

    include_once $CFG->dirroot.'/mod/customlabel/forms/EditTypeForm.php';

/// get parms

    $type = optional_param('type', 0, PARAM_INT);

/// get necessary data

    $url = $CFG->wwwroot.'/mod/customlabel/adminmetadata.php';

	if ($action == 'edit'){
	    $mform = new EditTypeForm($view, 'update', $url);
	} else {
	    $mform = new EditTypeForm($view, 'add', $url);
	}

    if (!$mform->is_cancelled()){
        if ($action){
            include_once 'metadatatypes.controller.php';
        }
    }

    $types = get_records($CFG->classification_type_table, '', '', 'sortorder');
        
    print_heading(get_string('classifierstypes', 'customlabel'));
        
/// make the type form
    
    $strname = get_string('typename', 'customlabel');
    $strcode = get_string('code', 'customlabel');
	$strcourses = get_string('courses');
	$struseas = get_string('usedas', 'customlabel');
    $strdesc = get_string('description');
    $strcommands = get_string('commands', 'customlabel');
    
    $table->head = array("<b>$strname</b>", "<b>$struseas</b>", "<b>$strcode</b>", "<b>$strdesc</b>", "<b>$strcourses</b>", "<b>$strcommands</b>");
	$table->size = array('20%', '5%', '10%', '50%', '5%', '10%');
	$table->align = array('left', 'center', 'center', 'center', 'right');
    $table->width = '95%'; 
    
    print_box_start();
    $count = count($types);
    $i = 0;
    if ($types){
        foreach($types as $atype){

		    $sql = "
		        SELECT 
		            COUNT(c.id)
		        FROM
		            {$CFG->prefix}course c,
		            {$CFG->prefix}{$CFG->course_metadata_table} ccm,
		            {$CFG->prefix}{$CFG->classification_value_table} v
		        WHERE
		            c.id = ccm.{$CFG->course_metadata_course_key} AND
		            ccm.{$CFG->course_metadata_value_key} = v.id AND
		            v.{$CFG->classification_value_type_key} = '{$atype->id}'
		        GROUP BY 
		            v.id
		    ";
		    $atype->courses = count_records_sql($sql);


            $cmds = "<a href=\"{$url}?view=classifiers&what=delete&amp;typeid={$atype->id}\"><img src=\"{$CFG->pixpath}/t/delete.gif\" alt=".get_string('delete').'"></a>';
            $cmds .= " <a href=\"{$url}?view=classifiers&amp;what=edit&amp;typeid={$atype->id}\"><img src=\"{$CFG->pixpath}/t/edit.gif\" alt=".get_string('editvalues', 'customlabel').'"></a>';
            if ($i > 0)
                $cmds .= " <a href=\"{$url}?view=classifiers&amp;typeid={$atype->id}&what=up\"><img src=\"{$CFG->pixpath}/t/up.gif\" alt=".get_string('up', 'customlabel').'"></a>';
            if ($i < $count - 1)
                $cmds .= " <a href=\"{$url}?view=classifiers&amp;typeid={$atype->id}&what=down\"><img src=\"{$CFG->pixpath}/t/down.gif\" alt=".get_string('up', 'customlabel').'"></a>';
                
            $link = "<a href=\"{$url}?view=qualifiers&typeid={$atype->id}\">{$atype->name}</a> ";
    		$coursecount = ($atype->courses) ? "<a href=\"$CFG->wwwroot/mod/customlabel/showclassified.php?typeid={$atype->id}\">{$atype->courses} <img src=\"{$CFG->pixpath}/t/hide.gif\"></a>" : 0 ;
            $table->data[] = array($link, get_string($atype->type, 'customlabel'), $atype->code, $atype->description, $coursecount, $cmds);
            $i++;
        }

        print_table($table);
    } else {
        print_string('notypes', 'customlabel');
    }
    print_box_end();

    print_box_start('addform');
    
    if (isset($data)){
    	$mform->set_data($data);
    }
    $mform->display();
    print_box_end();
    
    
?>