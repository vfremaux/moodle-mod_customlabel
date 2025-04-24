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

namespace mod_customlabel\local\hooks_output;

/**
 * Allows plugins to add any elements to the footer.
 *
 * @package    mod_customlabel
 */
class before_http_headers {

    /**
     * Callback to add head elements.
     * Here : no html added. Just ensure some libs are available.
     *
     * @param \core\hook\output\before_http_headers $hook
     */
    public static function callback(\core\hook\output\before_http_headers $hook): void {
        global $DB, $COURSE, $CFG;

        // Get all distinct labeltypes
        $sql = "
            SELECT DISTINCT
                labelclass
            FROM
                {customlabel}
            WHERE
                course = :course
        ";
        $labels = $DB->get_records_sql($sql, ['course' => $COURSE->id]);
        if ($labels) {
            $labelclasses = array_keys($labels);
            // TODO : Do this better and discover by classlabel.
            if (in_array('satisfaction', $labelclasses)) {
                if (is_dir($CFG->dirroot.'/local/vflibs')) {
                    include_once($CFG->dirroot.'/local/vflibs/jqplotlib.php');
                    local_vflibs_require_jqplot_libs();
                }
            }
        }
    }
}
