<?php

/**
* @package mod-customlabel
* @category mod
* @author Valery Fremaux for Pairformance/TAO
* @date 15/07/2008
*/

// TODO : check if there is not a legacy post install function in module API
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
function customlabel_get_classes($context=null, $allclasses = false){
    global $CFG;
    
    $classes = array();
    
    $basetypedir = $CFG->dirroot."/mod/customlabel/type";
    
    $classdir = opendir($basetypedir);
    while ($entry = readdir($classdir)){
        if (preg_match("/^[.!]/", $entry)) continue; // ignore what needs to be ignored
        if (!is_dir($basetypedir.'/'.$entry)) continue; // ignore real files
        if (preg_match('/^CVS$/', $entry)) continue; // ignore versionning files
        if ($entry == 'NEWTYPE') continue; // discard plugin prototype
        $enableparamkey = 'list_customlabel_'.$entry.'_enabled';
        if (!$allclasses && empty($CFG->{$enableparamkey})) continue; // ignore hidden by config plugins
        $obj = new StdClass;
        $obj->id = $entry;
        $obj->name = get_string($entry, 'customlabel');
        $classes[] = $obj;
    }

    // sort result against localized names
    $function = create_function('$a, $b', 'return strnatcmp($a->name, $b->name);');
    uasort($classes, $function);        
    
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
        if (empty($diff)){
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
        error ("Error loading : Null class");
    }
    
    $classfile = $CFG->dirroot."/mod/customlabel/type/{$customlabel->labelclass}/customlabel.class.php";
    if (file_exists($classfile)){
        include_once $classfile;
        $constructorfunction = "customlabel_type_{$customlabel->labelclass}";
        $instance = new $constructorfunction($customlabel);
        return $instance;
    } else {
    	$classfile = $CFG->dirroot."/mod/customlabel/type/text/customlabel.class.php";
        if (!$quiet){
            notify("Failed loading class for custom label {$customlabel->labelclass}. Reverting to \"text\" customlabel.", me().'&amp;what=changetype&amp;to=text');
        }
        include_once $classfile;
        $constructorfunction = "customlabel_type_text";
        $instance = new $constructorfunction($customlabel);
        return $instance;
    }
}

/**
 * a hack of choose_from_menu, adding multiple capability
 *
 * Given an array of values, output the HTML for a select element with those options.
 * Normally, you only need to use the first few parameters.
 *
 * @param array $options The options to offer. An array of the form
 *      $options[{value}] = {text displayed for that option};
 * @param string $name the name of this form control, as in &lt;select name="..." ...
 * @param array $selected the array of options to select initially, default none.
 * @param int $size gives the size for the list
 * @param string $nothing The label for the 'nothing is selected' option. Defaults to get_string('choose').
 *      Set this to '' if you don't want a 'nothing is selected' option.
 * @param string $script in not '', then this is added to the &lt;select> element as an onchange handler.
 * @param string $nothingvalue The value corresponding to the $nothing option. Defaults to 0.
 * @param boolean $return if false (the default) the the output is printed directly, If true, the
 *      generated HTML is returned as a string.
 * @param boolean $disabled if true, the select is generated in a disabled state. Default, false.
 * @param int $tabindex if give, sets the tabindex attribute on the &lt;select> element. Default none.
 * @param string $id value to use for the id attribute of the &lt;select> element. If none is given,
 *      then a suitable one is constructed.
 */
if (!function_exists('choose_from_menu_multiple')){
    function choose_from_menu_multiple($options, $name, $selected=null, $size=5, $nothing='choose', $script='',
                               $nothingvalue='0', $return=false, $disabled=false, $tabindex=0, $id='') {
    
        if ($nothing == 'choose') {
            $nothing = get_string('choose') .'...';
        }
    
        $attributes = ($script) ? 'onchange="'. $script .'"' : '';
        if ($disabled) {
            $attributes .= ' disabled="disabled"';
        }
    
        if ($tabindex) {
            $attributes .= ' tabindex="'.$tabindex.'"';
        }
        
        if (!$selected) $selected = array();
    
        if ($id ==='') {
            $id = 'menu'.$name;
             // name may contain [], which would make an invalid id. e.g. numeric question type editing form, assignment quickgrading
            $id = str_replace('[', '', $id);
            $id = str_replace(']', '', $id);
        }
    
        $output = '<select multiple="multiple" size="'.$size.'" id="'.$id.'" name="'. $name .'" '. $attributes .'>' . "\n";
        if ($nothing) {
            $output .= '   <option value="'. s($nothingvalue) .'"'. "\n";
            if ($nothingvalue === $selected) {
                $output .= ' selected="selected"';
            }
            $output .= '>'. $nothing .'</option>' . "\n";
        }
        if (!empty($options)) {
            foreach ($options as $value => $label) {
                $output .= '   <option value="'. s($value) .'"';
                $selectedarr = (!is_array($selected)) ? array($selected) : $selected ;
                if (in_array((string)$value, $selectedarr)) {
                    $output .= ' selected="selected"';
                }
                if ($label === '') {
                    $output .= '>'. $value .'</option>' . "\n";
                } else {
                    $output .= '>'. $label .'</option>' . "\n";
                }
            }
        }
        $output .= '</select>' . "\n";
    
        if ($return) {
            return $output;
        } else {
            echo $output;
        }
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