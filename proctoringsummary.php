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
$sort = optional_param('tsort', '', PARAM_ALPHANUMEXT);
$dir = optional_param('tdir', 4, PARAM_INT);

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
$PAGE->navbar->add(
    get_string('quizaccess_proctoring', 'quizaccess_proctoring'),
    new moodle_url('/mod/quiz/accessrule/proctoring/report.php', ['cmid' => $cmid, 'courseid' => $course->id])
);
$PAGE->navbar->add(get_string('proctoring_report', 'quizaccess_proctoring'), $url);

echo $OUTPUT->header();

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

// Validate and Determine the sorting direction.
$direction = ($dir == 4 || $dir == 0) ? 'ASC' : 'DESC';

// Validate the sort parameter.
$sortsql = '';
if (!in_array($sort, ['quiztitle', 'numberofimages'])) {
    // If the sort parameter is invalid, default to sorting by quiztitle.
    $sortsql = 'MQ.name';
} else {
    if ($sort === 'quiztitle') {
        // Sort by quiz title.
        $sortsql = 'MQ.name';
    } else if ($sort === 'numberofimages') {
        // Sort by number of images.
        $sortsql = 'camshotcount';
    }
}

// Complete the SQL query with sorting.
$quizsummarysql .= " ORDER BY $sortsql $direction";

$quizsummary = $DB->get_records_sql($quizsummarysql, ['courseid' => $course->id]);

// Create a flexible table instance for displaying user data.
$table = new flexible_table('quizaccess_proctoring_summary_table');
$table->define_columns(['quiztitle', 'numberofimages', 'action']);
$table->define_headers([
    get_string('quiztitle', 'quizaccess_proctoring'),
    get_string('numberofimages', 'quizaccess_proctoring'),
    get_string('action'),
]);

// Additional settings for the table.
$table->define_baseurl($PAGE->url);
$table->set_attribute('class', 'generaltable generalbox');
$table->set_attribute('id', 'quizaccess_proctoring_summary_table');
$table->sortable(true); // Sortable by quiz title.
$table->no_sorting('action'); // Actions column should not be sortable.
$table->setup();

// Filter quiz summary data for the current course.
foreach ($quizsummary as $quiz) {
    if ($course->id == $quiz->courseid) {
        $row = [];
        $row[] = $quiz->name;
        $row[] = $quiz->camshotcount;

        // Prepare the action menu for each user.
        $actionmenu = new action_menu();

        // Add Delete action to the action menu.
        $deleteimageurl = new moodle_url('/mod/quiz/accessrule/proctoring/bulkdelete.php', [
            'cmid' => $cmid,
            'type' => 'quiz',
            'id' => $quiz->quizid,
            'sesskey' => sesskey(),
        ]);

        // Prepare attributes for the delete action.
        $attributes = [
            'data-confirmation' => 'modal',
            'data-confirmation-type' => 'delete',
            'data-confirmation-title-str' => json_encode(['delete', 'core']),
            'data-confirmation-content-str' => json_encode(['confirmdeletionquiz', 'quizaccess_proctoring']),
            'data-confirmation-yes-button-str' => json_encode(['delete', 'core']),
            'data-confirmation-action-url' => $deleteimageurl->out(false),
            'data-confirmation-destination' => $deleteimageurl->out(false),
            'class' => 'text-danger',
        ];

        $deleteaction = new action_menu_link_primary(
            $deleteimageurl,
            new pix_icon('t/delete', get_string('delete')),
            get_string('delete'),
            $attributes
        );

        $actionmenu->add($deleteaction);

        $row[] = $OUTPUT->render($actionmenu);

        $table->add_data($row);
    }
}

echo html_writer::tag('button', get_string('back', 'quizaccess_proctoring'), [
    'type' => 'button',
    'class' => 'btn btn-secondary mb-3',
    'onclick' => 'window.history.back();',
]);

// Show the description for the summary page.
echo html_writer::tag('p', get_string('summarypagedesc', 'quizaccess_proctoring'));

$exists = $DB->record_exists('quizaccess_proctoring_logs', ['courseid' => $course->id, 'deletionprogress' => 0]);
if ($exists) {
    // Create a URL for course deletion with sesskey.
    $courseimagedeleteurl = new moodle_url('/mod/quiz/accessrule/proctoring/bulkdelete.php', [
        'cmid' => $cmid,
        'type' => 'course',
        'id' => $course->id,
        'sesskey' => sesskey(),
    ]);

    // Box containing the delete all images for a particular course.
    $deleteicon = html_writer::tag('i', '', ['class' => 'fa fa-trash mr-2']);
    $deletealltext = get_string('settingscontroll:deleteallcourseimage', 'quizaccess_proctoring');
    $deletealllinktext = get_string('settingscontroll:deletealllinktext', 'quizaccess_proctoring');
    $deletealllink = html_writer::tag('button', $deletealllinktext, [
        'class' => 'btn btn-danger',
        'data-confirmation' => 'modal',
        'data-confirmation-type' => 'delete',
        'data-confirmation-title-str' => json_encode(["delete", "core"]),
        'data-confirmation-content-str' => json_encode(["areyousure_delete_all_course_record", "quizaccess_proctoring"]),
        'data-confirmation-yes-button-str' => json_encode(["delete", "core"]),
        'data-confirmation-action-url' => $courseimagedeleteurl->out(false),
        'data-confirmation-destination' => $courseimagedeleteurl->out(false),
    ]);

    echo html_writer::div($deleteicon . ' ' . $deletealltext . ' ' . $deletealllink, 'mb-5');
}

// Render the table.
$table->print_html();
echo $OUTPUT->footer();
