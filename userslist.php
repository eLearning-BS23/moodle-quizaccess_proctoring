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
 * User list for uploading image in quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2022 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */


require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . '/lib.php');
global $CFG, $PAGE, $OUTPUT, $DB;
require_login();

if (!is_siteadmin()) {
    redirect($CFG->wwwroot, get_string('no_permission', 'quizaccess_proctoring'), null, \core\output\notification::NOTIFY_ERROR);
}

$PAGE->set_url('/mod/quiz/accessrule/proctoring/userslist.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('users_list', 'quizaccess_proctoring'));
$PAGE->set_heading(get_string('users_list', 'quizaccess_proctoring'));

echo $OUTPUT->header();

$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 5, PARAM_INT);

$sql = "SELECT * FROM {user}";

$users = $DB->get_records_sql($sql, [], $perpage * $page, $perpage);

foreach ($users as $user) {
    $user->image_url = quizaccess_proctoring_get_image_url($user->id);
    if (strlen($user->image_url)) {
        $user->delete_image_url = $CFG->wwwroot . "/mod/quiz/accessrule/proctoring/delete_user_image.php?userid=$user->id&perpage=$perpage&page=$page";
        $user->edit_image_url = $CFG->wwwroot . "/mod/quiz/accessrule/proctoring/upload_image.php?id=$user->id";
    }
}

$totaluser = $DB->count_records('user');

$baseurl = new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php', array('perpage' => $perpage));

$templatecontext = (object)[
    'users' => array_values($users),
    'redirecturl' => new moodle_url('/mod/quiz/accessrule/proctoring/upload_image.php'),
    'settingsurl' => new moodle_url('/admin/settings.php?section=modsettingsquizcatproctoring')
];

echo $OUTPUT->render_from_template('quizaccess_proctoring/users_list', $templatecontext);

echo $OUTPUT->paging_bar($totaluser, $page, $perpage, $baseurl);

echo $OUTPUT->footer();
