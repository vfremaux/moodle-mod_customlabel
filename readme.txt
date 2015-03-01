Moodle plugin : Customlabel
Author : Valery Fremaux / VF Consulting.
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

Controlling who accesses or change the content (> 2015010300)
=============================================================

Each subtype has a controlling capability : customlabeltype/typename:view that will
define who can access the content.

Each subtype has a controlling capability : customlabeltype/typename:addinstance that will
define if the subtype is given to the editing user choice.

Editing the customlabel content is possible with the standard course editing capability.

Installing the plugin css overrides in theme (> 2015010300)
===========================================================

The customlabel gives provision for a custom theme to insert customized CSS for
labels into the generated CSS stylesheet of Moodle. 

You need:

1. Add the [[customlabel|overrides]] somewhere in your active stylesheets. F.e, create
a 'customlabels.css' file into the 'style' directory of the theme and just drop the above
tag in it. Add this sheets to the enabled sheets into the config.php file of the theme.

2. Add the following code sequence in the theme_xxx_process_css($css) function in the lib.php
file of your theme :

   if (file_exists($CFG->dirroot.'/mod/customlabel/xlib.php')) {
       include_once($CFG->dirroot.'/mod/customlabel/xlib.php');
       $css = theme_set_customlabelcss($css);
   }

3. Go to administration to customlabel activity module plugin global settings, and add any
rules to the CSS overrides. This way, you can delegate to an administrator enabled user the
possibility to refine the customlabel apparence. If you are the Moodle administrator yourself,
you may direcltly write overrides in the customlabel.css additional stylesheet without more changes.

Making your own block types (> 2015010300)
==========================================
This plugin is valuable essentially by creating new custom types. 

A custom type is a directory in the mod/customlabel/types subdirectory with
a set of standard label components : 

the subtype is a Moodle subplugin, thus has a version, and several typical files for a true plugin:

- a db Directory with an access file defining 2 capabilities (view (module level) and addinstance (course level))

- a lang directory with local language defines. The lang stringset has to define the 'template' string entry as the 
fragment of HTML used for renderering content. Variables of the subtype data model will be inserted as <%%fieldname%%>
tags where required. The template also accepts <%%COURSEID%%>, <%%USERID%%>, and <%%WWWROOT%%> tags for replacement
when the label is preprocessed (at creation or update time).

- a PHP class defines the internal structure of information that makes the information block. This class
has a member name type (just for autoreference) and a really important member named "fields" that contains
a hashed array on all the information scalars. Fields can hold short text, long text, and lists (single
and multiple options). Although we are naming widgets, the custom label WILL NOT present a form to the 
user. Lists are provided for giving a possible multiple value choice in a closed options set. List options
are defined as a subarray of the $field value.

- a CSS file that defines all rendering attributes for the generated content.

You may make your own block types using a clone of the NEWTYPE subtype.

The custom label automatially discovers any new type added to the mod/customlabel/types directory. 
