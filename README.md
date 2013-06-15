moodle-mod_customlabel
=======================

High semantic elements for course content. Are used as course labels but wth preformatted information model and rendering.

Actual status : Only MOODLE_19_STABLE available

Constraints and restriction : 

this module needs some changes in the way courses (core) displays modules in a course, to make the "label" behaviour
extend to whatever module called "xxxxxlabel".

There are a little amount of key locations in the core code where processing is aware of the "label" specificity.