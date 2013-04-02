<?php  // $Id: admin_updateall.php,v 1.3 2012-06-19 11:46:40 vf Exp $

    /**
    * this admin screen allows updating massively all customlabels when a change has been proceeded
    * within the templates. Can only be done in one language.
    *
    * @package    mod-customlabel
    * @category   mod
    * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
    * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
    * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
    */

    require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
    require_once($CFG->libdir . '/adminlib.php');
    require_once($CFG->dirroot . '/mod/customlabel/locallib.php');
    require_once($CFG->dirroot . '/mod/customlabel/admin_updateall_form.php');
    // admin_externalpage_setup('customlabel_updateall');
    // admin_externalpage_print_header();

	$systemcontext = context_system::instance();

	require_login();
	require_capability('moodle/site:config', $systemcontext);
    
    $url = $CFG->wwwroot.'/mod/customlabel/admin_updateall.php';
    
    $PAGE->set_context($systemcontext);
    $PAGE->set_url($url);
    $PAGE->set_title(get_string('updatelabels', 'customlabel', get_string('pluginname', 'customlabel')));
    $PAGE->set_heading(get_string('updatelabels', 'customlabel', get_string('pluginname', 'customlabel')));
    /* SCANMSG: may be additional work required for $navigation variable */
    $PAGE->set_focuscontrol('');
    $PAGE->set_cacheable(true);

    /// get courses
    $allcourses = $DB->get_records_menu('course', null, 'shortname', 'id,shortname');
    /// get types
    $alltypes = customlabel_get_classes();
    if ($alltypes){
        foreach($alltypes as $atype){
            $types[$atype->id] = $atype->name;
        }
    } else {
        $types = array();
    }

    /// if data submitted, proceed
    $form = new customlabel_updateall_form($allcourses, $types);
	if ($form->is_cancelled()){
		redirect($CFG->wwwroot.'/admin/settings.php?section=modsettingcustomlabel');
	}

    echo $OUTPUT->header();
    if ($data = $form->get_data()){
        echo $OUTPUT->container_start('emptyleftspace');        
        echo $OUTPUT->heading(get_string('updatelabels', 'customlabel', get_string('modulename', 'customlabel')), 1);

        $courses = clean_param($data->courses, PARAM_INT);
        $labelclasses = clean_param($data->labelclasses, PARAM_RAW);
        if (empty($courses)){
            $courses = array();
        } else {
            $courseids = implode(',', $courses);
            $courses = $DB->get_records_list('course', array('id' => $courseids));
        }

        if (empty($labelclasses)){
            $labelclasses = array();
        }

        echo "<pre>";
        foreach($courses as $id => $course){
            mtrace("processing course $id : $course->shortname");
            foreach($labelclasses as $labelclassname){
                mtrace("   processing class '$labelclassname'");
                $customlabels = $DB->get_records_select('customlabel', " course = $id AND labelclass = '$labelclassname'");
                if ($customlabels){
                    foreach($customlabels as $customlabel){
                        mtrace("\tprocessing customlabel $customlabel->id");
                        // renew the template

					    // fake unpacks object's load
                    	$data = json_decode(base64_decode($customlabel->content));
                        if (is_null($data)) $data = new StdClass;
                        if (!is_object($data)){
                            $data = new StdClass; // reset old serialized data
                        };

                        // realize a pseudo update
                        $data->content = $customlabel->content;
                        $data->labelclass = $labelclassname; // fixes broken serialized contents
                        if (!isset($data->title)) $data->title = ''; // fixes broken serialized contents
                        $instance = customlabel_load_class($data);
                        $instance->preprocess_data();
                        $instance->process_form_fields();
                        $instance->process_datasource_fields();
                        $instance->postprocess_data($course);
                        $customlabel->processedcontent = $instance->make_content(); // this realizes the template
                        $customlabel->timemodified = time();
                        $result = $DB->update_record('customlabel', $customlabel);
                        mtrace("\tfinished customlabel $customlabel->id");
                    }
                }
            }
        }
        echo "</pre>";  
        echo $OUTPUT->container_end();        
        redirect($CFG->wwwroot.'/mod/customlabel/admin_updateall.php');      
    } else {
        // print form
        echo $OUTPUT->heading(get_string('updatelabels', 'customlabel', get_string('modulename', 'customlabel')), 1);
        $form->display();
    }    
    echo $OUTPUT->footer();
?>