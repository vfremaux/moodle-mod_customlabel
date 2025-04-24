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
 * @package customlabeltype_satisfaction
 *
 * @author Valery Fremaux
 * @date 02/12/2007
 *
 * A generic class for collecting all that is common to all elements
 */
defined('MOODLE_INTERNAL') || die();

function customlabeltype_satisfaction_get_cms() {
    global $COURSE, $DB;

    list($insql, $inparams) = $DB->get_in_or_equal(['questionnaire', 'feedback']);

    $sql = "
        SELECT
            cm.id,
            m.name as modname,
            cm.instance
        FROM
            {course_modules} cm,
            {modules} m
        WHERE
            cm.module = m.id AND
            m.name $insql AND
            cm.course = ? AND
            cm.deletioninprogress <> 1
    ";

    $inparams[] = $COURSE->id;

    $cms = $DB->get_records_sql($sql, $inparams);

    $mods = [];
    if (!empty($cms)) {
        foreach ($cms as $cm) {
            $mods[$cm->id] = $DB->get_field($cm->modname, 'name', ['id' => $cm->instance]);
        }
    }

    return $mods;
}
