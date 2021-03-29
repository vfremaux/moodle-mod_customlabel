<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@club-internet.fr>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/type/customtype.class.php');

/**
 *
 *
 */

class customlabel_type_authordata extends customlabel_type {

    public $nbauthor = 5;

    public function __construct($data) {
        global $USER;

        parent::__construct($data);
        $this->type = 'authordata';
        $this->fields = array();

        $field = new StdClass;
        $field->name = 'tablecaption';
        $field->type = 'textfield';
        $this->fields['tablecaption'] = $field;

        for ($i = 1; $i <= $this->nbauthor; $i++) {
            $field = new StdClass;
            $field->name = 'author'.$i;
            $field->type = 'textfield';
            $this->fields['author'.$i] = $field;

            $field = new StdClass;
            $field->name = 'thumb'.$i;
            $field->type = 'filepicker';
            $field->destination = 'url';
            $field->default = '';
            $this->fields['thumb'.$i] = $field;

            $field = new StdClass;
            $field->name = 'institution'.$i;
            $field->type = 'textfield';
            $field->default = '';
            $this->fields['institution'.$i] = $field;

            $field = new StdClass;
            $field->name = 'department'.$i;
            $field->type = 'textfield';
            $field->default = '';
            $this->fields['department'.$i] = $field;
        }

        $field = new StdClass;
        $field->name = 'showinstitution';
        $field->type = 'choiceyesno';
        $this->fields['showinstitution'] = $field;

        $field = new StdClass;
        $field->name = 'institution';
        $field->type = 'textfield';
        $field->default = @$USER->institution;
        $this->fields['institution'] = $field;

        $field = new StdClass;
        $field->name = 'showdepartment';
        $field->type = 'choiceyesno';
        $this->fields['showdepartment'] = $field;

        $field = new StdClass;
        $field->name = 'department';
        $field->type = 'textfield';
        $field->default = @$USER->department;
        $this->fields['department'] = $field;

        $field = new StdClass;
        $field->name = 'showcontributors';
        $field->type = 'choiceyesno';
        $this->fields['showcontributors'] = $field;

        $field = new StdClass;
        $field->name = 'contributors';
        $field->type = 'editor';
        $field->itemid = 0;
        $this->fields['contributors'] = $field;
    }

    public function postprocess_data($course = null) {

        $this->data->authors = [];
        $this->data->hasmany = 0;

        $j = 1;
        for ($i = 1; $i < $this->nbauthor; $i++) {

            if (!empty($this->data->{'author'.$i})) {

                if ($j > 1) {
                    $this->data->hasmany = 1;
                }

                $authortpl = new StdClass;
                $authortpl->authorname = $this->data->{'author'.$i};
                if (isset($this->data->{'institution'.$i})) {
                    $authortpl->institution = $this->data->{'institution'.$i};
                }
                if (isset($this->data->{'department'.$i})) {
                    $authortpl->department = $this->data->{'department'.$i};
                }
                $thumb = $this->get_file_url('thumb'.$i);
                if ($thumb) {
                    $authortpl->thumb = $thumb->out();
                }

                $this->data->authors[] = $authortpl;
                $j++;
            }
        }

    }
}

