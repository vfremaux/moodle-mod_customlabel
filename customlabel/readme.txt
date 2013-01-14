Moodle plugin : Customlabel
Author : Valery Fremaux / EISTI for Intel Corp.
===============================================
This plugin is an enhanced label that can precharacterize its content both
in the information structure and the rendering. 

It may be used just as a standard Moodle Label to display information in
a course layout. Unlike the label that allows the editing user to shape
the label content and rendering as he likes to, the custom label allows
information editing within a simple data model made with named fields.

The editing user will be able to change fields' content, but will not be allowed
to change information layout or styling. 

This module has a subplugin interface through which many new customized information
blocks can be defined. 

This block is for use by administrators or system architects to define information
packs that need to be edited and presented in a controlled way, such as displaying
a set of predefined metadata within a course space, or defining standardized 
information sets that teachers should use to normalize course construction and/or aspect.

Installing the plugin
=====================
Installs just as any other Moodle plugin : 
1. Unzip the plugin within the mod/customlabel directory
2. Goto Administration->notifications and logically install the plugin model

Making your own block types
===========================
This plugin is valuable essentially by creating new custom types. 

A custom type is a directory in the mod/customlabel/types subdirectory with
a set of standard label components : 

- a PHP class defines the internal structure of information that makes the information block. This class
has a member name type (just for autoreference) and a really important member named "fields" that contains
a hashed array on all the information scalars. Fields can hold short text, long text, and lists (single
and multiple options). Although we are naming widgets, the custom label WILL NOT present a form to the 
user. Lists are provided for giving a possible multiple value choice in a closed options set. List options
are defined as a subarray of the $field value.

- a CSS file that defines all redering attributes for the generated content.

- a set of localized templates along with translation files in consequent subdirs : 

    - a template.tpl file defines the HTML template where to insert data. The data is inserted 
    using <%%x%%> tags where x is the field name. (There is no provision for a field holding
    an array).
    
    - a customlabel.php file that holds localization inputs for the type contextual information
    such as list option trnaslations, field name translations, etc. 

You may make your own block types using a clone of the NEWTYPE subtype.

The custom label automatially discovers any new type added to the mod/customlabel/types directory. 
