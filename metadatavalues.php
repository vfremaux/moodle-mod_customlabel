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
     */

     include $CFG->dirroot.'/mod/customlabel/forms/EditValueForm.php';

/// get parms

    $type = optional_param('typeid', 0, PARAM_INT);

/// get necessary data

    $url = $CFG->wwwroot.'/mod/customlabel/adminmetadata.php';

    $types = get_records_menu($CFG->classification_type_table, '', '', 'name', 'id, name');

    if (!$types) {
        notice(get_string('noclassifiers', 'customlabel'));
        print_footer();
        return;
    }

/// form and controller

	if ($valueid = optional_param('valueid', PARAM_INT)){
	    $mform = new EditValueForm($view, 'update', $type, $url);
	} else {
	    $mform = new EditValueForm($view, 'add', $type, $url);
	}

    if (!$mform->is_cancelled()){
        if ($action){
            include 'metadatavalues.controller.php';
        }
    }

/// print page

    echo get_string('editclass', 'customlabel') . ':';
    popup_form($url . '?typeid=', $types, 'classify', $type);
    
    print_heading(get_string('metadataset', 'customlabel'));
    
    
    if (!$values = get_records($CFG->classification_value_table, $CFG->classification_value_type_key, $type, 'sortorder')) {
        $values = array();
    }
    
/// make the value form
    
    $strvalues = get_string('value', 'customlabel');
    $strcode = get_string('code', 'customlabel');
    $strcourses = get_string('courses');
    $strcommands = get_string('commands', 'customlabel');
    
    $table->head = array("", "<b>$strcode</b>", "<b>$strvalues</b>", "<b>$strcourses</b>", "<b>$strcommands</b>");
    $table->width = array('10%', '50%', '10%', '30%');
    $table->align = array('center', 'left', 'center', 'right');
    $table->width = '90%';

    print_box_start();
    if (!empty($values)){
    	$i = 0;
    	$valuecount = count($values);
        foreach($values as $avalue){
	 
	         $sql = "
	            SELECT
	                COUNT(ccm.id) ".sql_as()." courses
	            FROM
	                {$CFG->prefix}{$CFG->course_metadata_table} ccm
	            JOIN
	                {$CFG->prefix}course c
	            WHERE
	                ccm.{$CFG->course_metadata_value_key} = {$avalue->id} AND
	                (ccm.{$CFG->course_metadata_course_key} = c.id OR ccm.{$CFG->course_metadata_course_key} IS NULL)
	            GROUP BY
	                ccm.{$CFG->course_metadata_value_key}
	        ";
	        $avalue->courses = count_records_sql($sql);

           $cmds = "<a href=\"{$url}?view=qualifiers&amp;what=delete&amp;valueid={$avalue->id}&typeid={$type}\"><img src=\"{$CFG->pixpath}/t/delete.gif\" alt=".get_string('delete').'"></a>';
        	$cmds .= "&nbsp;<a href=\"{$url}?typeid={$type}&amp;what=edit&amp;valueid={$avalue->id}\"><img src=\"{$CFG->pixpath}/t/edit.gif\" /></a>";
	        if ($i > 0){
	            $cmds .= "&nbsp;<a href=\"{$url}?typeid={$type}&amp;what=up&amp;valueid={$avalue->id}\"><img src=\"{$CFG->pixpath}/t/up.gif\" /></a>";
	        } else {
	            $cmds .= "&nbsp;&nbsp;&nbsp;";
	        }
	        if ($i < $valuecount - 1){
	            $cmds .= "&nbsp;<a href=\"{$url}?typeid={$type}&amp;what=down&amp;valueid={$avalue->id}\"><img src=\"{$CFG->pixpath}/t/down.gif\" /></a>";
	        } else {
	            $cmds .= "&nbsp;&nbsp;&nbsp;";
	        }
	        $coursecount = ($avalue->courses) ? "<a href=\"$CFG->wwwroot/local/admin/lpshowclassified.php?value={$avalue->id}&amp;typeid={$type}\">{$avalue->courses} <img src=\"{$CFG->pixpath}/t/hide.gif\"></a>" : 0 ;
            $selcheck = "<input type=\"checkbox\" name=\"items[]\" value=\"{$avalue->id}\" />";
            $table->data[] = array($selcheck, $avalue->code, $avalue->value, $coursecount, $cmds);
            $i++;
        }
        print_table($table);
    } else {
        print_string('novalues', 'customlabel');
    }
    print_box_end();

    if ($type){
        print_box_start('addform');
        if (isset($data)){
        	$mform->set_data($data);
        }
        $mform->display();
        print_box_end();
    }
    
?>