<?php  //$Id: upgrade.php,v 1.7 2011-09-28 23:06:12 vf Exp $

// This file keeps track of upgrades to 
// the customlabel module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_customlabel_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;

/// And upgrade begins here. For each one, you'll need one 
/// block of code similar to the next one. Please, delete 
/// this comment lines once this file start handling proper
/// upgrade code.

/// if ($result && $oldversion < YYYYMMDD00) { //New version in version.php
///     $result = result of "/lib/ddllib.php" function calls
/// }

//===== 1.9.0 upgrade line ======//

if ($result && $oldversion < 2008112600) {
    
    // add classification model and external db bound lists

    /// Define table customlabel_metadata_type to be created
        $table = new XMLDBTable('customlabel_mtd_type');

    /// Adding fields to table customlabel_metadata_type
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('type', XMLDB_TYPE_CHAR, '40', null, null, null, XMLDB_ENUM, array('category', 'filter'), null);
        $table->addFieldInfo('name', XMLDB_TYPE_CHAR, '40', null, null, null, null, null, null);
        $table->addFieldInfo('description', XMLDB_TYPE_TEXT, 'small', null, null, null, null, null, null);
        $table->addFieldInfo('sortorder', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0');

    /// Adding keys to table customlabel_metadata_type
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

    /// Adding indexes to table customlabel_metadata_type
        $table->addIndexInfo('foreign_type', XMLDB_INDEX_UNIQUE, array('type'));

    /// Launch create table for customlabel_metadata_type
        $result = $result && create_table($table);
        
    /// Define table customlabel_metadata_value to be created
        $table = new XMLDBTable('customlabel_mtd_value');

    /// Adding fields to table customlabel_metadata_value
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('typeid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, null, null);
        $table->addFieldInfo('code', XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null);
        $table->addFieldInfo('value', XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null);
        $table->addFieldInfo('translatable', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0');
        $table->addFieldInfo('ordering', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0');
        $table->addFieldInfo('parent', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0');

    /// Adding keys to table customlabel_metadata_value
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

    /// Launch create table for customlabel_metadata_value
        $result = $result && create_table($table);

    /// Define table customlabel_course_metadata to be created
        $table = new XMLDBTable('customlabel_course_metadata');

    /// Adding fields to table customlabel_course_metadata
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('courseid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0');
        $table->addFieldInfo('valueid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0');

    /// Adding keys to table customlabel_course_metadata
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

    /// Launch create table for customlabel_course_metadata
        $result = $result && create_table($table);

    }

    if ($result && $oldversion < 2011040405) {

    /// Define field safecontent to be added to customlabel
        $table = new XMLDBTable('customlabel');
        $field = new XMLDBField('safecontent');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'small', null, null, null, null, null, null, 'content');

    /// Launch add field safecontent
        $result = $result && add_field($table, $field);
	}

    if ($result && $oldversion < 2011040406) {

    /// Define field safecontent to be added to customlabel
        $table = new XMLDBTable('customlabel');
        $field = new XMLDBField('usesafe');
        $field->setAttributes(XMLDB_TYPE_INTEGER, 1, null, null, null, null, null, 0, 'safecontent');

    /// Launch add field safecontent
        $result = $result && add_field($table, $field);
    }

	/// we need reencode all instances to Base64 content
    if ($result && $oldversion < 2011040410) {
	}

	if ($result && $oldversion < 2011071200) {
    
    // add classification model and external db bound lists

    /// Define table customlabel_metadata_type to be created
        $table = new XMLDBTable('customlabel_mtd_type');
        $field = new XMLDBField('code');
        $field->setAttributes(XMLDB_TYPE_CHAR, 15, null, null, null, null, null, 0, 'type');

    /// Launch add field safecontent
        $result = $result && add_field($table, $field);
    }

	if ($result && $oldversion < 2011092000) {
    
    // add classification model and external db bound lists

    /// Define table customlabel_metadata_type to be created
        $table = new XMLDBTable('customlabel_mtd_value');
        $field = new XMLDBField('code');
        $field->setAttributes(XMLDB_TYPE_CHAR, 15, null, null, null, null, null, 0, 'typeid');

    /// Launch add field safecontent
        $result = $result && add_field($table, $field);

        $field = new XMLDBField('ordering');
        $field->setAttributes(XMLDB_TYPE_INTEGER, 4, null, null, null, null, null, 0, 'value');

    /// Launch add field safecontent
        $result = $result && add_field($table, $field);

        $field = new XMLDBField('parent');
        $field->setAttributes(XMLDB_TYPE_INTEGER, 11, null, null, null, null, null, 0, 'translatable');

    /// Launch add field safecontent
        $result = $result && add_field($table, $field);
    }

    return $result;
}

?>