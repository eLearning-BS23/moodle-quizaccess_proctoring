<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Upload image from users list in quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . '/lib.php');
global $CFG, $PAGE, $OUTPUT, $DB;

require_login();

if (!is_siteadmin()) {
    redirect($CFG->wwwroot, get_string('no_permission', 'quizaccess_proctoring'),
    null, \core\output\notification::NOTIFY_ERROR);
}

$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 5, PARAM_INT);
$search = optional_param('search', '', PARAM_TEXT);
$direction = optional_param('direction', 'asc', PARAM_ALPHA);

// Validate and Determine the sorting direction.
$direction = ($direction === 'asc') ? 'ASC' : 'DESC';

$PAGE->set_pagelayout('admin');
$PAGE->set_url('/mod/quiz/accessrule/proctoring/userslist.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('users_list', 'quizaccess_proctoring'));
$PAGE->set_heading(get_string('users_list', 'quizaccess_proctoring'));

// Add navigation nodes.
$PAGE->navbar->add(get_string('pluginname', 'quizaccess_proctoring'),
    new moodle_url('/admin/settings.php?section=modsettingsquizcatproctoring'));
$PAGE->navbar->add(get_string('users_list', 'quizaccess_proctoring'), $PAGE->url);

echo $OUTPUT->header();

// Build SQL query with search filtering and exclude guest user.
$params = ['guestuser' => 'guest'];
$sql = "SELECT u.id, u.firstname, u.lastname, u.username, u.picture,
            u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename
        FROM {user} u
        WHERE u.username != :guestuser";

if (!empty($search) && is_string($search)) {
    $sql .= " AND (u.firstname LIKE :search1 OR u.lastname LIKE :search2 OR
            u.email LIKE :search3 OR u.username LIKE :search4)";
    $params['search1'] = "%$search%";
    $params['search2'] = "%$search%";
    $params['search3'] = "%$search%";
    $params['search4'] = "%$search%";
}

$sql .= " ORDER BY u.firstname $direction";

// Get user records based on the SQL query.
$users = $DB->get_records_sql($sql, $params, $perpage * $page, $perpage);

// Count total users based on search filter, excluding guest user.
if (!empty($search)) {
    $sql = "SELECT COUNT(*)
            FROM {user}
            WHERE username != :guestuser
            AND (firstname LIKE :search1
                 OR lastname LIKE :search2
                 OR email LIKE :search3
                 OR username LIKE :search4)";
    $totaluser = $DB->count_records_sql($sql, $params);

} else {
    $totaluser = $DB->count_records_select('user', "username != :guestuser", $params);
}

// Check if no users were found.
if (empty($users)) {
    // Display Moodle's default "no results found" page with a back link.
    notice(
        get_string('nousersfound', 'quizaccess_proctoring'),
        new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php')
    );
}

// Process users.
foreach ($users as $user) {
    // Get full name.
    $user->fullname = fullname($user);

    // Process image URLs.
    $user->image_url = quizaccess_proctoring_get_image_url($user->id);
    if (!empty($user->image_url)) {
        $user->delete_image_url =
        $CFG->wwwroot . "/mod/quiz/accessrule/proctoring/delete_user_image.php?userid=$user->id&perpage=$perpage&page=$page";
        $user->edit_image_url = $CFG->wwwroot . "/mod/quiz/accessrule/proctoring/upload_image.php?id=$user->id";
    }
}

$baseurl = new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php',
        ['perpage' => $perpage, 'search' => $search,
        'direction' => ($direction === 'ASC') ? 'asc' : 'desc']);

$proctoringpro = new moodle_url('/mod/quiz/accessrule/proctoring/proctoring_pro_promo.php');
$proctoringprogif = $OUTPUT->image_url('proctoring_pro_users_list', 'quizaccess_proctoring');

$templatecontext = (object)[
    'users' => array_values($users),
    'redirecturl' => new moodle_url('/mod/quiz/accessrule/proctoring/upload_image.php'),
    'settingsurl' => new moodle_url('/admin/settings.php?section=modsettingsquizcatproctoring'),
    'searchvalue' => $search,
    'action' => new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php'),
    'btnclass' => "btn-primary",
    'inputname' => "search",
    'searchstring' => "Search user",
    'proctoringpro' => $proctoringpro,
    'proctoringprogif' => $proctoringprogif,
    'buyproctoringpro' => get_string('buyproctoringpro', 'quizaccess_proctoring'),
    'wwwroot' => $CFG->wwwroot,
    'direction' => ($direction == 'ASC') ? true : false,
    'pagination' => $page,
    'perpage' => $perpage,
];

echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
echo html_writer::tag('p', get_string('users_list_info_description', 'quizaccess_proctoring'));
echo $OUTPUT->box_end();

echo $OUTPUT->render_from_template('quizaccess_proctoring/users_list', $templatecontext);
echo $OUTPUT->paging_bar($totaluser, $page, $perpage, $baseurl);

echo $OUTPUT->footer();
