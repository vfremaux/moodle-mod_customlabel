<?php

/**
* @package mod-customlabel
* @category mod
* @author Valery Fremaux for Pairformance/TAO
* @date 15/07/2008
*/

if (!isset($CFG->classification_type_table)){
	set_config('classification_type_table', 'customlabel_mtd_type');
	set_config('classification_value_table', 'customlabel_mtd_value');
	set_config('classification_value_type_key', 'typeid');
	set_config('classification_constraint_table', 'customlabel_mtd_constraint');
	set_config('course_metadata_table', 'customlabel_course_metadata');
	set_config('course_metadata_value_key', 'valueid');
	set_config('course_metadata_course_key', 'courseid');
}

/**
* returns all available classes for a customlabel
* @uses $CFG
* @param int $context if a context is given, filters out any type that is not allowed against 
*                     roles held by the current user. Returns all types otherwise.
* @return a sorted array of class definitions as objects
*/
function customlabel_get_classes($context = null, $ignoredisabled = true){
    global $CFG;

    static $classes = array();

	if (empty($classes)){
	    $basetypedir = $CFG->dirroot."/mod/customlabel/type";
	    
	    $disabled = @$CFG->list_customlabel_disabled;
	    $disabledarr = explode(',', $disabled);
	    
	    $classdir = opendir($basetypedir);
	    while ($entry = readdir($classdir)){
	        if (preg_match("/^[.!]/", $entry)) continue; // ignore what needs to be ignored
	        if (!is_dir($basetypedir.'/'.$entry)) continue; // ignore real files
	        if (preg_match('/^CVS$/', $entry)) continue; // ignore versionning files
	        if ($entry == 'NEWTYPE') continue; // discard plugin prototype
	        $enabledkey = "customlabel_{$entry}_enabled";
	        if (!isset($CFG->$enabledkey)) $CFG->$enabledkey = true; // open all possibilities by default
	        if (!$CFG->$enabledkey && $ignoredisabled) continue; // check admin config
	        if (in_array($entry, $disabledarr) && $ignoredisabled) continue; // ignore config.php disabled
	        $obj = new StdClass;
	        $obj->id = $entry;
	        $obj->name = get_string('typename', 'customlabeltype_'.$entry);
	        $classes[] = $obj;
	    }
	}

    // sort result against localized names
    $function = create_function('$a, $b', 'return strnatcmp($a->name, $b->name);');
    uasort($classes, $function);
    
    // print_object($classes);
        
    if ($context){
        customlabel_filter_role_disabled($classes, $context); // filter against roles
    }
    return $classes;
}

/**
* filters types against roles of the current user. Checks in global configuration keys
* for custom label.
* @param reference $classes
* @param int $context
* @uses $CFG.
* @uses $USER;
*
*/
function customlabel_filter_role_disabled(&$classes, $context){
    global $CFG, $USER;

    $roleids = array();
    $roles = get_user_roles($context, $USER->id, true);
    foreach($roles as $role){
        $roleids[] = $role->roleid;
    }
    
    for($i = 0 ; $i < count($classes) ; $i++){
        $typename = $classes[$i]->id;
        $globparm = "customlabel_{$typename}_hiddenfor";
        $disabledrolelist = explode(",", @$CFG->{$globparm});
        $diff = array_diff($roleids, $disabledrolelist);
        // if all roles held by user here are in disabled list, put it out.
        if (!empty($roleids) && empty($diff)){
        	echo "disengaging $typename"; 
            unset($classes[$i]);
        }
    }    
}

/**
* get a suitable CSS for a class after checking it exists.
* @param string $classname
* @return a suitable url for getting this local sheet
* @uses $CFG
*/
function customlabel_get_stylesheet($classname){
    global $CFG;
    
    $theme = current_theme();
    $css = $CFG->themewww ."/{$theme}/customlabel/{$classname}/customlabel.css";
    $cssloc = $CFG->dirroot ."/theme/{$theme}/customlabel/{$classname}/customlabel.css";
    if (file_exists($cssloc)) return $css;

    $css = $CFG->wwwroot ."/mod/customlabel/type/{$classname}/customlabel.css";
    $cssloc = $CFG->dirroot ."/mod/customlabel/type/{$classname}/customlabel.css";
    if (file_exists($cssloc)) return $css;
    
    return '';
}

/**
* makes an instance of the customlabel description object
* @param object $customlabel a customlabel record from the database
* @param boolean $quiet if true, will be silent when failing finding the class reference
* @return an instanciated classed object, loaded with the data in the record.
* @uses $CFG
*/
function customlabel_load_class($customlabel, $quiet = false){
    global $CFG;
    
    if (is_null($customlabel)){
        print_error('errorclassloading', 'customlabel');
    }
    
    $classfile = $CFG->dirroot."/mod/customlabel/type/{$customlabel->labelclass}/customlabel.class.php";
    if (file_exists($classfile)){
        include_once $classfile;
        $constructorfunction = "customlabel_type_{$customlabel->labelclass}";
        $instance = new $constructorfunction($customlabel, $customlabel->labelclass, $customlabel->processedcontent);
        return $instance;
    } else {
        if (!$quiet)
        	print_object($customlabel);
            print_error('errorfailedloading', 'customlabel', $customlabel->labelclass);
        return NULL;
    }
}

/**
* preprocesses for content serialization
* @param object $customlabel
* @return the filtered object
*/
function customlabel_stripslashes_fields($customlabel){

    // unprotects single quote in fields
    $customlabelarray = get_object_vars($customlabel);
    if ($customlabelarray){
        foreach($customlabelarray as $key => $value){
            $customlabel->{$key} = str_replace("\\'", "'", $customlabel->{$key});
            $customlabel->{$key} = str_replace("\\\"", "\"", $customlabel->{$key});
        }
    }
    return $customlabel;
}


/**
* preprocesses for content serialization
* @param object $customlabel
* @return the filtered object
*/
function customlabel_addslashes_fields($customlabel){

    // protects single quote in fields
    $customlabelarray = get_object_vars($customlabel);
    if ($customlabelarray){
        foreach($customlabelarray as $key => $value){
            if ($key == 'content'){
                $customlabel->{$key} = str_replace("\\", "\\\\", $customlabel->{$key});
                $customlabel->{$key} = str_replace("'", "\\'", $customlabel->{$key});
            }
        }
    }
    return $customlabel;
}
?>