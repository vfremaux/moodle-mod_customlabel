moodle-mod_customlabel
=======================

High semantic elements for course content. Are used as course labels but wth preformatted information model and rendering.

Actual status : Only MOODLE_19_STABLE available

Constraints and restriction : 

this module needs some changes in the way courses (core) displays modules in a course, to make the "label" behaviour
extend to whatever module called "xxxxxlabel".

There are a little amount of key locations in the core code where processing is aware of the "label" specificity.

2017020700 : Add content control webservices.

2018111900 : Major change
==========================================

Architecture change:

A major change occured in this version that removes completely the internal caching effect.
The customlabels are now dynamically produced based on subplugins mustache templates. there
is no more provision for customlabel regeneration and changes made on templates or internal
model is now applied immediately. the processedcontent field in database is obsolete and
will be further removed.

All customlabeltypes have been turned into AMD javascript support for better robustness.

The architecture change keeps all existing data.

Suptypes evolution :

Thanks to the new, smarter and srong architecture, some subtypes have been enhanced :

- Question subtype now let the teacher add a revealing date for the answer.
- Theorema subtype also has a revealing date for the demonstration.
- New collapsedtext subtype presents collapsible chapters with completion tracking
- Heading subtypes now let you control the exact width of the thumb

Customlabel completion:

An internal completion and local user data support has been added for those labels that may
require interaction with the user. Completion support provides three internal rules that
customlabel types can use to comply distinct completion strategies. When a customlabel subtype
uses completion, it will provide contextually the upper plugin the meaning of the used rules, and
for each used rule the completion condition trigger.

For those administrators who use LearningTimeCheck tracking module, you will need to upgrade
the LTC to the last version so the customlabel module will not be considered as a meaningless
label module.

Further roadmap :

- More pedagogic micro-interactions on subtypes such as :
   - Remind subtype : let the student program a reminder message emission with the remind content
   - Keypoints : let the student check the keypoint to track self-mastering
   - Worktodo : add detail subtopics that can be self checked by student. All checks will complete.
   - Question : add the possiblity for student to answer and assess the completion on the question
   - SingleChoice (new type to forge) : Unique tiny QCM single choice question with completion maked on success or response.

# 2018120600
####################################

Raise metadata type name precision to 255 to afford translated names spans.