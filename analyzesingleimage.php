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

require_once(__DIR__ . '/../../../../config.php');

require_once($CFG->libdir.'/filelib.php');
require_once(__DIR__ .'/lib.php');

$studentid = required_param('studentid', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$reportid = required_param('reportid', PARAM_INT);
$imgid = required_param('imgid', PARAM_INT);

list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');

require_login($course, true, $cm);

$fcmethod = get_proctoring_settings("fcmethod");
$params = array(
    "courseid" => $courseid,
    "quizid" => $cmid,
    "cmid" => $cmid,
    "studentid" => $studentid,
    "reportid" => $reportid
);

$redirecturl = new moodle_url('/mod/quiz/accessrule/proctoring/report.php', $params);
if ($fcmethod == "AWS") {
    aws_analyze_specific_image($imgid);
} else if ($fcmethod == "BS") {
    bs_analyze_specific_image($imgid);
} else {
    redirect($redirecturl, "Invalid facematch method in settings. Please give 'BS' or 'AWS' as face match method",
    1,
    \core\output\notification::NOTIFY_ERROR);
}
redirect($redirecturl);
