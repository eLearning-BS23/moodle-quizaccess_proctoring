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

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;

defined('MOODLE_INTERNAL') || die();

/**
 * provider
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider {

    public static function get_contexts_for_userid(int $userid): contextlist
    {
        // TODO: Implement get_contexts_for_userid() method.
    }

    public static function export_user_data(approved_contextlist $contextlist)
    {
        // TODO: Implement export_user_data() method.
    }

    public static function delete_data_for_all_users_in_context(\context $context)
    {
        // TODO: Implement delete_data_for_all_users_in_context() method.
    }

    public static function delete_data_for_user(approved_contextlist $contextlist)
    {
        // TODO: Implement delete_data_for_user() method.
    }

    /**
     * Provides meta data that is stored about a user with quizaccess_proctoring
     *
     * @param  collection $collection A collection of meta data items to be added to.
     * @return  collection Returns the collection of metadata.
     */
    public static function get_metadata(collection $collection): collection
    {
        $quizaccess_proctoring_logs = [
            'courseid' => 'privacy:metadata:courseid',
            'quizid' => 'privacy:metadata:quizid',
            'userid' => 'privacy:metadata:userid',
            'webcampicture' => 'privacy:metadata:webcampicture',
            'status' => 'privacy:metadata:status',
            'timemodified' => 'timemodified',
        ];

        $collection->add_database_table('quizaccess_proctoring_logs', $quizaccess_proctoring_logs, 'privacy:metadata:quizaccess_proctoring_logs');

        return $collection;
    }
}
