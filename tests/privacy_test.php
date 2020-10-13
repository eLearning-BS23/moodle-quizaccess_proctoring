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
 * Unit Test for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();
global $CFG;

use advanced_testcase;

require_once($CFG->dirroot . "/user/lib.php");

/**
 * Unit tests for core_user.
 *
 * @copyright  2018 Adrian Greeve <adrian@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_proctoring_privacy_testcase extends advanced_testcase {

    /**
     * Setup the user, the quiz and ensure that the user is the last user to modify the SEB quiz settings.
     */
    public function setup_test_data() {
        $this->resetAfterTest();

        $this->setAdminUser();

        $this->course = $this->getDataGenerator()->create_course();
        $this->quiz = $this->create_test_quiz($this->course, \quizaccess_seb\settings_provider::USE_SEB_CONFIG_MANUALLY);

        $this->user = $this->getDataGenerator()->create_user();
        $this->setUser($this->user);

        $template = $this->create_template();

        $quizsettings = quiz_settings::get_record(['quizid' => $this->quiz->id]);

        // Modify settings so usermodified is updated. This is the user data we are testing for.
        $quizsettings->set('requiresafeexambrowser', \quizaccess_seb\settings_provider::USE_SEB_TEMPLATE);
        $quizsettings->set('templateid', $template->get('id'));
        $quizsettings->save();

    }

    /**
     * Create user data for a user.
     *
     * @param  stdClass $user A user object.
     * @param  stdClass $course A course.
     */
    protected function create_data_for_user($user, $course) {
        global $DB;
        $this->resetAfterTest();
        // Last course access.
        $lastaccess = (object) [
            'userid' => $user->id,
            'courseid' => $course->id,
            'timeaccess' => time() - DAYSECS
        ];
        $DB->insert_record('user_lastaccess', $lastaccess);

        // Password history.
        $history = (object) [
            'userid' => $user->id,
            'hash' => 'HID098djJUU',
            'timecreated' => time()
        ];
        $DB->insert_record('user_password_history', $history);

        // Password resets.
        $passwordreset = (object) [
            'userid' => $user->id,
            'timerequested' => time(),
            'timererequested' => time(),
            'token' => $this->generate_random_string()
        ];
        $DB->insert_record('user_password_resets', $passwordreset);

        // User mobile devices.
        $userdevices = (object) [
            'userid' => $user->id,
            'appid' => 'com.moodle.moodlemobile',
            'name' => 'occam',
            'model' => 'Nexus 4',
            'platform' => 'Android',
            'version' => '4.2.2',
            'pushid' => 'kishUhd',
            'uuid' => 'KIhud7s',
            'timecreated' => time(),
            'timemodified' => time()
        ];
        $DB->insert_record('user_devices', $userdevices);

        // Course request.
        $courserequest = (object) [
            'fullname' => 'Test Course',
            'shortname' => 'TC',
            'summary' => 'Summary of course',
            'summaryformat' => 1,
            'category' => 1,
            'reason' => 'Because it would be nice.',
            'requester' => $user->id,
            'password' => ''
        ];
        $DB->insert_record('course_request', $courserequest);

        // User session table data.
        $usersessions = (object) [
            'state' => 0,
            'sid' => $this->generate_random_string(), // Needs a unique id.
            'userid' => $user->id,
            'sessdata' => 'Nothing',
            'timecreated' => time(),
            'timemodified' => time(),
            'firstip' => '0.0.0.0',
            'lastip' => '0.0.0.0'
        ];
        $DB->insert_record('sessions', $usersessions);
    }

    /**
     * Create a random string.
     *
     * @param  integer $length length of the string to generate.
     * @return string A random string.
     */
    protected function generate_random_string($length = 6) {
        $response = '';
        $source = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if ($length > 0) {

            $response = '';
            $source = str_split($source, 1);

            for ($i = 1; $i <= $length; $i++) {
                $num = mt_rand(1, count($source));
                $response .= $source[$num - 1];
            }
        }

        return $response;
    }
}
