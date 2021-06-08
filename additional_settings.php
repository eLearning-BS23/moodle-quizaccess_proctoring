<?php
require_once (__DIR__ . '/../../../../config.php');
require_once ($CFG->dirroot . '/lib/tablelib.php');
$cmid = required_param('cmid', PARAM_INT);
$username = optional_param('username', '', PARAM_TEXT);
$email = optional_param('email', '', PARAM_TEXT);
$coursename = optional_param('coursename', '', PARAM_TEXT);
$quizname = optional_param('quizname', '', PARAM_TEXT);
$deleteidstring = optional_param('deleteidstring', '', PARAM_RAW);
$formtype = optional_param('form_type', '', PARAM_TEXT);
$context = context_module::instance($cmid, MUST_EXIST);

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
$PAGE->set_heading('Additional Admin Settings');

$PAGE->navbar->add('Proctoring: Settings', $url);
$PAGE->requires->js_call_amd('quizaccess_proctoring/additionalSettings', 'setup',array());

echo $OUTPUT->header();
$formurl = new moodle_url('/mod/quiz/accessrule/proctoring/additional_settings.php');

echo '<form method="GET" id="my_form" action="'.$formurl.'">';
echo '<input type="hidden" id="cmid" name="cmid" value="'.$cmid.'">';
echo '<input type="hidden" id="deleteidstring" name="deleteidstring" value="">';
echo '<input type="hidden" id="deleteidstring" name="form_type" value="Delete">';

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


if($formtype == 'Search'){
    $params = array();
    $whereClauseArray = array();

    if($username!==""){
        $name_like = "( ( ".$DB->sql_like('u.firstname', ':firstnamelike', false).") OR (".$DB->sql_like('u.lastname', ':lastnamelike', false)." )) ";
        array_push($whereClauseArray,$name_like);
        $params['firstnamelike'] = $username;
        $params['lastnamelike'] = $username;
    }

    if($email!==""){
        $email_like = " ( ".$DB->sql_like('u.email', ':emaillike', false)." ) ";
        array_push($whereClauseArray,$email_like);
        $params['emaillike'] = $email;
    }

    if($coursename!==""){
        $coursename_like = " ( ".$DB->sql_like('c.fullname', ':coursenamelike', false)." ) ";
        array_push($whereClauseArray,$coursename_like);
        $params['coursenamelike'] = $coursename;
    }

    if($quizname!==""){
        $quizname_like = " ( ".$DB->sql_like('q.name', ':quiznamelike', false)." ) ";
        array_push($whereClauseArray,$quizname_like);
        $params['quiznamelike'] = $quizname;
    }

    if(count($whereClauseArray)>0){
        $whereClause = implode(" AND ",$whereClauseArray);
        $sql = "SELECT 
        e.id as reportid, 
        e.userid as studentid, 
        e.webcampicture as webcampicture, 
        e.status as status,
        e.quizid as quizid,
        e.courseid as courseid,
        e.timemodified as timemodified, 
        u.firstname as firstname, 
        u.lastname as lastname, 
        u.email as email,
        c.fullname as coursename,
        q.name as quizname
        from  {quizaccess_proctoring_logs} e 
        INNER JOIN {user} u  ON u.id = e.userid
        INNER JOIN {course} c  ON c.id = e.courseid
        INNER JOIN {course_modules} cm  ON cm.id = e.quizid
        INNER JOIN {quiz} q  ON q.id = cm.instance
        WHERE $whereClause";

        // Prepare data.
        $sqlexecuted = $DB->get_recordset_sql($sql,$params);
    }
    else{
        $sqlexecuted = array();
    }
}
else if($formtype == 'Delete'){
    $deleteids = explode(",",$deleteidstring);
    if(count($deleteids)>0){
        /// Get report rows
        list($insql, $inparams) = $DB->get_in_or_equal($deleteids);
        $sql = "SELECT * FROM {quizaccess_proctoring_logs} WHERE id $insql";
        $logs = $DB->get_records_sql($sql, $inparams);

        foreach($logs as $row){
            $id = $row->id;
            $tempuserid = $row->userid;
            $fileurl = $row->webcampicture;
            $patharray = explode("/",$fileurl);
            $filename = end($patharray);

            // Set userid = 0
            $DB->set_field('quizaccess_proctoring_logs', 'userid', 0, array('id' => $id));

            // Delete users file (webcam images).
            $filesql = 'SELECT * FROM {files} 
                        WHERE 
                        component = "quizaccess_proctoring" 
                        AND filearea = "picture"
                        AND filename = :filename';
            $params = array();
            $params["filename"] = $filename;
            $usersfile = $DB->get_records_sql($filesql, $params);
            $fs = get_file_storage();
            foreach ($usersfile as $file):
                // Prepare file record object
                $fileinfo = array(
                    'component' => 'quizaccess_proctoring',
                    'filearea' => 'picture',     // Usually = table name.
                    'itemid' => $file->itemid,               // Usually = ID of row in table.
                    'contextid' => $file->contextid, // ID of context.
                    'filepath' => '/',           // any path beginning and ending in /.
                    'filename' => $file->filename); // any filename.

                // Get file
                $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                    $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

                // Delete it if it exists
                if ($file) {
                    $file->delete();
                }
            endforeach;
        }
    }

    $url2 = new moodle_url(
        '/mod/quiz/accessrule/proctoring/additional_settings.php',
        array(
            'cmid' => $cmid
        )
    );
    redirect($url2, 'Images deleted!', -11);
}
else {
    $sql = "SELECT 
        e.id as reportid, 
        e.userid as studentid, 
        e.webcampicture as webcampicture, 
        e.status as status,
        e.quizid as quizid,
        e.courseid as courseid,
        e.timemodified as timemodified, 
        u.firstname as firstname, 
        u.lastname as lastname, 
        u.email as email,
        c.fullname as coursename,
        q.name as quizname
        from  {quizaccess_proctoring_logs} e 
        INNER JOIN {user} u  ON u.id = e.userid
        INNER JOIN {course} c  ON c.id = e.courseid
        INNER JOIN {course_modules} cm  ON cm.id = e.quizid
        INNER JOIN {quiz} q  ON q.id = cm.instance";

    // Prepare data.
    $sqlexecuted = $DB->get_recordset_sql($sql);
}

////
$search_row = array();
$search_row[] = '';
$search_row[] = '<input type="text" placeholder="user name" id="uname" name="uname" value="'.$username.'">';
$search_row[] = '<input type="text" placeholder="email" id="email" name="email" value="'.$email.'">';
$search_row[] = '<input type="text" placeholder="coursename" id="coursename" name="coursename" value="'.$coursename.'">';
$search_row[] = '<input type="text" placeholder="quizname" id="quizname" name="quizname" value="'.$quizname.'">';
$search_row[] = '';
$search_row[] = '<input type="submit" name="form_type" value="Search">
                 <br/>
                 Select All &nbsp<input type="checkbox" id="select_all" name="select_all" value="0">
                 <br/>
                 <button id="delete_select_btn"  style="display: none">Delete</button>';
$table->add_data($search_row);

foreach ($sqlexecuted as $info) {
    $data = array();
    $data[] = $info->reportid;
    $data[] = $info->firstname . ' ' . $info->lastname;
    $data[] = $info->email;
    $data[] = $info->coursename;
    $data[] = $info->quizname;
    $data[] = date("Y/M/d H:m:s", $info->timemodified);
    $data[] = '<input type="checkbox" class ="reportIdChkBox" value="'.$info->reportid.'">';
    $table->add_data($data);
}
$table->finish_html();
echo "</form>";

//echo "success";
echo $OUTPUT->footer();