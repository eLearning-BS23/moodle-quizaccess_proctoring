<?php
require_once (__DIR__ . '/../../../../config.php');
require_once ($CFG->dirroot . '/lib/tablelib.php');
require_once (__DIR__ . '/classes/addtional_settings_helper.php');

$cmid = required_param('cmid', PARAM_INT);
$username = optional_param('uname', '', PARAM_TEXT);
$email = optional_param('email', '', PARAM_TEXT);
$coursename = optional_param('coursename', '', PARAM_TEXT);
$quizname = optional_param('quizname', '', PARAM_TEXT);
$deleteidstring = optional_param('deleteidstring', '', PARAM_RAW);
$formtype = optional_param('form_type', '', PARAM_TEXT);
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
$PAGE->set_title('Proctoring logs');
$PAGE->set_heading('Proctoring Logs');

$PAGE->navbar->add('Proctoring Logs', $url);
$PAGE->requires->js_call_amd('quizaccess_proctoring/additionalSettings', 'setup',array());

echo $OUTPUT->header();
$formurl = new moodle_url('/mod/quiz/accessrule/proctoring/additional_settings.php');

echo '<form method="GET" id="my_form" action="'.$formurl.'">';
echo '<input type="hidden" id="cmid" name="cmid" value="'.$cmid.'">';
echo '<input type="hidden" id="deleteidstring" name="deleteidstring" value="">';
echo '<input type="hidden" name="form_type" value="Delete">';

$helper = new addtional_settings_helper();
if($formtype == 'Search'){
    $sqlexecuted = $helper->search($username,$email,$coursename,$quizname);
}
else if($formtype == 'Delete'){
    $helper->deleteLogs($deleteidstring);
    $url2 = new moodle_url(
        '/mod/quiz/accessrule/proctoring/additional_settings.php',
        array(
            'cmid' => $cmid
        )
    );
    redirect($url2, 'Images deleted!', -11);
}
else {
    // Prepare data.
    $sqlexecuted = array();//$helper->getAllData();

    echo '<div class="box generalbox m-b-1 adminerror alert alert-info p-y-1">Please search logs first to see data.</div>';
}

// Print report.
$table = new flexible_table('proctoring-report-' . $COURSE->id . '-' . $cmid);

$table->define_columns(array('Log Id','fullname', 'email', 'coursename','quizname','dateverified', 'actions'));
$table->define_headers(
    array(
        get_string('reportidheader', 'quizaccess_proctoring'),
        get_string('user'),
        get_string('email'),
        get_string('coursenameheader', 'quizaccess_proctoring'),
        get_string('quiznameheader', 'quizaccess_proctoring'),
        get_string('dateverified', 'quizaccess_proctoring'),
        get_string('actions', 'quizaccess_proctoring')
    )
);

$table->define_baseurl($url);

$table->set_attribute('cellpadding', '5');
$table->set_attribute('class', 'generaltable generalbox reporttable');
$table->setup();

////
$search_row = array();
$search_row[] = 'Select All &nbsp<input type="checkbox" id="select_all" name="select_all" value="0">
                 <br/>
                 <button id="delete_select_btn" onclick="return confirm(`Are you sure want to delete ?`)"  style="display: none">Delete</button>';
$search_row[] = '<input type="text" placeholder="user name" id="uname" name="uname" value="'.$username.'">';
$search_row[] = '<input type="text" placeholder="email" id="email" name="email" value="'.$email.'">';
$search_row[] = '<input type="text" placeholder="coursename" id="coursename" name="coursename" value="'.$coursename.'">';
$search_row[] = '<input type="text" placeholder="quizname" id="quizname" name="quizname" value="'.$quizname.'">';
$search_row[] = '';
$search_row[] = '<input type="submit" name="form_type" value="Search">';
$table->add_data($search_row);

foreach ($sqlexecuted as $info) {
    $report_url = new moodle_url('/mod/quiz/accessrule/proctoring/report.php');
    $folder_btn = '<a target="_blank" href="'.$report_url.'?courseid=' . $info->courseid .
        '&quizid=' . $info->quizid . '&cmid=' . $cmid . '&studentid=' . $info->studentid . '&reportid=' . $info->reportid . '">' .
        '<i class="icon fa fa-folder-o fa-fw "></i>' . '</a>';


    $data = array();
    $data[] = $info->reportid.'<input type="checkbox" class ="reportIdChkBox" value="'.$info->reportid.'">';
    $data[] = $info->firstname . ' ' . $info->lastname;
    $data[] = $info->email;
    $data[] = $info->coursename;
    $data[] = $info->quizname;
    $data[] = date("Y/M/d H:m:s", $info->timemodified);
    $data[] = $folder_btn;
    $table->add_data($data);
}
$table->finish_html();
echo "</form>";

//echo "success";
echo $OUTPUT->footer();