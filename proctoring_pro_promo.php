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
 * Proctoring pro promo page for quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__.'/../../../../config.php');
require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');

require_login();

if (is_siteadmin()) {
    $PAGE->set_pagelayout('admin');
} else {
    $PAGE->set_pagelayout('course');
}

$courseid = optional_param('courseid', '0', PARAM_INT);
$cmid = optional_param('cmid' , '0' , PARAM_INT);

$PAGE->set_url(new moodle_url('/mod/quiz/accessrule/proctoring/proctoring_pro_promo.php', [
    'courseid' => $courseid, 'cmid' => $cmid]));
$PAGE->set_context(context_system::instance());

$PAGE->requires->css('/mod/quiz/accessrule/proctoring/styles.css');
$PAGE->set_title(get_string('proctoring_pro_promo', 'quizaccess_proctoring'));
$PAGE->navbar->add(get_string('proctoring_pro_promo', 'quizaccess_proctoring'));
$PAGE->set_heading(get_string('proctoring_pro_promo_heading', 'quizaccess_proctoring'));

if ($courseid != 0 && $cmid != 0) {
    $PAGE->navbar->add(get_string('reportpage', 'quizaccess_proctoring'),
    new moodle_url('/mod/quiz/accessrule/proctoring/report.php', ['cmid' => $cmid , 'courseid' => $courseid]));
    $PAGE->navbar->add(get_string('proctoring_pro_promo', 'quizaccess_proctoring'));
} else {
    $PAGE->navbar->add(get_string('userlist', 'quizaccess_proctoring'),
    new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php'));
    $PAGE->navbar->add(get_string('proctoring_pro_promo', 'quizaccess_proctoring'));
}

echo $OUTPUT->header();
$proctoringprologo = $OUTPUT->image_url('proctoring_pro_logo', 'quizaccess_proctoring');
$proctoringprogif = $OUTPUT->image_url('proctoring_pro_report', 'quizaccess_proctoring');
$proctoringgif = $OUTPUT->image_url('proctoring_report', 'quizaccess_proctoring');
$successsign = $OUTPUT->image_url('success', 'quizaccess_proctoring');
$crossign = $OUTPUT->image_url('crossign', 'quizaccess_proctoring');
$elearninglogo = $OUTPUT->image_url('cropped-e-Learning-23-logo-1', 'quizaccess_proctoring');
$moodlepartnerlogo = $OUTPUT->image_url('moodleheader-logo', 'quizaccess_proctoring');

$template = 'quizaccess_proctoring/proctoring_pro_promo';
$context = [
    'promobanner_style' => "background-image: url('"
                        . $OUTPUT->image_url('proctoring_pro_logo', 'quizaccess_proctoring')->out() . "');",
    'proctoring_pro_gif' => $proctoringprogif,
    'proctoring_gif' => $proctoringgif,
    'elearninglogo' => $elearninglogo,
    'moodlepartnerlogo' => $moodlepartnerlogo,
    'successsign' => $successsign,
    'crossign' => $crossign,
];

echo $OUTPUT->render_from_template($template, $context);
echo $OUTPUT->footer();
