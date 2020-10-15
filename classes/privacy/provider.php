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
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_proctoring\privacy;

use coding_exception;
use context;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\core_userlist_provider;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;
use core_privacy\local\request\transform;
use dml_exception;

defined('MOODLE_INTERNAL') || die();

/**
 * provider
 */
class provider implements
    \core_privacy\local\metadata\provider,
    core_userlist_provider,
    \core_privacy\local\request\plugin\provider

{

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
        $params = ['context' => CONTEXT_MODULE, 'userid' => $userid];

        // Context in Quizaccess proctoring logs.
        $sql = "SELECT DISTINCT c.id
                  FROM {quizaccess_proctoring_logs} qpl
                  JOIN {context} c ON c.instanceid = qpl.quizid AND c.contextlevel = :context
                  WHERE qpl.userid = :userid
              GROUP BY c.id";
        $contextlist = new contextlist();
        $contextlist->add_from_sql($sql, $params);
        $fileparams = ['component' => 'quizaccess_proctoring', 'userid' => $userid];

        $sqlfile = "SELECT DISTINCT contextid as id
                    FROM {files}
                    WHERE component = :component
                    AND userid= :userid";
        $contextlist->add_from_sql($sqlfile, $fileparams);
        return $contextlist;
    }

    /**
     * Get the list of users within a specific context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        // The data is associated at the quiz module context level, so retrieve the user's context id.
        $sql = "SELECT DISTINCT qpl.userid AS userid
                  FROM {quizaccess_proctoring_logs} qpl
                  JOIN {course_modules} cm ON cm.id = qpl.quizid
                 WHERE cm.id = ?";
        $params = [$context->instanceid];
        $userlist->add_from_sql('userid', $sql, $params);

        $fileparams = ['component' => 'quizaccess_proctoring', 'contextid' => $context->id];
        $sqlfile = "SELECT DISTINCT userid
                    FROM {files}
                    WHERE component = :component
                    AND contextid= :contextid";
        $userlist->add_from_sql('userid', $sqlfile, $fileparams);
    }

    /**
     * Export personal data for the given approved_contextlist. User and context information is contained within the contextlist.
     * @param approved_contextlist $contextlist
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        // Get all cmids that correspond to the contexts for a user.
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel === CONTEXT_MODULE) {
                if ($context->instanceid) {
                    list($insql, $params) = $DB->get_in_or_equal($context->instanceid, SQL_PARAMS_NAMED);
                    $params['userid'] = $contextlist->get_user()->id;

                    // Quiz access proctoring logs.
                    $sql = "SELECT qpl.id as id,
                       qpl.courseid as courseid,
                       qpl.quizid as quizid,
                       qpl.userid as userid,
                       qpl.webcampicture as webcampicture,
                       qpl.status as status,
                       qpl.timemodified as timemodified
                  FROM {quizaccess_proctoring_logs} qpl
                 WHERE qpl.quizid {$insql} AND qpl.userid =:userid
                 ORDER BY qpl.id ASC";

                    $qaplogs = $DB->get_records_sql($sql, $params);
                    $index = 0;
                    foreach ($qaplogs as $qaplog) {
                        // Data export is organised in: {Context}/{Plugin Name}/{Table name}/{index}/data.json.
                        $index++;
                        $subcontext = [
                            get_string('quizaccess_proctoring', 'quizaccess_proctoring'),
                            'proctoring_logs',
                            $index
                        ];

                        $data = (object)[
                            'id' => $qaplog->id,
                            'courseid' => $qaplog->courseid,
                            'quizid' => $qaplog->quizid,
                            'userid' => $qaplog->userid,
                            'webcampicture' => $qaplog->webcampicture,
                            'status' => $qaplog->status,
                            'timemodified' => transform::datetime($qaplog->timemodified)
                        ];
                        $webcamepic = explode("/", "$qaplog->webcampicture");
                        $webcamepiclast = end($webcamepic);

                        $paramfile["userid"] = $qaplog->userid;
                        $paramfile["filename"] = $webcamepiclast;
                        if (!empty($webcamepiclast)) {
                            $userfiles = $DB->get_record('files', $paramfile);
                            writer::with_context($context)
                                ->export_area_files([get_string('privacy:core_files', 'quizaccess_proctoring')],
                                    'quizaccess_proctoring', 'picture', $userfiles->itemid
                                )->export_data($subcontext, $data);
                        } else {
                            writer::with_context($context)
                                ->export_data($subcontext, $data);
                        }

                    }
                }
            }
        }

    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context
     * @throws dml_exception
     */
    public static function delete_data_for_all_users_in_context(context $context) {
        global $DB;

        // Sanity check that context is at the module context level, then get the quizid.
        if ($context->contextlevel === CONTEXT_MODULE) {
            $cmid = $context->instanceid;
            $quizid = $DB->get_field('course_modules', 'instance', ['id' => $cmid]);

            $params['quizid'] = $quizid;
            $DB->set_field_select('quizaccess_proctoring_logs', 'userid', 0, "quizid = :quizid", $params);
        }

        // Delete all of the webcam images for this user.
        $fs = get_file_storage();
        $fs->delete_area_files($context->id, 'quizaccess_proctoring', 'picture');
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_userlist $userlist
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;
        $context = $userlist->get_context();

        // Sanity check that context is at the Module context level.
        if ($context->contextlevel !== CONTEXT_MODULE) {
            $userids = $userlist->get_userids();
            list($insql, $inparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);

            $DB->set_field_select('quizaccess_proctoring_logs', 'userid', 0, "userid {$insql}", $inparams);

            // Delete users file (webcam images).
            $filesql = "SELECT * FROM {files} WHERE userid {$insql}";
            $usersfile = $DB->get_records_sql($filesql, $inparams);

            $fs = get_file_storage();
            foreach ($usersfile as $file):
                $fs->delete_area_files($context->id, 'quizaccess_proctoring', 'picture', $file->id);
            endforeach;

        }
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param approved_contextlist $contextlist
     * @throws dml_exception
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        // If the user has data, then only the User context should be present so get the first context.
        $contexts = $contextlist->get_contexts();
        if (count($contexts) == 0) {
            return;
        }

        $params['userid'] = $contextlist->get_user()->id;
        $DB->set_field_select('quizaccess_proctoring_logs', 'userid', 0, "userid = :userid", $params);
        foreach ($contextlist as $context) {
            // Delete user file (webcam images).
            $userfiles = $DB->get_records('files', $params);
            $fs = get_file_storage();
            foreach ($userfiles as $file):
                $fs->delete_area_files($context->id, 'quizaccess_proctoring', 'picture', $file->itemid);
            endforeach;
        }
    }

}
