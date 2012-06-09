<?php //$Id: restorelib.php,v 1.3 2011-07-07 14:01:22 vf Exp $
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

    //This function executes all the restore procedure about this mod
    function customlabel_restore_mods($mod, $restore) {

        global $CFG;

        $status = true;

        //Get record from backup_ids
        $data = backup_getid($restore->backup_unique_code, $mod->modtype, $mod->id);

        if ($data) {
            //Now get completed xmlized object
            $info = $data->info;
            //traverse_xmlize($info);                                                                     //Debug
            //print_object ($GLOBALS['traverse_array']);                                                  //Debug
            //$GLOBALS['traverse_array']="";                                                              //Debug
          
            //Now, build the LABEL record structure
            $customlabel->course = $restore->course_id;
            $customlabel->name = backup_todb($info['MOD']['#']['NAME']['0']['#']);
            $customlabel->title = backup_todb($info['MOD']['#']['TITLE']['0']['#']);
            $customlabel->labelclass = backup_todb($info['MOD']['#']['LABELCLASS']['0']['#']);
            $customlabel->content = backup_todb($info['MOD']['#']['CONTENT']['0']['#']);
            
            // deals with old unsecured format
            if (isset($info['MOD']['#']['SAFECONTENT']['0']['#'])){
	            $customlabel->safecontent = backup_todb($info['MOD']['#']['SAFECONTENT']['0']['#']);
	        } else {
	        	$customlabel->safecontent = base64_encode($customlabel->content);
	        }
        	$customlabel->usesafe = 1;
	        
            $customlabel->timemodified = $info['MOD']['#']['TIMEMODIFIED']['0']['#'];

            if (empty($customlabel->title)){
                $customlabel->title = '';
            } 
            //The structure is equal to the db, so insert the customlabel
            $newid = insert_record ('customlabel', $customlabel);

            //Do some output     
            if (!defined('RESTORE_SILENTLY')) {
                echo "<li>".get_string('modulename', 'customlabel').' "'.format_string(stripslashes($customlabel->name),true)."\"</li>";
            }
            backup_flush(300);

            if ($newid) {
                //We have the newid, update backup_ids
                backup_putid($restore->backup_unique_code,$mod->modtype,
                             $mod->id, $newid);
   
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }
        
        customlabel_decode_content_links_caller($restore);

        return $status;
    }

    /**
    *
    *
    */
    function customlabel_decode_content_links_caller($restore) {
        global $CFG;
        $status = true;
        
        $sql = "
            SELECT 
                l.id, 
                l.safecontent,
                l.name
            FROM 
                {$CFG->prefix}customlabel l
            WHERE 
                l.course = $restore->course_id
        ";
        if ($customlabels = get_records_sql ($sql)) {
            $i = 0;   //Counter to send some output to the browser to avoid timeouts
            foreach ($customlabels as $customlabel) {
                //Increment counter
                $i++;
				$contentarr = (array)json_decode(base64_decode($customlabel->safecontent)); // pass component content to array so we can iterate
				$contenthaschanged = false;
				foreach($contentarr as $key => $contentstring){
					$contentarr[$key] = restore_decode_content_links_worker($contentstring, $restore);
					if ($contentstring != $contentarr[$key]){
						$contenthaschanged = true;
					}
				}
                $name = restore_decode_content_links_worker($customlabel->name, $restore);

				if ($contenthaschanged){
					$contentobj = (object)$contentarr;
					$result = json_encode($contentobj);
                    $customlabel->safecontent = base64_encode($result);
				}
				if ($name != $customlabel->name){
                    $customlabel->name = addslashes($name);
				}

                if ($contenthaschanged || $name != $customlabel->name) {
                    //Update record
                    $status = update_record('customlabel', $customlabel);
                }
                //Do some output
                if (($i+1) % 5 == 0) {
                    if (!defined('RESTORE_SILENTLY')) {
                        echo ".";
                        if (($i+1) % 100 == 0) {
                            echo "<br />";
                        }
                    }
                    backup_flush(300);
                }
            }
        }
        return $status;
    }

    //Return a content decoded to support interactivities linking. Every module
    //should have its own. They are called automatically from
    //customlabel_decode_content_links_caller() function in each module
    //in the restore process
    function customlabel_decode_content_links ($content, $restore) {            
        global $CFG;
            
        mb_internal_encoding("UTF-8");
        $result = mb_ereg_replace('\\$@CUSTOMLABEL@\\$', $CFG->wwwroot.'/mod/customlabel', $content);

        if ($CFG->debug > 1024 && !empty($CFG->debugdecodelinks)) {
            if (!defined('RESTORE_SILENTLY')) {
                echo '<br /><hr />'.s($content).'<br />changed to<br />'.s($result).'<hr /><br />';
            }
        }

        return $result;
    }

    //This function returns a log record with all the necessay transformations
    //done. It's used by restore_log_module() to restore modules log.
    function customlabel_restore_logs($restore,$log) {
                    
        $status = false;
                    
        //Depending of the action, we recode different things
        switch ($log->action) {
        case 'add':
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code, $log->module, $log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case 'update':
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        default:
            if (!defined('RESTORE_SILENTLY')) {
                echo "action (".$log->module."-".$log->action.") unknown. Not restored<br />";                 //Debug
            }
            break;
        }

        if ($status) {
            $status = $log;
        }
        return $status;
    }
?>
