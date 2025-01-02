<?php
// This file is part of Moodle - http://moodle.org/
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
 * Proctoring Summary for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/lib/tablelib.php');
require_once(__DIR__ . '/classes/AdditionalSettingsHelper.php');

$cmid = required_param('cmid', PARAM_INT);
$context = context_module::instance($cmid, MUST_EXIST);
has_capability('quizaccess/proctoring:deletecamshots', $context);

$params = array(
    'cmid' => $cmid,
);
$url = new moodle_url('/mod/quiz/accessrule/proctoring/proctoringsummary.php', $params);

list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');

require_login($course, true, $cm);

$PAGE->set_url($url);
$PAGE->set_title(get_string('proctoring_summary_report', 'quizaccess_proctoring'));
$PAGE->set_heading(get_string('proctoring_summary_report', 'quizaccess_proctoring'));

$PAGE->navbar->add(get_string('proctoring_report', 'quizaccess_proctoring'), $url);
$PAGE->requires->js_call_amd('core/modal', 'init', array()); // Initialize the modal system

echo $OUTPUT->header();

$coursewisesummarysql = ' SELECT '
                        .' MC.fullname as coursefullname, '
                        .' MC.shortname as courseshortname, '
                        .' MQL.courseid, '
                        .' COUNT(MQL.id) as logcount '
                        .' FROM {quizaccess_proctoring_logs} MQL '
                        .' JOIN {course} MC ON MQL.courseid = MC.id '
                        .' GROUP BY courseid,coursefullname,courseshortname ';
$coursesummary = $DB->get_records_sql($coursewisesummarysql);

$quizsummarysql = 'SELECT '
                 . 'CM.id AS quizid, '
                 . 'MQ.name, '
                 . 'MQL.courseid, '
                 . 'COUNT(MQL.webcampicture) AS camshotcount '
                 . 'FROM {quizaccess_proctoring_logs} MQL '
                 . 'JOIN {course_modules} CM ON MQL.quizid = CM.id '
                 . 'JOIN {quiz} MQ ON CM.instance = MQ.id '
                 . 'WHERE COALESCE(TRIM(MQL.webcampicture), \'\') != \'\' '
                 . 'GROUP BY CM.id, MQ.id, MQ.name, MQL.courseid';
$quizsummary = $DB->get_records_sql($quizsummarysql);

$summarypagedesc = get_string('summarypagedesc', 'quizaccess_proctoring');

$renderable = new stdClass();
$renderable->summarypagedesc = $summarypagedesc;
$renderable->coursesummary = [];
$renderable->quizsummary = $quizsummary;

foreach ($coursesummary as $course) {
    $course_data = new stdClass();
    $course_data->coursefullname = $course->coursefullname;
    $course_data->courseshortname = $course->courseshortname;
    $course_data->url_course_delete = new moodle_url('/mod/quiz/accessrule/proctoring/bulkdelete.php', ['cmid' => $cmid, 'type' => 'course', 'id' => $course->courseid]);
    // Convert to a properly encoded URL string.
    $course_data->url_course_delete = $course_data->url_course_delete->out(false);
    $course_data->quizsummary = [];

    foreach ($quizsummary as $quiz) {
        if ($course->courseid == $quiz->courseid) {
            $quiz_data = new stdClass();
            $quiz_data->name = $quiz->name;
            $quiz_data->camshotcount = $quiz->camshotcount;
            $quiz_data->url_quiz_delete = new moodle_url('/mod/quiz/accessrule/proctoring/bulkdelete.php', ['cmid' => $cmid, 'type' => 'quiz', 'id' => $quiz->quizid]);
            // Convert to a properly encoded URL string.
            $quiz_data->url_quiz_delete = $quiz_data->url_quiz_delete->out(false);
            $course_data->quizsummary[] = $quiz_data;
        }
    }

    $renderable->coursesummary[] = $course_data;
}

echo $OUTPUT->render_from_template('quizaccess_proctoring/proctoring_summary', $renderable);

echo $OUTPUT->footer();
