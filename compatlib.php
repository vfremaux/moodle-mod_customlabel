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
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 *
 * TODO : check if there is not a legacy post install function in module API
 */

/**
 * Get all stealth modules. On page format, there is no need
 * of stealth modules as this is naturally handled with the
 * page publishing (or not) concept.
 */
function customlabel_get_stealth_cms($activeoptions = '', $course = null) {
    global $COURSE, $DB;

    if (is_null($course)) {
        $course = $COURSE;
    }

    $coursemodinfo = get_fast_modinfo($course);
    $stealthcms = [];

    if ($course->format !== 'page') {
        $allcms = $coursemodinfo->get_cms();
        if (!empty($allcms)) {
            foreach ($allcms as $cm) {
                if ($cm->is_stealth()) {
                    $stealthcms[$cm->id] = format_string($cm->name);
                }
            }
        }
    } else {

        // Get all course modules that remained unpublished on pages.
        $sql = "
            SELECT
                id as modid,
                fpi.id as pageid
            FROM
                {course_modules} cm
            LEFT JOIN
                {format_page_item} fpi
            ON
                fpi.moduleid = cm.id
            WHERE
                fpi.id IS NULL
        ";
        $cms = $DB->get_records_sql($sql);

        if ($cms) {
            foreach (array_keys($cms) as $cm) {
                // Convert into cm_info.
                $cminfo = $coursemodinfo->get_cm($cm->modid);
                $stealthcms[$cm->modid] = format_string($cminfo->name);
            }
        }
    }

    return $stealthcms;
}