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
 * Backup task.
 *
 * @package    mod_customlabel
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2008 Valery Fremaux (www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/mod/customlabel/backup/moodle2/backup_customlabel_stepslib.php'); // Because it exists (must).

/**
 * Customlabel backup task that provides all the settings and steps to perform one
 * complete backup of the activity
 */
class backup_customlabel_activity_task extends backup_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
        assert(1);
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // Choice only has one structure step.
        $this->add_step(new backup_customlabel_activity_structure_step('customlabel_structure', 'customlabel.xml'));
    }

    /**
     * Code the transformations to perform in the activity in
     * order to get transportable (encoded) links
     */
    public static function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');

        // Link to the list of customlabels.
        $search = "/(".$base."\/mod\/customlabel\/index.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@CUSTOMLABELINDEX*$2@$', $content);

        // Link to customlabel view by moduleid. - There should not be any.
        $search = "/(".$base."\/mod\/customlabel\/view.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@CUSTOMLABELVIEWBYID*$2@$', $content);

        return $content;
    }
}
