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
 * xlib.php is a cross-components library for functions that are required
 * from elsewhere in Moodle and not part of the standard Core API
 *
 * @package    mod_customlabel
 *
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

/**
 * this function for use in theme_xxx_process_css() function in coordination with a
 * [[customlabel|overrides]] tag placed into any stylesheet of the theme.
 */
function theme_set_customlabelcss($css) {
    $tag = '[[customlabel:overrides]]';
    $config = get_config('mod_customlabel');
    $replacement = @$config->cssoverrides;
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

/**
 * Get authors in a course based on presence of "course_author" type.
 * @param int $courseid
 */
function customlabel_get_authors($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'authordata'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    if (!$potcls) {
        return;
    }

    // At the moment take the first that comes.
    $cl = array_shift($potcls);

    $instance = customlabel_load_class($cl);
    return $instance;
}

/**
 * Get courseheading info based on presence of a courseheading type
 * If found more than one, take first.
 * @param int $courseid
 */
function customlabel_get_courseheading($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'courseheading'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    if (!$potcls) {
        return;
    }

    // At the moment take the first that comes.
    $cl = array_shift($potcls);

    $instance = customlabel_load_class($cl);
    return $instance;
}

/**
 * Get all classification values for the course. Based on presence of courseclassifier
 * types.
 * @param int $courseid
 */
function customlabel_get_all_classifiers($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'courseclassifier'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    return $potcls;
}

/**
 * Get all classification values for the course. Based on presence of courseclassifier
 * types. If more than one are found, take the first that comes.
 * @param int $courseid
 * @todo potentially enhance the candidate selection
 */
function customlabel_get_classifiers($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'courseclassifier'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    if (!$potcls) {
        return;
    }

    // At the moment take the first that comes.
    $cl = array_shift($potcls);

    $instance = customlabel_load_class($cl);
    return $instance;
}

/**
 * Get all course metadata on base of presence of a coursedata element
 * @param int $courseid
 */
function customlabel_get_coursedata($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'coursedata'];
    $potcls = $DB->get_records('customlabel', $params, 'id');

    if (!$potcls) {
        return;
    }

    // At the moment take the first that comes.
    $cl = array_shift($potcls);

    $instance = customlabel_load_class($cl);
    return $instance;
}

/**
 * Get the requestcontact element of the course. (first one)
 * @param int $courseid
 */
function customlabel_get_requestcontact($courseid) {
    global $DB;

    $params = ['course' => $courseid, 'labelclass' => 'requestcontact'];
    $potrq = $DB->get_records('customlabel', $params, 'id');

    if (!$potrq) {
        return;
    }

    // At the moment take the first that comes.
    $cl = array_shift($potrq);

    $instance = customlabel_load_class($cl);
    return $instance;
}

/**
 * Finds a customlabel, or all customlabels of some type in a course.
 * Special behaviour : in page format, needs to be published
 * @param object $course
 * @param string $type
 * @param bool $wantfirst
 * @param bool $onlyvisible
 * @param bool $onlypublished (only for page format)
 */
function customlabel_get_customlabel_in_course($course, $type, $wantfirst = false, $onlyvisible = true, $onlypublished = true) {
    global $DB;

    if ($course->format != 'page') {
        $params = ['course' => $course->id, 'customlabeltype' => $type];
        $instances = $DB->get_records('customlabel', $params);
    } else {

        $visibleclause = '';
        if (!empty($onlyvisible)) {
            $visibleclause = " AND
                fpi.visible = 1
            ";
        }

        $select = "
            SELECT
                c.*
            FROM
                {customlabel} c,
                {course_module} cm,
                {module} m,
                {format_page} fp,
                {format_page_items} fpi
            WHERE
                c.id = cm.instance AND
                cm.moduleid = m.id AND
                m.name = 'customlabel' AND
                fpi.cmid = cm.id AND
                fpi.pageid = fp.id AND
                fp.courseid = c.id
                {$visibleclause}
        ";

        $instances = $DB->get_records_sql($sql);
    }

    if (!empty($instances) && $wantfirst) {
        return array_pop($instances);
    }
    return $instances;
}

/**
 * Get any field in any customlabel
 * @param int $courseid
 * @param string $type
 * @param string $field
 * @todo finish implement
 */
function customlabel_get_customlabel_field($courseid, $type, $field) {
    return '';
}

/**
 * Search coures using customlabel metadata
 * @param string $searchcriteria
 * @todo finish implement it.
 */
function customlabel_search_courses($searchcriteria) {
    return [];
}
