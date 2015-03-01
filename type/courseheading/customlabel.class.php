<?php

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 *
 *
 */

class customlabel_type_courseheading extends customlabel_type {

    public function __construct($data) {
        global $CFG, $COURSE, $PAGE;

        parent::__construct($data);
        $this->type = 'courseheading';
        $this->fields = array();

        $field = new StdClass();
        $field->name = 'showdescription';
        $field->type = 'choiceyesno';
        $this->fields['showdescription'] = $field;

        $field = new StdClass();
        $field->name = 'showshortname';
        $field->type = 'choiceyesno';
        $this->fields['showshortname'] = $field;

        $field = new StdClass();
        $field->name = 'showidnumber';
        $field->type = 'choiceyesno';
        $this->fields['showidnumber'] = $field;

        $field = new StdClass();
        $field->name = 'showcategory';
        $field->type = 'choiceyesno';
        $this->fields['showcategory'] = $field;
        
        $field = new StdClass();
        $field->name = 'image';
        $field->type = 'filepicker';
        $field->destination = 'url';
        if ($PAGE->state >= moodle_page::STATE_IN_BODY) {
            if (!is_file($CFG->dirroot.'/theme/'.$PAGE->theme->name.'/pix/customlabel_icons/defaultcourseheading.png')){
                $field->default = $CFG->wwwroot.'/mod/customlabel/type/courseheading/defaultheading.png';
            } else {
                $field->default = $CFG->wwwroot.'/theme/'.$PAGE->theme->name.'/pix/customlabel_icons/defaultcourseheading.png';
            }
        } else {
            $field->default = $CFG->wwwroot.'/mod/customlabel/type/courseheading/defaultheading.png';
        }
        $this->fields['image'] = $field;

        $field = new StdClass();
        $field->name = 'overimagetext';
        $field->type = 'textfield';
        $field->size = 20;
        $field->default = '';
        $this->fields['overimagetext'] = $field;

        $field = new StdClass();
        $field->name = 'imageposition';
        $field->type = 'list';
        $field->options = array('none', 'left', 'right');
        $field->default = 'none';
        $this->fields['imageposition'] = $field;

        $field = new StdClass();
        $field->name = 'moduletype';
        $field->type = 'textfield';
        $field->size = 40;
        $field->default = get_string('trainingmodule', 'customlabeltype_courseheading');
        $this->fields['moduletype'] = $field;
    }

    /**
     * If exists, this method can process local alternative values for
     * realizing the template, after all standard translations have been performed. 
     * Type information structure and application context dependant.
     */
    public function postprocess_data($course = null) {
        global $CFG, $COURSE, $DB;

        if (is_null($course)) $course = &$COURSE;

        // Get virtual fields from course title.
        $this->data->courseheading = format_string($course->fullname);
        $this->data->coursedesc = format_text($course->summary, $course->summaryformat);
        $this->data->idnumber = $course->idnumber;
        $this->data->shortname = $course->shortname;
        $storedimage = $this->get_file_url('image');
        $imageurl = (!empty($storedimage)) ? $storedimage : $this->fields['image']->default;
        if ($this->data->imagepositionoption == 'left') {
            $this->data->imageL = "<td width=\"100\" class=\"custombox-icon-left courseheading\" align=\"center\" style=\"background:url({$imageurl}) 50% 50% no-repeat transparent\">{$this->data->overimagetext}</td>";
            $this->data->imageR = '';
        } elseif ($this->data->imagepositionoption == 'right') {
            $this->data->imageL = '';
            $this->data->imageR = "<td width=\"100\" class=\"custombox-icon-right courseheading\" align=\"center\" style=\"background:url({$imageurl}) 50% 50% no-repeat transparent\">{$this->data->overimagetext}</td>";
        } else {
            $this->data->imageL = '';
            $this->data->imageR = '';
        }
        $cat = $DB->get_record('course_categories', array('id' => $course->category));
        $this->data->category = $cat->name;
    }
}

