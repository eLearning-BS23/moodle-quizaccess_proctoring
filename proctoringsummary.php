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
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/lib/tablelib.php');
require_once(__DIR__ . '/classes/AdditionalSettingsHelper.php');

const TD_CLASS_NO_BORDER = '<td class="no-border">';
const TD = "</td>";

$cmid = required_param('cmid', PARAM_INT);
$context = context_module::instance($cmid, MUST_EXIST);
require_capability('quizaccess/proctoring:deletecamshots', $context);

$params = array(
    'cmid' => $cmid
);
$url = new moodle_url(
    '/mod/quiz/accessrule/proctoring/proctoringsummary.php',
    $params
);

list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');

require_login($course, true, $cm);

$PAGE->set_url($url);
$PAGE->set_title('Proctoring Summary Report');
$PAGE->set_heading('Proctoring Summary Report');

$PAGE->navbar->add('Proctoring Report', $url);
$PAGE->requires->js_call_amd('quizaccess_proctoring/additionalSettings', 'setup', array());

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


$quizsummarysql = ' SELECT '
                .' CM.id as quizid, '
                .' MQ.name, '
                .' MQL.courseid, '
                .' COUNT(MQL.id) as camshotcount '
                .' FROM {quizaccess_proctoring_logs} MQL '
                .' JOIN {course_modules} CM ON MQL.quizid = CM.id '
                .' JOIN {quiz} MQ ON CM.instance = MQ.id '
                .' GROUP BY CM.id,MQ.id,MQ.name,MQL.courseid ';
$quizsummary = $DB->get_records_sql($quizsummarysql);

echo '<div class="box generalbox m-b-1 adminerror alert alert-info p-y-1">'
    . get_string('summarypagedesc', 'quizaccess_proctoring') . '</div>';

echo '<table class="flexible table table_class">
        <thead>
            <th colspan="2">Course Name / Quiz Name</th>
            <th>Number of images</th>
            <th>Delete</th>
        </thead>';

echo '<tbody>';

foreach ($coursesummary as $row) {
    $params1 = array(
        'cmid' => $cmid,
        'type' => 'course',
        'id' => $row->courseid
    );
    $url1 = new moodle_url(
        '/mod/quiz/accessrule/proctoring/bulkdelete.php',
        $params1
    );
    $con = "return confirm('Are you sure want to delete the pictures for this course?');";
    $deletelink1 = '<a onclick="'. $con .'"
    href="'.$url1.'"><i class="icon fa fa-trash fa-fw "></i></a>';

    echo '<tr class="course-row no-border">';
    echo '<td colspan="4" class="no-border">'.$row->courseshortname.":".$row->coursefullname. TD;

    echo TD_CLASS_NO_BORDER .$deletelink1. TD;
    echo '</tr>';

    foreach ($quizsummary as $row2) {
        if ($row->courseid == $row2->courseid) {
            $params2 = array(
                'cmid' => $cmid,
                'type' => 'quiz',
                'id' => $row2->quizid
            );
            $url2 = new moodle_url(
                '/mod/quiz/accessrule/proctoring/bulkdelete.php',
                $params2
            );
            $con2 = "return confirm('Are you sure want to delete the pictures for this quiz?');";
            $deletelink2 = '<a onclick="'. $con2 .'"
            href="'.$url2.'"><i class="icon fa fa-trash fa-fw "></i></a>';

            echo '<tr class="quiz-row">';
            echo '<td width="5%" class="no-border"></td>';
            echo TD_CLASS_NO_BORDER .$row2->name. TD;
            echo TD_CLASS_NO_BORDER .$row2->camshotcount. TD;
            echo TD_CLASS_NO_BORDER .$deletelink2. TD;
            echo '</tr>';
        }
    }
}
echo '</tbody></table>';

echo '<style>'
.'.table_class{ font-family: arial, sans-serif; border-collapse: collapse; width: 100%;}'
.'.course-row{ background-color: #dddddd; border: none;}'
.'.quiz-row{ background-color: #ffffff; border: none;}'
.'.no-border{ border: none !important; border-top: none !important;}'
.'</style>';
