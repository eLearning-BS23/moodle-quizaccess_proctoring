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
 * Proctoring Summary for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/lib/tablelib.php');
require_once(__DIR__ . '/classes/additional_settings_helper.php');

// Ensure that all required parameters are present.
$cmid = required_param('cmid', PARAM_INT);  // Course module ID.

// Get the context and check the user's capabilities.
$context = context_module::instance($cmid, MUST_EXIST);
require_capability('quizaccess/proctoring:viewreport', $context);

// Get course and module information.
list($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');
require_login($course, true, $cm);

// Define the URL for the page.
$params = ['cmid' => $cmid];
$url = new moodle_url('/mod/quiz/accessrule/proctoring/proctoringsummary.php', $params);

// Set page metadata.
$PAGE->set_url($url);
$PAGE->set_title(get_string('course_proctoring_summary', 'quizaccess_proctoring'));
$PAGE->set_heading(get_string('proctoring_pro_promo_heading', 'quizaccess_proctoring'));

// Add navigation and modal initialization.
$PAGE->navbar->add(get_string('quizaccess_proctoring', 'quizaccess_proctoring'),
       new moodle_url('/mod/quiz/accessrule/proctoring/report.php', ['cmid' => $cmid, 'courseid' => $course->id]));
$PAGE->navbar->add(get_string('proctoring_report', 'quizaccess_proctoring'), $url);

$PAGE->requires->js_call_amd('core/modal', 'init', []); // Initialize modal system.

echo $OUTPUT->header();

// SQL query for course-wise summary.
$coursewisesummarysql = '
    SELECT MC.fullname AS coursefullname,
           MC.shortname AS courseshortname,
           MQL.courseid,
           COUNT(MQL.id) AS logcount
      FROM {quizaccess_proctoring_logs} MQL
      JOIN {course} MC ON MQL.courseid = MC.id
     WHERE MQL.courseid = :courseid
     GROUP BY courseid, coursefullname, courseshortname
';
$coursesummary = $DB->get_records_sql($coursewisesummarysql, ['courseid' => $course->id]);

// SQL query for quiz-wise summary.
$quizsummarysql = '
    SELECT CM.id AS quizid,
           MQ.name,
           MQL.courseid,
           COUNT(MQL.webcampicture) AS camshotcount
      FROM {quizaccess_proctoring_logs} MQL
      JOIN {course_modules} CM ON MQL.quizid = CM.id
      JOIN {quiz} MQ ON CM.instance = MQ.id
     WHERE COALESCE(TRIM(MQL.webcampicture), \'\') != \'\'
       AND MQL.courseid = :courseid
     GROUP BY CM.id, MQ.id, MQ.name, MQL.courseid
';
$quizsummary = $DB->get_records_sql($quizsummarysql, ['courseid' => $course->id]);

// Get the description for the summary page.
$summarypagedesc = get_string('summarypagedesc', 'quizaccess_proctoring');

// Prepare renderable object for the template.
$renderable = new stdClass();
$renderable->summarypagedesc = $summarypagedesc;
$renderable->coursesummary = [];

foreach ($coursesummary as $course) {
    $coursedata = new stdClass();
    $coursedata->coursefullname = $course->coursefullname;
    $coursedata->courseshortname = $course->courseshortname;

    // Create a URL for course deletion with sesskey.
    $coursedata->url_course_delete = new moodle_url(
        '/mod/quiz/accessrule/proctoring/bulkdelete.php',
        ['cmid' => $cmid, 'type' => 'course', 'id' => $course->courseid, 'sesskey' => sesskey()]
    );
    $coursedata->url_course_delete = $coursedata->url_course_delete->out(false);
    // Filter quiz summary data for the current course.
    $coursedata->quizsummary = [];
    foreach ($quizsummary as $quiz) {
        if ($course->courseid == $quiz->courseid) {
            $quizdata = new stdClass();
            $quizdata->name = $quiz->name;
            $quizdata->camshotcount = $quiz->camshotcount;

            // Create a URL for quiz deletion with sesskey.
            $quizdata->url_quiz_delete = new moodle_url(
                '/mod/quiz/accessrule/proctoring/bulkdelete.php',
                ['cmid' => $cmid, 'type' => 'quiz', 'id' => $quiz->quizid, 'sesskey' => sesskey()]
            );
            $quizdata->url_quiz_delete = $quizdata->url_quiz_delete->out(false);

            $coursedata->quizsummary[] = $quizdata;
        }
    }

    $renderable->coursesummary[] = $coursedata;
}

// Render the template.
echo $OUTPUT->render_from_template('quizaccess_proctoring/proctoring_summary', $renderable);
echo $OUTPUT->footer();
