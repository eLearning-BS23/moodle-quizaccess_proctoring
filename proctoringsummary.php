<?php
require_once (__DIR__ . '/../../../../config.php');
require_once ($CFG->dirroot . '/lib/tablelib.php');
require_once (__DIR__ . '/classes/addtional_settings_helper.php');

$cmid = required_param('cmid', PARAM_INT);
$context = context_module::instance($cmid, MUST_EXIST);
require_capability('quizaccess/proctoring:deletecamshots', $context);

$params = array(
    'cmid' => $cmid
);
$url = new moodle_url(
    '/mod/quiz/accessrule/proctoring/additional_settings.php',
    $params
);

list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');

require_login($course, true, $cm);

$PAGE->set_url($url);
$PAGE->set_title('Proctoring:Settings');
$PAGE->set_heading('Proctoring Log Summary');

$PAGE->navbar->add('Proctoring: Settings', $url);
$PAGE->requires->js_call_amd('quizaccess_proctoring/additionalSettings', 'setup',array());

echo $OUTPUT->header();

$coursewisesummarysql = 'SELECT
                        MC.fullname as coursefullname,
                        MC.shortname as courseshortname,
                        MQL.courseid,
                        COUNT(MQL.id) as logcount
                        FROM {quizaccess_proctoring_logs} MQL
                        JOIN {course} MC ON MQL.courseid = MC.id  
                        GROUP BY courseid,coursefullname,courseshortname';
$coursesummary = $DB->get_records_sql($coursewisesummarysql);


$quizsummarysql = 'SELECT
                    CM.id as quizid,
                    MQ.name,
                    MQL.courseid,
                    COUNT(MQL.id) as logcount
                    FROM mdl_quizaccess_proctoring_logs MQL
                    JOIN mdl_course_modules CM ON MQL.quizid = CM.id
                    JOIN mdl_quiz MQ ON CM.instance = MQ.id
                    GROUP BY MQ.id';
$quizsummary = $DB->get_records_sql($quizsummarysql);

echo '<table class="flexible table table-striped table-hover generaltable generalbox reporttable">
        <thead>
            <th colspan="2" style="text-align: center">Enity</th>
            <th>Image Count</th>
            <th>Action</th>
        </thead>';

echo '<tbody>';

foreach ($coursesummary as $row){
    $params1 = array(
        'cmid' => $cmid,
        'type' => 'course',
        'id' => $row->courseid
    );
    $url1 = new moodle_url(
        '/mod/quiz/accessrule/proctoring/bulkdelete.php',
        $params1
    );
    $deletelink1 = '<a class="btn btn-danger" href="'.$url1.'">Delete</a>';

    echo '<tr>';
    echo '<td>'.$row->courseshortname.":".$row->coursefullname."</td>";
//    echo '<td>'.$row->coursefullname."</td>";
    echo '<td>'."</td>";
    echo '<td>'.$row->logcount."</td>";
    echo '<td>'.$deletelink1."</td>";
    echo '</tr>';

    foreach ($quizsummary as $row2){
        if($row->courseid == $row2->courseid){
            $params2 = array(
                'cmid' => $cmid,
                'type' => 'quiz',
                'id' => $row2->quizid
            );
            $url2 = new moodle_url(
                '/mod/quiz/accessrule/proctoring/bulkdelete.php',
                $params2
            );
            $deletelink2 = '<a class="btn btn-danger" href="'.$url2.'">Delete</a>';

            echo '<tr>';
            echo '<td></td>';
            echo '<td>'.$row2->name."</td>";
            echo '<td>'.$row2->logcount."</td>";
            echo '<td>'.$deletelink2."</td>";
            echo '</tr>';
        }
    }
}
echo '</tbody></table>';