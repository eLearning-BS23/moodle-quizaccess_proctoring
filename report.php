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
 * The path to the report file for the quizaccess_proctoring plugin.
 *
 * This constant holds the relative path to the report.php file used by the
 * quiz access rule for proctoring. It is utilized in the plugin to access
 * the report generation functionality.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../../../config.php');
require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');
require_once($CFG->libdir.'/tablelib.php');

// Parameters.
$courseid = required_param('courseid', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
$studentid = optional_param('studentid', null, PARAM_INT);
$searchkey = optional_param('searchKey', null, PARAM_TEXT);
$submittype = optional_param('submitType', null, PARAM_TEXT);
$reportid = optional_param('reportid', null, PARAM_INT);
$logaction = optional_param('logaction', null, PARAM_TEXT);
$page = optional_param('page', 0, PARAM_INT);

$analyzebtn = get_string('analyzbtn', 'quizaccess_proctoring');
$analyzebtnconfirm = get_string('analyzbtnconfirm', 'quizaccess_proctoring');


// Context and validation.
$context = context_module::instance($cmid, MUST_EXIST);
require_capability('quizaccess/proctoring:viewreport', $context);

list($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');
require_login($course, true, $cm);

// Course and quiz data.
$coursedata = $DB->get_record('course', ['id' => $courseid]);
$quiz = $DB->get_record('quiz', ['id' => $cm->instance]);

// URL setup.
$params = [
    'courseid' => $courseid,
    'userid' => $studentid,
    'cmid' => $cmid,
];
// Pagination set.
$perpage = 30;
$offset = $page * $perpage;
$totalrecords = 0;

if ($studentid) {
    $params['studentid'] = $studentid;
}
if ($reportid) {
    $params['reportid'] = $reportid;
}


$url = new moodle_url('/mod/quiz/accessrule/proctoring/report.php', ['courseid' => $courseid, 'cmid' => $cmid]);
$fcmethod = get_config('quizaccess_proctoring', 'fcmethod');

// Page setup.
$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_title($coursedata->shortname . ': ' . get_string('pluginname', 'quizaccess_proctoring'));
$PAGE->set_heading($coursedata->fullname . ': ' . get_string('pluginname', 'quizaccess_proctoring'));
$PAGE->navbar->add(get_string('quizaccess_proctoring', 'quizaccess_proctoring'), $url);
$PAGE->requires->js_call_amd('quizaccess_proctoring/lightbox2', 'init', [$fcmethod , [
    'analyzebtn' => $analyzebtn,
    'analyzebtnconfirm' => $analyzebtnconfirm,
]]);
$PAGE->requires->css('/mod/quiz/accessrule/proctoring/styles.css');
// Add navbar for studnet report.
if ($studentid != null && $cmid != null && $courseid != null && $reportid != null) {
    $PAGE->navbar->add(get_string('studentreport', 'quizaccess_proctoring') . " - $studentid", $url);
}

// Button logic.
$settingsbtn = has_capability('quizaccess/proctoring:viewreport', $context, $USER->id);
$showclearbutton = ($submittype === 'Search' && !empty($searchkey));

if (has_capability('quizaccess/proctoring:deletecamshots', $context, $USER->id) && $studentid != null
    && $cmid != null && $courseid != null && $reportid != null&& !empty($logaction)) {

        $DB->delete_records('quizaccess_proctoring_logs', [
            'courseid' => $courseid,
            'quizid' => $cmid,
            'userid' => $studentid,
        ]);
        $DB->delete_records('quizaccess_proctoring_fm_warnings', [
            'courseid' => $courseid,
            'quizid' => $cmid,
            'userid' => $studentid,
        ]);


        $params = [
        'userid' => $studentid,
        'contextid' => $context->id,
        'component' => 'quizaccess_proctoring',
        'filearea'  => 'picture',
        ];

        $usersfile = $DB->get_records('files', $params);
        $fs = get_file_storage();
        foreach ($usersfile as $file) {
            $fileinfo = [
                'component' => 'quizaccess_proctoring',
                'filearea' => 'picture',
                'itemid' => $file->itemid,
                'contextid' => $context->id,
                'filepath' => '/',
                'filename' => $file->filename,
            ];
            $storedfile = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                        $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
            if ($storedfile) {
                $storedfile->delete();
            }
        }

        redirect(new moodle_url('/mod/quiz/accessrule/proctoring/report.php', [
            'courseid' => $courseid,
            'cmid' => $cmid,
        ]), 'Images deleted!', -11);
}

$proctoringprolink = new moodle_url(
    '/mod/quiz/accessrule/proctoring/proctoring_pro_promo.php',
    [
        'cmid' => $cmid,
        'courseid' => $courseid,
    ]
);

echo $OUTPUT->header();

$backbutton = new moodle_url('/mod/quiz/view.php', ['id' => $cmid]);

// Print report.
if (
    has_capability('quizaccess/proctoring:viewreport', $context, $USER->id) &&
    $cmid != null && $courseid != null) {
     // Show specific student report.
    if ($studentid != null && $cmid != null && $courseid != null && $reportid != null) {
         // Set backButton.
        $backbutton = new moodle_url('/mod/quiz/accessrule/proctoring/report.php?',
                    ['courseid' => $courseid , 'cmid' => $cmid ]);
        // Report for this user.
        $sql = "SELECT
                    e.id AS reportid,
                    e.userid AS studentid,
                    e.webcampicture AS webcampicture,
                    e.status AS status,
                    e.timemodified AS timemodified,
                    u.firstname AS firstname,
                    u.lastname AS lastname,
                    u.email AS email,
                    pfw.reportid AS warningid
                FROM
                    {quizaccess_proctoring_logs} e
                INNER JOIN
                    {user} u
                    ON u.id = e.userid
                LEFT JOIN
                    {quizaccess_proctoring_fm_warnings} pfw
                    ON e.courseid = pfw.courseid
                    AND e.quizid = pfw.quizid
                    AND e.userid = pfw.userid
                WHERE
                    e.courseid = :courseid
                    AND e.quizid = :cmid
                    AND u.id = :studentid
                    AND e.id = :reportid ";
    }

    if ($studentid == null && $cmid != null && $courseid != null) {
        // Report for all users.
        $sql = "SELECT DISTINCT
                    e.userid AS studentid,
                    u.firstname AS firstname,
                    u.lastname AS lastname,
                    u.email AS email,
                    pfw.reportid AS warningid,
                    MAX(e.webcampicture) AS webcampicture,
                    MAX(e.id) AS reportid,
                    MAX(e.status) AS status,
                    MAX(e.timemodified) AS timemodified
                FROM
                    {quizaccess_proctoring_logs} e
                INNER JOIN
                    {user} u
                    ON u.id = e.userid
                LEFT JOIN
                    {quizaccess_proctoring_fm_warnings} pfw
                    ON e.courseid = pfw.courseid
                    AND e.quizid = pfw.quizid
                    AND e.userid = pfw.userid
                WHERE
                    e.courseid = :courseid
                    AND e.quizid = :cmid
                GROUP BY
                    e.userid, u.firstname, u.lastname, u.email, pfw.reportid ";
    }

    if ($studentid == null && $cmid != null && $searchkey != null && $submittype == 'clear') {
        // Report for searched users.
        $sql = "SELECT DISTINCT e.userid AS studentid,
                                u.firstname AS firstname,
                                u.lastname AS lastname,
                                u.email AS email,
                                pfw.reportid AS warningid,
                                MAX(e.webcampicture) AS webcampicture,
                                MAX(e.id) AS reportid,
                                MAX(e.status) AS status,
                                MAX(e.timemodified) AS timemodified
                        FROM {quizaccess_proctoring_logs} e
                        INNER JOIN {user} u ON u.id = e.userid
                        LEFT JOIN {quizaccess_proctoring_fm_warnings} pfw ON e.courseid = pfw.courseid
                        AND e.quizid = pfw.quizid
                        AND e.userid = pfw.userid
                        WHERE e.courseid = :courseid
                        AND e.quizid = :quizid
                        GROUP BY e.userid, u.firstname, u.lastname, u.email, pfw.reportid";
    }

    if ($studentid == null && $cmid != null && $searchkey != null && $submittype == 'Search') {
        $sql = "SELECT DISTINCT e.userid AS studentid,
                                u.firstname AS firstname,
                                u.lastname AS lastname,
                                u.email AS email,
                                pfw.reportid AS warningid,
                                MAX(e.webcampicture) AS webcampicture,
                                MAX(e.id) AS reportid,
                                MAX(e.status) AS status,
                                                        MAX(e.timemodified) AS timemodified
                        FROM {quizaccess_proctoring_logs} e
                        INNER JOIN {user} u ON u.id = e.userid
                        LEFT JOIN {quizaccess_proctoring_fm_warnings} pfw
                        ON e.courseid = pfw.courseid
                        AND e.quizid = pfw.quizid
                        AND e.userid = pfw.userid
                        WHERE (e.courseid = :courseid1 AND e.quizid = :quizid1 AND
                              " . $DB->sql_like('u.firstname', ':firstnamelike', false) . ")
                                OR (e.courseid = :courseid2 AND e.quizid = :quizid2 AND "
                                . $DB->sql_like('u.email', ':emaillike', false) . ")
                                OR (e.courseid = :courseid3 AND e.quizid = :quizid3 AND "
                                . $DB->sql_like('u.lastname', ':lastnamelike', false) . ")
                                GROUP BY e.userid, u.firstname, u.lastname, u.email, pfw.reportid";
    }


    if ($studentid == null && $cmid != null && $searchkey != null && $submittype == 'Search') {
        $params = ['firstnamelike' => "%$searchkey%",
                'lastnamelike' => "%$searchkey%",
                'emaillike' => "%$searchkey%",
                'courseid1' => $courseid,
                'courseid2' => $courseid,
                'courseid3' => $courseid,
                'quizid1' => $cmid,
                'quizid2' => $cmid,
                'quizid3' => $cmid];

        // Calculate total records for pagination.
        $totalrecordssql = "SELECT COUNT(DISTINCT e.userid)
                            FROM {quizaccess_proctoring_logs} e
                            INNER JOIN {user} u ON u.id = e.userid
                            LEFT JOIN {quizaccess_proctoring_fm_warnings} pfw
                            ON e.courseid = pfw.courseid AND e.quizid = pfw.quizid AND e.userid = pfw.userid
                            WHERE (e.courseid = :courseid1 AND e.quizid = :quizid1 AND
                            " . $DB->sql_like('u.firstname', ':firstnamelike', false) . ")
                            OR (e.courseid = :courseid2 AND e.quizid = :quizid2 AND
                            " . $DB->sql_like('u.email', ':emaillike', false) . ")
                            OR (e.courseid = :courseid3 AND e.quizid = :quizid3 AND "
                            . $DB->sql_like('u.lastname', ':lastnamelike', false) . ")";
        $totalrecords = $DB->count_records_sql($totalrecordssql, $params);

        // Fetch paginated results.
        $sqlexecuted = $DB->get_records_sql($sql, $params, $offset, $perpage);
    } else {
        $params = [
            'courseid' => $courseid,
            'cmid' => $cmid,
            'studentid' => $studentid,
            'reportid' => $reportid,
        ];
        $totalrecordssql = "SELECT COUNT(1) FROM ({$sql}) as subquery";
        $totalrecords = $DB->count_records_sql($totalrecordssql, $params);
        $sqlexecuted = $DB->get_records_sql($sql, $params, $offset, $perpage);
    }

       // Print report.
    $rows = [];
    foreach ($sqlexecuted as $info) {
            $row = [];
            $row['userlink'] = $CFG->wwwroot.'/user/view.php?id=' . $info->studentid . '&course=' . $courseid;
            $row['fullname'] = $info->firstname . ' ' . $info->lastname;
            $row['email'] = $info->email;
            $row['timemodified'] = date('Y/M/d H:i:s', $info->timemodified);
            $row['warningicon'] = ($info->warningid == '') ? true : false;

            $actionmenu = new action_menu();
            $actionmenu->set_kebab_trigger(get_string('actions'));

            $viewurl = new moodle_url($PAGE->url, [
                'courseid' => $courseid,
                'quizid' => $cmid,
                'cmid' => $cmid,
                'studentid' => $info->studentid,
                'reportid' => $info->reportid,
            ]);

            $viewaction = new action_menu_link_secondary(
                $viewurl,
                new pix_icon('e/insert_edit_image', get_string('viewimages', 'quizaccess_proctoring'), 'moodle'),
                get_string('viewimages', 'quizaccess_proctoring')
            );
            $actionmenu->add($viewaction);

            $deleteurl = new moodle_url($PAGE->url, [
                'courseid' => $courseid,
                'quizid' => $cmid,
                'cmid' => $cmid,
                'studentid' => $info->studentid,
                'reportid' => $info->reportid,
                'logaction' => 'delete',
                'sesskey' => sesskey(),
            ]);

            // Prepare attributes for the delete action.
            $attributes = [
                'data-confirmation' => 'modal',
                'data-confirmation-type' => 'delete',
                'data-confirmation-title-str' => json_encode(['delete', 'core']),
                'data-confirmation-content-str' => json_encode(['areyousure_delete_record', 'quizaccess_proctoring']),
                'data-confirmation-yes-button-str' => json_encode(['delete', 'core']),
                'data-confirmation-action-url' => $deleteurl->out(false),
                'data-confirmation-destination' => $deleteurl->out(false),
                'class' => 'text-danger',
            ];

            $deleteaction = new action_menu_link_secondary(
                $deleteurl,
                new pix_icon('t/delete', '', 'moodle'),
                get_string('delete'),
                $attributes
            );

            $actionmenu->add($deleteaction);

            // Add rendered HTML to template context.
            $row['actionmenu'] = $OUTPUT->render($actionmenu);
            $rows[] = $row;
    }
    $templatecontext = (object)[
        'quizname'        => get_string('eprotroringreports', 'quizaccess_proctoring') . $quiz->name,
        'settingsbtn'     => $settingsbtn,
        'settingspageurl'  => $CFG->wwwroot.'/mod/quiz/accessrule/proctoring/proctoringsummary.php?cmid='.$cmid,
        'proctoringsummary' => get_string('eprotroringreportsdesc', 'quizaccess_proctoring'),
        'url' => $CFG->wwwroot. '/mod/quiz/accessrule/proctoring/report.php',
        'courseid' => $courseid,
        'cmid' => $cmid,
        'searchkey' => ($submittype == "Clear") ? '' : $searchkey,
        'showclearbutton' => $showclearbutton,
        'checkrow' => (!empty($row)) ? true : false,
        'rows' => $rows,
        'backbutton' => preg_replace('/&amp;/', '&', $backbutton),
    ];
    echo $OUTPUT->render_from_template('quizaccess_proctoring/report', $templatecontext);

    // Pagination added.
    $currenturl = new moodle_url(qualified_me());
    // If user search the  specific value.
    if (!empty($searchkey) && empty($submittype) ) {
        $currenturl->param('searchKey' , $searchkey);
        $currenturl->param('submitType' , $submittype);
    }
    $currenturl->param('page' , $page);
    $pagingbar = new paging_bar($totalrecords, $page, $perpage, $currenturl);
    echo $OUTPUT->render($pagingbar);
    // Print image results.
    if ($studentid != null && $cmid != null && $courseid != null && $reportid != null) {
        $featuresimageurl = $OUTPUT->image_url('proctoring_pro_report_overview', 'quizaccess_proctoring');
        $profileimageurl = quizaccess_proctoring_get_image_url($studentid);
        $redirecturl = new moodle_url('/mod/quiz/accessrule/proctoring/upload_image.php', ['id' => $studentid]);

        $sql = "SELECT e.id AS reportid,
               e.userid AS studentid,
               e.webcampicture AS webcampicture,
               e.status AS status,
               e.timemodified AS timemodified,
               u.firstname AS firstname,
               u.lastname AS lastname,
               u.email AS email,
               e.awsscore,
               e.awsflag
        FROM {quizaccess_proctoring_logs} e
        INNER JOIN {user} u ON u.id = e.userid
        WHERE e.courseid = :courseid
          AND e.quizid = :cmid
          AND u.id = :studentid
          AND e.deletionprogress = :deletionprogress";
        $params = [
            'courseid' => $courseid,
            'cmid' => $cmid,
            'studentid' => $studentid,
            'deletionprogress' => 0,
        ];
        $sqlexecuted = $DB->get_recordset_sql($sql, $params);

        $user = core_user::get_user($studentid);
        $thresholdvalue = (int) quizaccess_proctoring_get_proctoring_settings('threshold');
        $studentdata = [];
        foreach ($sqlexecuted as $info) {
                $row = [];
                $row['firstname'] = $info->firstname;
                $row['lastname'] = $info->lastname;
                $row['image_url'] = $info->webcampicture;
                $row['border_color'] = $info->awsflag == 2 && $info->awsscore > $thresholdvalue ? 'green' :
                                        ($info->awsflag == 2 && $info->awsscore < $thresholdvalue ? 'red' :
                                        ($info->awsflag == 3 && $info->awsscore < $thresholdvalue ? 'yellow' : 'none'));
                $row['img_id'] = 'reportid-' . $info->reportid;
                $row['lightbox_data'] = basename($info->webcampicture, '.png');
                $studentdata[] = $row;
        }
        $analyzeparam = ['studentid' => $studentid, 'cmid' => $cmid, 'courseid' => $courseid, 'reportid' => $reportid];
        $analyzeurl = new moodle_url('/mod/quiz/accessrule/proctoring/analyzeimage.php', $analyzeparam);
        $analyzeurl = preg_replace('/&amp;/', '&', $analyzeurl);
        $userimageurl = quizaccess_proctoring_get_image_url($user->id);
        if (!$userimageurl) {
            $userimageurl = $OUTPUT->image_url('u/f2');
        }
        $templatecontext = (object)[
            'featuresimageurl' => $featuresimageurl,
            'proctoringprolink' => preg_replace('/&amp;/', '&', $proctoringprolink),
            'issiteadmin' => (is_siteadmin() && !$profileimageurl ? true : false),
            'redirecturl' => $redirecturl,
            'data' => $studentdata,
            'userimageurl' => $userimageurl,
            'firstname' => $info->firstname,
            'lastname' => $info->lastname,
            'email' => $info->email,
            'fcmethod' => ($fcmethod == 'BS') ? true : false,
            'analyzeurl' => $analyzeurl,
        ];
        echo $OUTPUT->render_from_template('quizaccess_proctoring/studentreport', $templatecontext);
    }
} else {
    echo $OUTPUT->notify(get_string('notpermissionreport', 'quizaccess_proctoring'), 'notifyproblem');
}

echo $OUTPUT->footer();
