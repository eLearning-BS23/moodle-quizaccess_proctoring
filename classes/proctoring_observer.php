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
 * Observer for the quizaccess_proctoring plugin.
 *
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_proctoring;


/**
 * proctoring_observer class.
 *
 * @copyright  2020 Brain Station 23
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class proctoring_observer {
    /**
     * handle quiz attempt started.
     *
     * @return void
     */
    public static function handle_quiz_attempt_started(\mod_quiz\event\attempt_started $event) {
        self::update_event_data($event);
    }

    /**
     * handle quiz attempt submitted.
     *
     * @return void
     */
    public static function handle_quiz_attempt_submitted(\mod_quiz\event\quiz_attempt_submitted $event) {
        self::update_event_data($event);
    }

    /**
     * take_screenshot.
     *
     * @param take_screensho $event
     */
    public static function take_screenshot(\quizaccess_proctoring\take_screensho $event) {
        global $DB;
        $record = $event->get_record_snapshot('quizaccess_proctoring_logs', $event->objectid);
        $DB->update_record('quizaccess_proctoring_logs', $record);
    }

    private static function update_event_data($event) {
        global $DB;
        $DB->update_record('quizaccess_proctoring_logs', $event);
    }
}
