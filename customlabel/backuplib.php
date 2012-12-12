<?php //$Id: backuplib.php,v 1.3 2011-07-07 14:01:20 vf Exp $

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
        if ($customlabels = $DB->get_records ('customlabel', 'course', $preferences->backup_course, 'id')) {
            foreach ($customlabels as $customlabel) {
                if (backup_mod_selected($preferences,'customlabel', $customlabel->id)) {
                    $status = customlabel_backup_one_mod($bf, $preferences, $customlabel);
                }
            }
        }
        return $status;
    }
    function customlabel_backup_one_mod($bf, $preferences, $customlabel) {

        global $CFG;
        if (is_numeric($customlabel)) {
            $customlabel = $DB->get_record('customlabel', array('id' => $customlabel));
        }
        $status = true;

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
        fwrite ($bf,full_tag('CONTENT', 4, false, $customlabel->content));
        fwrite ($bf,full_tag('SAFECONTENT', 4, false, $customlabel->safecontent));
        fwrite ($bf,full_tag('USESAFE', 4, false, $customlabel->usesafe));
        fwrite ($bf,full_tag('TIMEMODIFIED', 4, false, $customlabel->timemodified));
        //End mod
        $status = fwrite ($bf,end_tag('MOD', 3, true));

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
         $info[0][1] = $DB->count_records('customlabel', array('course' => "$course"));
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
