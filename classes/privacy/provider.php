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
 * Privacy for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23 <moodle@brainstation-23.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_proctoring\privacy;

use context;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\core_userlist_provider;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;
use dml_exception;
use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * provider
 */
class provider implements
    \core_privacy\local\metadata\provider,
    core_userlist_provider,
    \core_privacy\local\request\subsystem\provider {

    /**
     * Provides meta data that is stored about a user with quizaccess_proctoring
     *
     * @param collection $collection A collection of meta data items to be added to.
     * @return  collection Returns the collection of metadata.
     */
    public static function get_metadata(collection $collection): collection {
        $quizaccessproctoringlogs = [
            'courseid' => 'privacy:metadata:courseid',
            'quizid' => 'privacy:metadata:quizid',
            'userid' => 'privacy:metadata:userid',
            'webcampicture' => 'privacy:metadata:webcampicture',
            'status' => 'privacy:metadata:status',
            'timemodified' => 'timemodified',
        ];

        $collection->add_database_table(
            'quizaccess_proctoring_logs',
            $quizaccessproctoringlogs,
            'privacy:metadata:quizaccess_proctoring_logs'
        );

        $collection->add_subsystem_link(
            'core_files',
            [],
            'privacy:metadata:core_files'
        );

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return  contextlist   $contextlist  The list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $params = ['userid' => $userid, 'contextuser' => CONTEXT_USER];
        $sql = "SELECT id
                  FROM {context}
                 WHERE instanceid = :userid and contextlevel = :contextuser";
        $contextlist = new contextlist();
        $contextlist->add_from_sql($sql, $params);
        return $contextlist;
    }

    /**
     * Get the list of users within a specific context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if (!$context instanceof \context_user) {
            return;
        }

        $userlist->add_user($context->instanceid);
    }

    /**
     * Export personal data for the given approved_contextlist. User and context information is contained within the contextlist.
     *
     * @param approved_contextlist $contextlist
     * @throws dml_exception
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        $context = $contextlist->current();
        $records = $DB->get_records('quizaccess_proctoring_logs', ['userid' => $contextlist->get_user()->id]);
        static::export_user($records, $context);
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context
     * @throws dml_exception
     */
    public static function delete_data_for_all_users_in_context(context $context) {
        // Only delete data for a user context.
        if ($context->contextlevel == CONTEXT_USER) {
            static::delete_user_data($context->instanceid, $context);
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     * @throws dml_exception
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        $context = $userlist->get_context();

        if ($context instanceof \context_user) {
            static::delete_user_data($context->instanceid, $context);
        }
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     * @throws dml_exception
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        foreach ($contextlist as $context) {
            // Let's be super certain that we have the right information for this user here.
            if ($context->contextlevel == CONTEXT_USER && $contextlist->get_user()->id == $context->instanceid) {
                static::delete_user_data($contextlist->get_user()->id, $contextlist->current());
            }
        }
    }

    /**
     * @param array $records
     * @param context $context
     */
    protected static function export_user(array $records, context $context)
    {
        foreach ($records as $key => $record):
            writer::with_context($context)->export_area_files([get_string('privacy:core_files', 'quizaccess_proctoring')], 'quizaccess_proctoring', 'picture',0)
                ->export_data([get_string('privacy:quizaccess_proctoring_logs', 'quizaccess_proctoring')], $record);
        endforeach;
    }

    /**
     * @param int $userid
     * @param context $context
     * @throws dml_exception
     */
    protected static function delete_user_data(int $userid, context $context)
    {
        global $DB;

        // Delete proctoring logs for this users.
        $DB->delete_records('quizaccess_proctoring_logs', ['userid' => $userid]);

        // Delete all of the files for this user.
        $fs = get_file_storage();
        $fs->delete_area_files($context->id, 'quizaccess_proctoring');

    }


}
