<?php
require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir.'/filelib.php');
require_once(__DIR__ .'/lib.php');
$studentid = required_param('studentid', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$reportid = required_param('reportid', PARAM_INT);
$fcmethod = get_proctoring_settings("fcmethod");
$params = array(
    "courseid" => $courseid,
    "quizid" => $cmid,
    "cmid" => $cmid,
    "studentid" => $studentid,
    "reportid" => $reportid
);
$redirecturl = new moodle_url('/mod/quiz/accessrule/proctoring/report.php',$params);
if($fcmethod == "AWS"){
    aws_analyze_specific_quiz($courseid, $cmid, $studentid);
}
else if($fcmethod == "BS"){
    bs_analyze_specific_quiz($courseid, $cmid, $studentid);
}
else{
    redirect($redirecturl, "Invalid facematch method in settings. Please give 'BS' or 'AWS' as face match method", 1, \core\output\notification::NOTIFY_ERROR);
}
redirect($redirecturl);

