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

namespace mod_customlabel\privacy;

use \core_privacy\local\request\writer;
use \core_privacy\local\metadata\collection;

defined('MOODLE_INTERNAL') || die();

class provider implements \core_privacy\local\metadata\provider {

    public static function get_metadata(collection $collection) : collection {

        $fields = [
            'userid' => 'privacy:metadata:customlabel_user_data:userid',
            'customlabelid' => 'privacy:metadata:customlabel_user_data:customlabelid',
            'completion1' => 'privacy:metadata:customlabel_user_data:completion1',
            'completion2' => 'privacy:metadata:customlabel_user_data:completion2',
            'completion3' => 'privacy:metadata:customlabel_user_data:completion3',
        ];

        $collection->add_database_table('customlabel_user_data', $fields, 'privacy:metadata:customlabel_user_data');

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param   int           $userid       The user to search.
     * @return  contextlist   $contextlist  The list of contexts used in this plugin.
     */
  public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new contextlist();

        // Fetching customodules context should be sufficiant to get contexts where user is involved in.
        // It may have NO states if it has no deck cards.

        $sql = "
            SELECT
                c.id
            FROM
                {context} c
            INNER JOIN
                {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
            INNER JOIN
                {modules} m ON m.id = cm.module AND m.name = :modname
            INNER JOIN
                {customlabel} c ON c.id = cm.instance
            LEFT JOIN
                {customlabel_user_data} cud ON cud.customlabelid = c.id
            WHERE cud.userid = :userid
        ";

        $params = [
            'modname'           => 'customlabel',
            'contextlevel'      => CONTEXT_MODULE,
            'userid'  => $userid,
        ];

        $contextlist->add_from_sql($sql, $params);
    }

    /**
     * Export all user data for the specified user, in the specified contexts, using the supplied exporter instance.
     *
     * @param   approved_contextlist    $contextlist    The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        $user = $contextlist->get_user();

        foreach ($contextlist->get_contexts() as $ctx) {
            $instance = writer::withcontext($ctx);

            $data = new StdClass;

            $params = array('customlabelid' => $ctx->instanceid,
                            'userid' => $user->id);
            $completions = $DB->get_records('customlabel_user_data', $params);

            foreach ($completions as $cp) {
                $label = $DB->get_record('customlabel', ['id' => $cp->customlabelid]);
                $exportlabel = new StdClass;
                $exportlabel->course = $DB->get_field('course', 'shortname', ['id' => $label->course]);
                $exportlabel->name = $label->name;
                $exportlabel->title = $label->title;
                $data->labels[$label->name] = $exportlabel;
                $data->labelcompletions[$label->name] = $cp;
            }

            $instance->export_data(null, $data);
        }
    }

    public static function delete_data_for_all_users_in_context(deletion_criteria $criteria) {
        global $DB;

        $context = $criteria->get_context();
        if (empty($context)) {
            return;
        }

        $DB->delete_records('customlabel_user_data', ['customlabelid' => $context->instanceid]);
    }

    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }
        $userid = $contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $ctx) {
            $DB->delete_records('customlabel_user_data', ['customlabelid' => $ctx->instanceid, 'userid' => $userid]);
        }
    }
}