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
 * Unit tests for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace quizaccess_proctoring;
use advanced_testcase;


defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot.'/mod/workshop/lib.php'); // Include the code to test.

require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/rule.php');
require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');
require_once($CFG->dirroot.'/mod/workshop/locallib.php'); // Include the code to test.

/**
 * Unit tests for the quizaccess_proctoring plugin.
 *
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule_test extends advanced_testcase {
    /** @var stdClass Basic workshop data stored in an object. */
    protected $workshop;
    /** @var stdClass Generated Random Course. */
    protected $course;
    /** @var stdClass mod info */
    protected $cm;
    /** @var context Course module context. */
    protected $context;

    /**
     * Test case to check the rule basics.
     */
    public function test_proctoring_access_rule() {
        $quiz = new \stdClass();
        $cm = new \stdClass();
        $cm->id = 0;
        $quizobj = new \mod_quiz\quiz_settings($quiz, $cm, null);

        $rule = new \quizaccess_proctoring($quizobj, 0);
        $attempt = new \stdClass();

         $this->assertFalse($rule->prevent_access());
         $this->assertFalse($rule->prevent_new_attempt(0, $attempt));
        $this->assertFalse($rule->is_finished(0, $attempt));
        $this->assertFalse($rule->end_time($attempt));
        $this->assertFalse($rule->time_left_display($attempt, 0));
        $this->assertFalse($rule->attempt_must_be_in_popup());
    }

    /**
     * Test case to check if the proper message is producing form the empty object validation method.
     *
     * @throws coding_exception
     */
    public function test_validate_preflight_check() {
        $this->resetAfterTest();
        $quiz = new \stdClass();
        $cm = new \stdClass();
        $cm->id = 0;
        $quizobj = new \mod_quiz\quiz_settings($quiz, $cm, null);
        $rule = new \quizaccess_proctoring($quizobj, 0);
        $data['proctoring'] = '';
        $errors = $rule->validate_preflight_check($data, [], [], 0);
        $string = get_string('youmustagree', 'quizaccess_proctoring');

        $this->assertEquals($errors['proctoring'], $string);
    }

    /**
     * Test proctorin settings.
     *
     * @throws coding_exception
     */
    public function test_proctoring_settings() {
        global $DB, $CFG;
        $this->resetAfterTest();
        // Set the expected default values before running assertions.
        set_config('autoreconfigurecamshotdelay', '30', 'quizaccess_proctoring');
        set_config('autoreconfigureimagewidth', '230', 'quizaccess_proctoring');
        set_config('awschecknumber', '0', 'quizaccess_proctoring');
        set_config('awsfcthreshold', '80', 'quizaccess_proctoring');
        set_config('bsapi', '', 'quizaccess_proctoring');

        // Now run the assertions to test the settings.
        $this->assertEquals('30', quizaccess_proctoring_get_proctoring_settings('autoreconfigurecamshotdelay'));
        $this->assertEquals('230', quizaccess_proctoring_get_proctoring_settings('autoreconfigureimagewidth'));
        $this->assertEquals('0', quizaccess_proctoring_get_proctoring_settings('awschecknumber'));
        $this->assertEquals('80', quizaccess_proctoring_get_proctoring_settings('awsfcthreshold'));
        $this->assertEquals('', quizaccess_proctoring_get_proctoring_settings('bsapi'));
    }

    /*
     * Test save settings
     *
     * @throws coding_exception
     */
    public function test_make_modal_content() {
        global $DB;

        $quiz = new \stdClass();
        $cm = new \stdClass();
        $cm->id = 0;
        $quizobj = new \mod_quiz\quiz_settings($quiz, $cm, null);

        $attempt = new \stdClass();

        $rule = new \quizaccess_proctoring($quizobj, 0);

        $modalhtml = $rule->make_modal_content(null, '1', '1');

        $this->assertEquals(gettype($modalhtml), 'string');
    }
    /*
     * Test save settings
     *
     * @throws coding_exception
     */
    public function test_offlineattempts_access_rule() {
        $quiz = new \stdClass();
        $quiz->allowofflineattempts = 1;
        $cm = new \stdClass();
        $cm->id = 0;
        $quizobj = new \mod_quiz\quiz_settings($quiz, $cm, null);
        $rule = new \quizaccess_proctoring($quizobj, 0);
        $attempt = new \stdClass();

        $this->assertFalse($rule->prevent_access());
        $this->assertFalse($rule->prevent_new_attempt(0, $attempt));
        $this->assertFalse($rule->is_finished(0, $attempt));
        $this->assertFalse($rule->end_time($attempt));
        $this->assertFalse($rule->time_left_display($attempt, 0));
    }

}
