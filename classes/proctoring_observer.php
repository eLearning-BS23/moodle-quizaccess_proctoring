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
 * This class listens for events related to quiz attempts, such as starting or submitting a quiz attempt.
 * It also handles specific actions related to proctoring events like taking a screenshot and updating logs.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_proctoring;

/**
 * quizaccess_proctoring_observer class.
 *
 * This class defines the observer methods that handle specific quiz events like attempt start and attempt submission.
 * It also handles proctoring actions such as taking screenshots and updating related logs.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_proctoring_observer {
    /**
     * Handle the event when a quiz attempt is started.
     *
     * This method listens to the quiz attempt start event and updates the proctoring event data.
     *
     * @param \mod_quiz\event\attempt_started $event The event object representing the quiz attempt start.
     * @return void
     */
    public static function handle_quiz_attempt_started(\mod_quiz\event\attempt_started $event) {
        self::update_event_data($event);
    }

    /**
     * Handle the event when a quiz attempt is submitted.
     *
     * This method listens to the quiz attempt submission event and updates the proctoring event data.
     *
     * @param \mod_quiz\event\quiz_attempt_submitted $event The event object representing the quiz attempt submission.
     * @return void
     */
    public static function handle_quiz_attempt_submitted(\mod_quiz\event\quiz_attempt_submitted $event) {
        self::update_event_data($event);
    }

    /**
     * Take a screenshot during the proctoring process.
     *
     * This method listens to the screenshot event and updates the corresponding record in the proctoring logs.
     *
     * @param \quizaccess_proctoring\take_screensho $event The event object representing a screenshot action.
     * @return void
     */
    public static function take_screenshot(\quizaccess_proctoring\take_screensho $event) {
        global $DB;
        $record = $event->get_record_snapshot('quizaccess_proctoring_logs', $event->objectid);
        $DB->update_record('quizaccess_proctoring_logs', $record);
    }

    /**
     * Update logs of proctoring events.
     *
     * This method updates the proctoring event data in the logs table.
     *
     * @param \mod_quiz\event\attempt_started|\mod_quiz\event\quiz_attempt_submitted
     * $event The event object representing a quiz event.
     * @return void
     */
    private static function update_event_data($event) {
        global $DB;
        $DB->update_record('quizaccess_proctoring_logs', $event);
    }
}
