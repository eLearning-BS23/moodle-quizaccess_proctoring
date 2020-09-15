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
 * Implementaton for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23 <moodle@brainstation-23.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');

$CFG->cachejs = false;

class quizaccess_proctoring extends quiz_access_rule_base
{

    public function is_preflight_check_required($attemptid) {
        return empty($attemptid);
    }

    public function add_preflight_check_form_fields(mod_quiz_preflight_check_form $quizform, MoodleQuickForm $mform, $attemptid){
        $mform->addElement('header', 'proctoringheader', get_string('proctoringheader', 'quizaccess_proctoring'));
        $mform->addElement('static', 'proctoringmessage', '', get_string('proctoringstatement', 'quizaccess_proctoring'));
        $mform->addElement('static', 'cammessage', '', get_string('camhtml', 'quizaccess_proctoring'));
        $mform->addElement('checkbox', 'proctoring', '', get_string('proctoringlabel', 'quizaccess_proctoring'));
    }

    public function validate_preflight_check($data, $files, $errors, $attemptid) {
        if (empty($data['proctoring'])) {
            $errors['proctoring'] = get_string('youmustagree', 'quizaccess_proctoring');
        }

        return $errors;
    }

    public static function make(quiz $quizobj, $timenow, $canignoretimelimits) {
        if (empty($quizobj->get_quiz()->proctoringrequired)) {
            return null;
        }
        return new self($quizobj, $timenow);
    }

    public static function add_settings_form_fields(mod_quiz_mod_form $quizform, MoodleQuickForm $mform){
        $mform->addElement('select', 'proctoringrequired',
            get_string('proctoringrequired', 'quizaccess_proctoring'),
            array(
                0 => get_string('notrequired', 'quizaccess_proctoring'),
                1 => get_string('proctoringrequiredoption', 'quizaccess_proctoring'),
            ));
        $mform->addHelpButton('proctoringrequired', 'proctoringrequired', 'quizaccess_proctoring');
    }

    public static function save_settings($quiz) {
        global $DB;
        if (empty($quiz->proctoringrequired)) {
            $DB->delete_records('quizaccess_proctoring', array('quizid' => $quiz->id));
        } else {
            if (!$DB->record_exists('quizaccess_proctoring', array('quizid' => $quiz->id))) {
                $record = new stdClass();
                $record->quizid = $quiz->id;
                $record->proctoringrequired = 1;
                $DB->insert_record('quizaccess_proctoring', $record);
            }
        }
    }

    public static function delete_settings($quiz) {
        global $DB;
        $DB->delete_records('quizaccess_proctoring', array('quizid' => $quiz->id));
    }

    public static function get_settings_sql($quizid) {
        return array(
            'proctoringrequired',
            'LEFT JOIN {quizaccess_proctoring} proctoring ON proctoring.quizid = quiz.id',
            array());
    }

    public function description() {
        $cmid = optional_param('id', '', PARAM_INT);

        global $DB, $PAGE, $COURSE, $USER;
        $contextquiz = $DB->get_record('course_modules', array('id' => $cmid));

        $record = new stdClass();
        $record->courseid = $COURSE->id;
        $record->quizid = $contextquiz->id;
        $record->userid = $USER->id;
        $record->webcampicture = '';
        $record->status = 1;
        $record->timemodified = time();
        $record->id = $DB->insert_record('quizaccess_proctoring_logs', $record, true);

        $PAGE->requires->js_call_amd('quizaccess_proctoring/proctoring', 'init', array($record));
        $messages = [get_string('proctoringheader', 'quizaccess_proctoring')];

        return $messages;
    }

    public function setup_attempt_page($page) {
        $cmid = optional_param('cmid', '', PARAM_INT);
        $attempt = optional_param('attempt', '', PARAM_INT);

        $page->set_title($this->quizobj->get_course()->shortname . ': ' . $page->title);
        $page->set_popup_notification_allowed(false); // Prevent message notifications.
        $page->set_heading($page->title);
        global $DB, $COURSE, $USER;

        $contextquiz = $DB->get_record('course_modules', array('id' => $cmid));

        $record = new stdClass();
        $record->courseid = $COURSE->id;
        $record->quizid = $contextquiz->id;
        $record->userid = $USER->id;
        $record->webcampicture = '';
        $record->status = $attempt;
        $record->timemodified = time();
        $record->id = $DB->insert_record('quizaccess_proctoring_logs', $record, true);
        $page->requires->js_call_amd('quizaccess_proctoring/proctoring', 'setup', array($record));
    }

}
