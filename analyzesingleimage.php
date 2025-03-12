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
 * Analyzes single image.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir.'/filelib.php');
require_once(__DIR__ .'/lib.php');

$studentid = required_param('studentid', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$reportid = required_param('reportid', PARAM_INT);
$imgid = required_param('imgid', PARAM_INT);

list($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');

require_login($course, true, $cm);

// Context and validation.
$context = context_module::instance($cmid, MUST_EXIST);

// Check if the user has the required capability or is a site admin.
if (!has_capability('quizaccess/proctoring:analyzeimages', $context) && !is_siteadmin()) {
    throw new moodle_exception('nopermission', 'error', '', null, 'You do not have permission to access this page.');
}

$fcmethod = quizaccess_proctoring_get_proctoring_settings("fcmethod");
$params = [
    "courseid" => $courseid,
    "quizid" => $cmid,
    "cmid" => $cmid,
    "studentid" => $studentid,
    "reportid" => $reportid,
];
$profileimageurl = quizaccess_proctoring_get_image_url($studentid);

// If image is not uploaded then teacher will be redirected to report page.
if (!is_siteadmin() && empty($profileimageurl)) {
    $redirecturl = new moodle_url('/mod/quiz/accessrule/proctoring/report.php', $params);
    redirect(
        $redirecturl,
        get_string('user_image_not_uploaded_teacher', 'quizaccess_proctoring'),
        1,
        \core\output\notification::NOTIFY_WARNING
    );
} else if (is_siteadmin() && empty($profileimageurl)) {
    // If image is not uploaded then admin will be redirected to upload image page.
    $redirecturl = new moodle_url('/mod/quiz/accessrule/proctoring/upload_image.php', ['id' => $studentid]);
    redirect(
        $redirecturl,
        get_string('user_image_not_uploaded', 'quizaccess_proctoring'),
        1,
        \core\output\notification::NOTIFY_WARNING
    );
}

$redirecturl = new moodle_url('/mod/quiz/accessrule/proctoring/report.php', $params);
$bsapi = quizaccess_proctoring_get_proctoring_settings('bsapi');
$bsapikey = quizaccess_proctoring_get_proctoring_settings('bs_api_key');

if ($fcmethod == "BS") {
    if (empty($bsapi) || empty($bsapikey)) {
        redirect(
            $redirecturl,
            get_string('invalid_facematch_method', 'quizaccess_proctoring'),
            1,
            \core\output\notification::NOTIFY_ERROR
        );
    } else {
        quizaccess_proctoring_bs_analyze_specific_image($imgid, $redirecturl);
    }
} else {
    redirect(
        $redirecturl,
        get_string('invalid_facematch_method', 'quizaccess_proctoring'),
        1,
        \core\output\notification::NOTIFY_ERROR
    );
}

redirect($redirecturl);
