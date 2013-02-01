<?php //$Id: backuplib.php,v 1.3 2012-12-28 22:53:37 vf Exp $

    /**
    * This php script contains all the stuff to backup/restore
    * customlabel mods
    *
    * @package    mod-customlabel
    * @subpackage backup/restore
    * @category   mod
    * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
    * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
    * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
    *
    * 
    * This is the "graphical" structure of the customlabel mod:
    * 
    *                        customlabel
    *                      (CL,pk->id)
    * 
    *  Meaning: pk->primary key field of the table
    *           fk->foreign key to link with parent
    *           nt->nested field (recursive data)
    *           CL->course level info
    *           UL->user level info
    *           files->table may have files)
    * 
    * -----------------------------------------------------------
    */

    //This function executes all the backup procedure about this mod
    function customlabel_backup_mods($bf,$preferences) {
        global $CFG;

        $status = true; 

        ////Iterate over customlabel table
        if ($customlabels = get_records ('customlabel', 'course', $preferences->backup_course, 'id')) {
            foreach ($customlabels as $customlabel) {
                if (backup_mod_selected($preferences,'customlabel', $customlabel->id)) {
                    $status = customlabel_backup_one_mod($bf, $preferences, $customlabel);
                }
            }
        }
        return $status;
    }
   
    function customlabel_backup_one_mod($bf, $preferences, $customlabel) {
		static $mtd_backup = false;
        global $CFG;
    
        if (is_numeric($customlabel)) {
            $customlabel = get_record('customlabel', 'id', $customlabel);
        }
    
        $status = true;

        //Backup once mtd definitions

		if (!$mtd_backup){
        	$status = $status && backup_customlabel_mtds($bf, $preferences, $customlabel->course);
			
			$mtd_backup = true;
		}

        //Start mod
        fwrite ($bf,start_tag('MOD',3,true));
        //Print customlabel data
        fwrite ($bf,full_tag('ID', 4, false, $customlabel->id));
        fwrite ($bf,full_tag('MODTYPE', 4, false, 'customlabel'));
        fwrite ($bf,full_tag('COURSE', 4, false, $customlabel->course));
        fwrite ($bf,full_tag('NAME', 4, false, $customlabel->name));
        fwrite ($bf,full_tag('TITLE', 4, false, $customlabel->title));
        fwrite ($bf,full_tag('LABELCLASS', 4, false, $customlabel->labelclass));
        // hot convert for storage
        if (!empty($customlabel->content)){
        	$customlabel->safecontent = base64_encode($customlabel->content);
        	$customlabel->usesafe = 1;
        }
        fwrite ($bf,full_tag('FALLBACKTYPE', 4, false, $customlabel->fallbacktype));
        fwrite ($bf,full_tag('CONTENT', 4, false, $customlabel->content));
        fwrite ($bf,full_tag('SAFECONTENT', 4, false, $customlabel->safecontent));
        fwrite ($bf,full_tag('USESAFE', 4, false, $customlabel->usesafe));
        fwrite ($bf,full_tag('TIMEMODIFIED', 4, false, $customlabel->timemodified));
        //End mod
        $status = fwrite ($bf,end_tag('MOD', 3, true));

        return $status;
    }

    //Backup types used for customlabel metadatas (executed from tracker_backup_mods)
    function backup_customlabel_mtds($bf, $preferences, $courseid) {
        global $CFG;
        
        $status = true;

        fwrite ($bf, start_tag('CUSTOMLABELMTDS', 3, true));
        
        $mtdtypes = get_records('customlabel_mtd_type');
        if ($mtdtypes) {
            //Write start tag
            fwrite ($bf, start_tag('MTDTYPES', 4, true));
            //Iterate over each type
            foreach ($mtdtypes as $type) {
                //Start type
                fwrite ($bf, start_tag('TYPE', 5, true));
                //Print type data
                fwrite ($bf, full_tag('ID', 6, false, $type->id));
                fwrite ($bf, full_tag('TYPE', 6, false, $type->type)); 
                fwrite ($bf, full_tag('NAME', 6, false, $type->name)); 
                fwrite ($bf, full_tag('DESCRIPTION', 6, false, $type->description)); 
                fwrite ($bf, full_tag('SORTORDER', 6, false, $type->sortorder));
                //End elementused
                fwrite ($bf, end_tag('TYPE', 5, true));
            }
            //Write end tag
            fwrite($bf, end_tag('MTDTYPES', 4, true));
        }

        $mtdvalues = get_records('customlabel_mtd_value');
        if ($mtdvalues) {
            //Write start tag
            fwrite ($bf, start_tag('MTDVALUES', 4, true));
            //Iterate over each value
            foreach ($mtdvalues as $value) {
                //Start value
                fwrite ($bf, start_tag('VALUE', 5, true));
                //Print value data
                fwrite ($bf, full_tag('ID', 6, false, $value->id));
                fwrite ($bf, full_tag('TYPEID', 6, false, $value->typeid)); 
                fwrite ($bf, full_tag('CODE', 6, false, $value->code)); 
                fwrite ($bf, full_tag('VALUE', 6, false, $value->value)); 
                fwrite ($bf, full_tag('TRANSLATABLE', 6, false, $value->translatable)); 
                fwrite ($bf, full_tag('SORTORDER', 6, false, $value->sortorder));
                fwrite ($bf, full_tag('PARENT', 6, false, $value->parent));
                //End elementused
                fwrite ($bf, end_tag('VALUE', 5, true));
            }
            //Write end tag
            fwrite($bf, end_tag('MTDVALUES', 4, true));
        }

        $mtdconstraints = get_records('customlabel_mtd_constraint');
        if ($mtdconstraints) {
            //Write start tag
            fwrite ($bf, start_tag('MTDCONSTRAINTS', 4, true));
            //Iterate over each value
            foreach ($mtdconstraints as $constraint) {
                //Start value
                fwrite ($bf, start_tag('CONSTRAINT', 5, true));
                //Print value data
                fwrite ($bf, full_tag('ID', 6, false, $constraint->id));
                fwrite ($bf, full_tag('VALUEONE', 6, false, $constraint->value1)); 
                fwrite ($bf, full_tag('VALUETWO', 6, false, $constraint->value2)); 
                fwrite ($bf, full_tag('CONST', 6, false, $constraint->const)); 
                //End elementused
                fwrite ($bf, end_tag('CONSTRAINT', 5, true));
            }
            //Write end tag
            fwrite($bf, end_tag('MTDCONSTRAINTS', 4, true));
        }

		// current course metadata bindings for the current course only
        $coursemtds = get_records('customlabel_course_metadata', 'courseid', $courseid);
        if ($coursemtds) {
            //Write start tag
            fwrite ($bf, start_tag('COURSEMTDS', 4, true));
            //Iterate over each value
            foreach ($coursemtds as $mtd) {
                //Start value
                fwrite ($bf, start_tag('MTD', 5, true));
                //Print value data
                fwrite ($bf, full_tag('ID', 6, false, $mtd->id));
                fwrite ($bf, full_tag('COURSEID', 6, false, $mtd->courseid)); 
                fwrite ($bf, full_tag('VALUEID', 6, false, $mtd->valueid)); 
                //End elementused
                fwrite ($bf, end_tag('MTD', 5, true));
            }
            //Write end tag
            fwrite($bf, end_tag('COURSEMTDS', 4, true));
        }

        fwrite($bf, end_tag('CUSTOMLABELMTDS', 3, true));

        return $status;
    }


    ////Return an array of info (name,value)
    function customlabel_check_backup_mods($course, $user_data = false, $backup_unique_code, $instances = null) {
        if (!empty($instances) && is_array($instances) && count($instances)) {
            $info = array();
            foreach ($instances as $id => $instance) {
                $info += customlabel_check_backup_mods_instances($instance, $backup_unique_code);
            }
            return $info;
        }
        
         //First the course data
         $info[0][0] = get_string('modulenameplural','customlabel');
         $info[0][1] = count_records('customlabel', 'course', "$course");
         return $info;
    } 

    ////Return an array of info (name,value)
    function customlabel_check_backup_mods_instances($instance,$backup_unique_code) {
         //First the course data
        $info[$instance->id.'0'][0] = '<b>'.$instance->name.'</b>';
        $info[$instance->id.'0'][1] = '';
        return $info;
    }

    //Return a content encoded to support interactivities linking. Every module
    //should have its own. They are called automatically from the backup procedure.
    function customlabel_encode_content_links ($content, $preferences) {

        global $CFG;

        $base = preg_quote($CFG->wwwroot,"/");

        //Links to everything in customlabel
        $searchpattern = "/(".$base."\/mod\/customlabel\/)/";
        $result = preg_replace($searchpattern, '$@CUSTOMLABEL@$/', $content);

        return $result;
    }

?>
