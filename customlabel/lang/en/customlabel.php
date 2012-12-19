<?php
global $CFG;

$string['configusesafestorage'] = 'If enabled, any old storage will be converted when edited. New storage will be used for converted items.';
$string['changetypeadvice'] = "You are about to thange the internal data structure of this element. Old content cannot be maintained. Continue?";
$string['customlabel:addinstance'] = 'Can add an instance';
$string['customlabel:fullaccess'] = 'Full access to all fields ';
$string['customlabeltools'] = 'Mass tools for custom labels';
$string['doupdate'] = 'Update !!';
$string['enabletype'] = 'Enable subtype';
$string['errorclassloading'] = 'Error loading : Null class';
$string['errorfailedloading'] = 'Failed loading class for custom label {$a}. Reverting to "text" customlabel.';
$string['exportdata'] = 'Export data to XML';
$string['editvalues'] = 'Edit values';
$string['hiddenrolesfor'] = 'Roles that CANNOT USE ';
$string['labelclass'] = 'Label type';
$string['labelclasses'] = 'Element classes';
$string['labelupdater'] = '{$a} Regeneration Tool';
$string['languages'] = 'Language';
$string['missingfields'] = 'Mandatory fields have not been defined';
$string['modulename'] = 'Course element';
$string['pluginname'] = 'Course element';
$string['modulenameplural'] = 'Course elements';
$string['name'] = 'Label';
$string['nocontentforthislanguage'] = 'No content available for this language<br/>';
$string['resourcetypecustomlabel'] = 'Course element';
$string['regenerate'] = 'Regenerate';
$string['regeneration'] = 'Content mass regeneration';
$string['roleaccesstoelements'] = 'Access per role';
$string['sametypes'] = 'You cannot constraint twice the same type';
$string['storage'] = 'Storage model';
$string['title'] = 'Element name';
$string['updateall'] = 'Update all instances';
$string['updatelabels'] = 'Regenerate instances of {$a}';
$string['updatescope'] = 'Updating scope';
$string['usesafestorage'] = 'Use safe storage for content (base64)';
$string['pluginadministration'] = 'Course element administration';

// Metadata
$string['adminmetadata'] = 'Classifiers configuration';
$string['metadata'] = 'Metadata values';
$string['metadataset'] = 'Classification values';
$string['classifiers'] = 'Classifiers';
$string['qualifiers'] = 'Qualifiers';
$string['classifierstypes'] = 'Classifiers type';
$string['classification'] = 'Classification';
$string['constraints'] = 'Constraints';
$string['commands'] = 'Commands';
$string['typename'] = 'Name';
$string['typetype'] = 'Type';
$string['noclassifiers'] = 'No classifier';
$string['metadata :'] = 'Metadata:';
$string['editclass'] = 'Update classifier class ';
$string['category'] = 'Category';
$string['filter'] = 'Filter';
$string['usedas'] = 'Use as ';
$string['none'] = 'Undefined';
$string['include'] = 'Include';
$string['exclude'] = 'Exclude';
$string['value'] = 'Value';
$string['code'] = 'Code';
$string['novalues'] = 'No registered value';
$string['notypes'] = 'No classifier type';
$string['up'] = 'Up';
$string['down'] = 'Down';

$string['templatenotfound'] = 'Template {$a} not found';

// known types
$string['text'] = 'Text';
$string['content'] = 'Content';

/*
// this language files loads dynamically discovered label types
if (!function_exists('local_customlabel_get_classes')){
    function local_customlabel_get_classes(){
        global $CFG;
        
        $classes = array();
        $basetypedir = $CFG->dirroot."/mod/customlabel/type";
        
        $classdir = opendir($basetypedir);
        while ($entry = readdir($classdir)){
            if (preg_match("/^[.!]/", $entry)) continue; // ignore what need to be ignored
            if (!is_dir($basetypedir.'/'.$entry)) continue; // ignore real files
            unset($obj);
            $obj->id = $entry;
            $classes[] = $obj;
        }
        closedir($classdir);
        return $classes;
    }
}


// get strings for known types
$classes = local_customlabel_get_classes();
if (!empty($classes)){
    foreach($classes as $atype){
        $typelangfile = $CFG->dirroot."/mod/customlabel/type/{$atype->id}/en/customlabel.php";
        if (file_exists($typelangfile)){
            include_once($typelangfile);
        }
    }
}
*/