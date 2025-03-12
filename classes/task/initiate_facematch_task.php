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

namespace quizaccess_proctoring\task;

use core\task\scheduled_task;
use Exception;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');

/**
 * Scheduled task to synchronize user data for face matching.
 *
 * This class defines a task to automate face match initiation
 * during proctoring in quizzes.
 *
 * @package    quizaccess_proctoring
 * @author     Brain Station 23 Ltd <brainstation-23.com>
 * @copyright  2021 Brain Station 23 Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class initiate_facematch_task extends scheduled_task {

    /**
     * Returns the name of the task.
     *
     * @return string The task name.
     */
    public function get_name() {
        return get_string('initiate_facematch_task', 'quizaccess_proctoring');
    }

    /**
     * Updates meetings that are not expired.
     *
     * @return boolean
     */
    public function execute() {
        mtrace('Proctoring facematch task initiate starting');
        try {
            quizaccess_proctoring_log_facematch_task();
        } catch (Exception $exception) {
            mtrace('error in proctoring facematch task initiation: '.$exception->getMessage());
        }
        return true;
    }
}
