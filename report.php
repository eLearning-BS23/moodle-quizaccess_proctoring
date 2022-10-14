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
 * Report for the quizaccess_proctoring plugin.
 *
 * @copyright 2020 Brain Station 23
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__.'/../../../../config.php');
require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');
require_once($CFG->libdir.'/tablelib.php');
const MOD_QUIZ_ACCESSRULE_PROCTORING_REPORT_PHP = '/mod/quiz/accessrule/proctoring/report.php';

const DATA_LIGHTBOX = '" data-lightbox="';
const ANCHORENDTAG = '"/></a>';
const ALT = '" alt="';
const IMG_ID = '<img id="';
const DATA_TITLE = ' data-title ="';
const DATA_LIGHTBOX_PROC_IMAGES = '" data-lightbox="procImages"';
const A_HREF = '<a href="';
const PROCTORING_INNER_JOIN_USER_USERID = ' from  {quizaccess_proctoring_logs} e INNER JOIN {user} u ON u.id = e.userid ';
const MAX_E_TIMEMODIFIED_AS_TIMEMODIFIED = ' max(e.timemodified) as timemodified ';
const MAX_REPORTID_STATUS_AS_STATUS = ' max(e.id) as reportid, max(e.status) as status, ';
const SELECT_DISTINCT_LASTNAME = ' SELECT  DISTINCT e.userid as studentid, u.firstname as firstname, u.lastname as lastname, ';
const HTML_STRING_URL_FROM = '/mod/quiz/accessrule/proctoring/report.php">
      <input type="hidden" id="courseid" name="courseid" value="';
const FORM_ACTION = '<form action="';
const HIDDEN_CMID = '">
      <input type="hidden" id="cmid" name="cmid" value="';
const DIV = '</div>';
// Get vars.
$courseid = required_param('courseid', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
$studentid = optional_param('studentid', '', PARAM_INT);
$searchkey = optional_param('searchKey', '', PARAM_TEXT);
$submittype = optional_param('submitType', '', PARAM_TEXT);
$reportid = optional_param('reportid', '', PARAM_INT);
$logaction = optional_param('logaction', '', PARAM_TEXT);

$context = context_module::instance($cmid, MUST_EXIST);

list($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');

require_login($course, true, $cm);

$COURSE = $DB->get_record('course', ['id' => $courseid]);
$quiz = $DB->get_record('quiz', ['id' => $cm->instance]);

$params = [
    'courseid' => $courseid,
    'userid' => $studentid,
    'cmid' => $cmid,
];
if ($studentid) {
    $params['studentid'] = $studentid;
}
if ($reportid) {
    $params['reportid'] = $reportid;
}

$url = new moodle_url(
    MOD_QUIZ_ACCESSRULE_PROCTORING_REPORT_PHP,
    $params
);

$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_title($COURSE->shortname.': '.get_string('pluginname', 'quizaccess_proctoring'));
$PAGE->set_heading($COURSE->fullname.': '.get_string('pluginname', 'quizaccess_proctoring'));

$PAGE->navbar->add(get_string('quizaccess_proctoring', 'quizaccess_proctoring'), $url);

$PAGE->requires->js_call_amd('quizaccess_proctoring/lightbox2');

$settingsbtn = '';
$logbtn = '';

if (has_capability('quizaccess/proctoring:deletecamshots', $context, $USER->id)) {
    $settingspageurl = $CFG->wwwroot.'/mod/quiz/accessrule/proctoring/proctoringsummary.php?cmid='.$cmid;
    $settingsbtnlabel = 'Proctoring Summary Report';
    $settingsbtn = '<a class="btn btn-primary" href="'.$settingspageurl.'">'.$settingsbtnlabel.'</a>';

    $logpageurl = $CFG->wwwroot.'/mod/quiz/accessrule/proctoring/additional_settings.php?cmid='.$cmid;
    $logbtnlabel = 'Proctoring Logs';
    $logbtn = '<a class="btn btn-primary" style="margin-left:5px" href="'.$logpageurl.'">'.$logbtnlabel.'</a>';
}

if ($submittype == 'Search' && $searchkey != null) {
    $searchform = FORM_ACTION.$CFG->wwwroot.HTML_STRING_URL_FROM.$courseid.HIDDEN_CMID.$cmid.'">
      <input style="width:250px" type="text" id="searchKey" name="searchKey"
      placeholder="Search by email" value="'.$searchkey.'">
      <input type="submit" name="submitType" value="Search">
      <input type="submit" name="submitType" value="clear">
    </form>
    ';
} else {
    $searchform = FORM_ACTION.$CFG->wwwroot.HTML_STRING_URL_FROM.$courseid.HIDDEN_CMID.$cmid.'">
      <input style="width:250px" type="text" id="searchKey" name="searchKey" placeholder="Search by email">
      <input type="submit" name="submitType" value="Search">
    </form>';
}

if (has_capability('quizaccess/proctoring:deletecamshots', $context, $USER->id)
    && $studentid != null
    && $cmid != null
    && $courseid != null
    && $reportid != null
    && !empty($logaction)
) {
    $DB->delete_records('quizaccess_proctoring_logs', ['courseid' => $courseid, 'quizid' => $cmid, 'userid' => $studentid]);
    $DB->delete_records('proctoring_fm_warnings', ['courseid' => $courseid, 'quizid' => $cmid, 'userid' => $studentid]);
    // Delete users file (webcam images).
    $filesql = 'SELECT * FROM {files}
    WHERE userid = :studentid  AND contextid = :contextid  AND component = \'quizaccess_proctoring\' AND filearea = \'picture\'';

    $params = [];
    $params['studentid'] = $studentid;
    $params['contextid'] = $context->id;

    $usersfile = $DB->get_records_sql($filesql, $params);

    $fs = get_file_storage();
    foreach ($usersfile as $file):
        // Prepare file record object.
        $fileinfo = [
            'component' => 'quizaccess_proctoring',
            'filearea' => 'picture',     // Usually = table name.
            'itemid' => $file->itemid,               // Usually = ID of row in table.
            'contextid' => $context->id, // ID of context.
            'filepath' => '/',           // Any path beginning and ending in /.
            'filename' => $file->filename, ]; // Any filename.

        // Get file.
        $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
            $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

        // Delete it if it exists.
        if ($file) {
            $file->delete();
        }
    endforeach;
    $url2 = new moodle_url(
        MOD_QUIZ_ACCESSRULE_PROCTORING_REPORT_PHP,
        [
            'courseid' => $courseid,
            'cmid' => $cmid,
        ]
    );
    redirect($url2, 'Images deleted!', -11);
}
echo $OUTPUT->header();
echo '<div id="main">
<h2>'.get_string('eprotroringreports', 'quizaccess_proctoring').''.$quiz->name.'</h2>'.'
<br/><br/><div style="float: left">'.$searchform.DIV.'<div style="float: right">'.$settingsbtn.$logbtn.'</div><br/><br/>
<div class="box generalbox m-b-1 adminerror alert alert-info p-y-1">'
    .get_string('eprotroringreportsdesc', 'quizaccess_proctoring').'</div>
';
if (
    has_capability('quizaccess/proctoring:viewreport', $context, $USER->id) &&
    $cmid != null &&
    $courseid != null) {
    // Check if report if for some user.
    if ($studentid != null && $cmid != null && $courseid != null && $reportid != null) {
        // Report for this user.
        $sql = ' SELECT e.id as reportid, e.userid as studentid, e.webcampicture as webcampicture, '
         .' e.status as status, '
         .' e.timemodified as timemodified, u.firstname as firstname, u.lastname as lastname, '
         .' u.email as email, pfw.reportid as warningid '
         .' from  {quizaccess_proctoring_logs} e INNER JOIN {user} u  ON u.id = e.userid '
         .' LEFT JOIN {proctoring_fm_warnings} pfw ON e.courseid = pfw.courseid '
         .' AND e.quizid = pfw.quizid AND e.userid = pfw.userid '
         ." WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' AND u.id = '$studentid' AND e.id = '$reportid' ";
    }

    if ($studentid == null && $cmid != null && $courseid != null) {
        // Report for all users.
        $sql = SELECT_DISTINCT_LASTNAME
                .' u.email as email,pfw.reportid as warningid, max(e.webcampicture) as webcampicture, '
                .MAX_REPORTID_STATUS_AS_STATUS
                .MAX_E_TIMEMODIFIED_AS_TIMEMODIFIED
                .PROCTORING_INNER_JOIN_USER_USERID
                .' LEFT JOIN {proctoring_fm_warnings} pfw ON e.courseid = pfw.courseid AND e.quizid = pfw.quizid '
                .' AND e.userid = pfw.userid '
                ." WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' "
                .' group by e.userid, u.firstname, u.lastname, u.email, pfw.reportid ';
    }

    if ($studentid == null && $cmid != null && $searchkey != null && $submittype == 'clear') {
        // Report for searched users.
        $sql = SELECT_DISTINCT_LASTNAME
                .' u.email as email, pfw.reportid as warningid, max(e.webcampicture) as webcampicture, '
                .MAX_REPORTID_STATUS_AS_STATUS
                .MAX_E_TIMEMODIFIED_AS_TIMEMODIFIED
                .PROCTORING_INNER_JOIN_USER_USERID
                .' LEFT JOIN {proctoring_fm_warnings} pfw ON e.courseid = pfw.courseid '
                .' AND e.quizid = pfw.quizid AND e.userid = pfw.userid '
                ." WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' "
                .' group by e.userid, u.firstname, u.lastname, u.email, pfw.reportid ';
    }

    if ($studentid == null && $cmid != null && $searchkey != null && $submittype == 'Search') {
        // Report for searched users.
        $sql = SELECT_DISTINCT_LASTNAME
                .' u.email as email, pfw.reportid as warningid, max(e.webcampicture) as webcampicture, '
                .MAX_REPORTID_STATUS_AS_STATUS
                .MAX_E_TIMEMODIFIED_AS_TIMEMODIFIED
                .PROCTORING_INNER_JOIN_USER_USERID
                .' LEFT JOIN {proctoring_fm_warnings} pfw ON e.courseid = pfw.courseid AND '
                .' e.quizid = pfw.quizid AND e.userid = pfw.userid '
                .' WHERE '
                ." (e.courseid = '$courseid' AND e.quizid = '$cmid' AND "
                .$DB->sql_like('u.firstname', ':firstnamelike', false).') OR '
              ." (e.courseid = '$courseid' AND e.quizid = '$cmid' AND ".$DB->sql_like('u.email', ':emaillike', false).') OR '
            ." (e.courseid = '$courseid' AND e.quizid = '$cmid' AND ".$DB->sql_like('u.lastname', ':lastnamelike', false)
            .' )group by e.userid, u.firstname, u.lastname, u.email, pfw.reportid'; // False = not case sensitive.
    }

    // Print report.
    $table = new flexible_table('proctoring-report-'.$COURSE->id.'-'.$cmid);

    $table->define_columns(['fullname', 'email', 'dateverified', 'warnings', 'actions']);
    $table->define_headers(
        [
            get_string('user'),
            get_string('email'),
            get_string('dateverified', 'quizaccess_proctoring'),
            get_string('warninglabel', 'quizaccess_proctoring'),
            get_string('actions', 'quizaccess_proctoring'),
        ]
    );
    $table->define_baseurl($url);

    $table->set_attribute('cellpadding', '5');
    $table->set_attribute('class', 'generaltable generalbox reporttable');
    $table->setup();

    // Prepare data.
    if ($studentid == null && $cmid != null && $searchkey != null && $submittype == 'Search') {
        // Report for searched users.
        $params = ['firstnamelike' => "%$searchkey%", 'lastnamelike' => "%$searchkey%", 'emaillike' => "%$searchkey%"];
        $sqlexecuted = $DB->get_recordset_sql($sql, $params);
    } else {
        $sqlexecuted = $DB->get_recordset_sql($sql);
    }

    foreach ($sqlexecuted as $info) {
        $data = [];
        $data[] = A_HREF.$CFG->wwwroot.'/user/view.php?id='.$info->studentid.
            '&course='.$courseid.'" target="_blank">'.$info->firstname.' '.$info->lastname.'</a>';

        $data[] = $info->email;

        $data[] = date('Y/M/d H:m:s', $info->timemodified);

        if ($info->warningid == '') {
            $data[] = '<i class="icon fa fa-check fa-fw " style="color: green"></i>';
        } else {
            $data[] = '<i class="icon fa fa-exclamation fa-fw " style="color: red"></i>';
        }

        $con = "return confirm('Are you sure want to delete the pictures?');";
        $btn = '<a onclick="'.$con.'" href="?courseid='.$courseid.
            '&quizid='.$cmid.'&cmid='.$cmid.'&studentid='.$info->studentid.
            '&reportid='.$info->reportid.'&logaction=delete"><i class="icon fa fa-trash fa-fw "></i></a>';

        $data[] = '<a href="?courseid='.$courseid.
            '&quizid='.$cmid.'&cmid='.$cmid.'&studentid='.$info->studentid.'&reportid='.$info->reportid.'">'.
            '<i class="icon fa fa-folder-o fa-fw "></i>'.'</a>
            '.$btn;

        $table->add_data($data);
    }
    $table->finish_html();

    // Print image results.
    if ($studentid != null && $cmid != null && $courseid != null && $reportid != null) {
        $data = [];
        $sql = "SELECT e.id as reportid, e.userid as studentid, e.webcampicture as webcampicture, e.status as status,
        e.timemodified as timemodified, u.firstname as firstname, u.lastname as lastname, u.email as email, e.awsscore, e.awsflag
        from {quizaccess_proctoring_logs} e INNER JOIN {user} u  ON u.id = e.userid
        WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' AND u.id = '$studentid'";

        $sqlexecuted = $DB->get_recordset_sql($sql);
        echo '<h3>'.get_string('picturesusedreport', 'quizaccess_proctoring').'</h3>';

        $tablepictures = new flexible_table('proctoring-report-pictures'.$COURSE->id.'-'.$cmid);

        $tablepictures->define_columns(
            [
                get_string('name', 'quizaccess_proctoring'),
                get_string('webcampicture', 'quizaccess_proctoring')
            ]
        );
        $tablepictures->define_headers(
            [
                get_string('name', 'quizaccess_proctoring'),
                get_string('webcampicture', 'quizaccess_proctoring')
            ]
        );
        $tablepictures->define_baseurl($url);

        $tablepictures->set_attribute('cellpadding', '2');
        $tablepictures->set_attribute('class', 'generaltable generalbox reporttable');

        $tablepictures->setup();
        $pictures = '';

        $user = core_user::get_user($studentid);
        $thresholdvalue = (int) get_proctoring_settings('awsfcthreshold');

        foreach ($sqlexecuted as $info) {
            $d = basename($info->webcampicture, '.png');
            $imgid = 'reportid-'.$info->reportid;

            if ($info->awsflag == 2 && $info->awsscore > $thresholdvalue) {
                $pictures .= $info->webcampicture
                    ? A_HREF.$info->webcampicture.DATA_LIGHTBOX_PROC_IMAGES.
                    DATA_TITLE.$info->firstname.' '
                    .$info->lastname.'">'.
                    IMG_ID.$imgid.'" style="border: 5px solid green" width="100" src="'
                    .$info->webcampicture.ALT.$info->firstname.' '
                    .$info->lastname.DATA_LIGHTBOX.basename($info->webcampicture, '.png').ANCHORENDTAG
                    : '';
            } else if ($info->awsflag == 2 && $info->awsscore < $thresholdvalue) {
                $pictures .= $info->webcampicture
                    ? A_HREF.$info->webcampicture.DATA_LIGHTBOX_PROC_IMAGES.
                    DATA_TITLE.$info->firstname.' '.$info->lastname.'">'.
                    IMG_ID.$imgid.'" style="border: 5px solid red" width="100" src="'
                    .$info->webcampicture.ALT.$info->firstname.' '
                    .$info->lastname.DATA_LIGHTBOX.basename($info->webcampicture, '.png').ANCHORENDTAG
                    : '';
            } else if ($info->awsflag == 3 && $info->awsscore < $thresholdvalue) {
                $pictures .= $info->webcampicture
                    ? A_HREF.$info->webcampicture.DATA_LIGHTBOX_PROC_IMAGES.
                    DATA_TITLE.$info->firstname.' '.$info->lastname.'">'.
                    IMG_ID.$imgid.'" style="border: 5px solid #f0ad4e" width="100" src="'
                    .$info->webcampicture.ALT.$info->firstname.' '
                    .$info->lastname.DATA_LIGHTBOX.basename($info->webcampicture, '.png').ANCHORENDTAG
                    : '';
            } else {
                $pictures .= $info->webcampicture
                    ? A_HREF.$info->webcampicture.DATA_LIGHTBOX_PROC_IMAGES.
                    DATA_TITLE.$info->firstname.' '.$info->lastname.'">'.
                    IMG_ID.$imgid.'" width="100" src="'.$info->webcampicture.ALT.$info->firstname.' '
                    .$info->lastname.DATA_LIGHTBOX.basename($info->webcampicture, '.png').ANCHORENDTAG
                    : '';
            }
        }

        $analyzeparam = ['studentid' => $studentid, 'cmid' => $cmid, 'courseid' => $courseid, 'reportid' => $reportid];
        $analyzeurl = new moodle_url('/mod/quiz/accessrule/proctoring/analyzeimage.php', $analyzeparam);
        $userinfo = '<table border="0" width="110" height="160px">
                        <tr height="120" style="background-color: transparent;">
                            <td style="border: unset;">'.$OUTPUT->user_picture($user, ['size' => 100]).'</td>
                        </tr>
                        <tr height="50">
                            <td style="border: unset;"><b>'.$info->firstname.' '.$info->lastname.'</b></td>
                        </tr>
                        <tr height="50">
                            <td style="border: unset;"><b>'.$info->email.'</b></td>
                        </tr>
                        <tr height="50">
                            <td><a href="'.$analyzeurl.'" class="btn btn-primary">Analyze Images</a></td>
                        </tr>
                    </table>';
            $datapictures = [
                $userinfo,
                $pictures,
            ];
            $tablepictures->add_data($datapictures);
            $tablepictures->finish_html();
    }
} else {
    // User has not permissions to view this page.
    echo '<div class="box generalbox m-b-1 adminerror alert alert-danger p-y-1">'.
        get_string('notpermissionreport', 'quizaccess_proctoring').DIV;
}
echo DIV;
echo $OUTPUT->footer();
