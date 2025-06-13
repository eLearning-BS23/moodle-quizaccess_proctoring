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
require_once($CFG->libdir . '/tablelib.php');
global $CFG, $PAGE, $OUTPUT, $DB;

require_login();

if (!is_siteadmin()) {
    redirect($CFG->wwwroot, get_string('no_permission', 'quizaccess_proctoring'),
    null, \core\output\notification::NOTIFY_ERROR);
}

$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 10, PARAM_INT);
$search = optional_param('search', '', PARAM_TEXT);
$direction = optional_param('direction', 'asc', PARAM_ALPHA);

// Validate and Determine the sorting direction.
$direction = ($direction === 'asc') ? 'ASC' : 'DESC';

$PAGE->set_pagelayout('admin');
$PAGE->set_url('/mod/quiz/accessrule/proctoring/userslist.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('users_list', 'quizaccess_proctoring'));
$PAGE->set_heading(get_string('users_list', 'quizaccess_proctoring'));

echo $OUTPUT->header();

// Build SQL query with search filtering and exclude guest user.
$params = ['guestuser' => 'guest'];
$sql = "SELECT u.id, u.firstname, u.lastname, u.email, u.username, u.picture,
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

if ($direction === 'ASC') {
    $sql .= " ORDER BY u.firstname ASC";
} else {
    $sql .= " ORDER BY u.firstname DESC";
}

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
        $sesskey = sesskey();
        $user->delete_image_url =
        $CFG->wwwroot . "/mod/quiz/accessrule/proctoring/delete_user_image.php?userid=$user->id
        &perpage=$perpage&page=$page&sesskey=$sesskey";
        $user->edit_image_url = $CFG->wwwroot . "/mod/quiz/accessrule/proctoring/upload_image.php?id=$user->id&sesskey=$sesskey";
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
    'wwwroot' => $CFG->wwwroot,
    'direction' => ($direction == 'ASC') ? true : false,
    'pagination' => $page,
    'perpage' => $perpage,
];

echo html_writer::tag('button', get_string('back', 'quizaccess_proctoring'), [
    'type' => 'button',
    'class' => 'btn btn-secondary',
    'onclick' => 'window.history.back();'
]);
echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
$proversionlink = html_writer::link(
    'https://elearning23.com/moodle-proctoring-pro-details/',
    get_string('pro_version_title_text', 'quizaccess_proctoring'),
);
echo html_writer::tag('p', get_string('users_list_info_description', 'quizaccess_proctoring') . ' ' . $proversionlink);
echo $OUTPUT->box_end();

echo $OUTPUT->render_from_template('quizaccess_proctoring/users_list', $templatecontext);
echo $OUTPUT->paging_bar($totaluser, $page, $perpage, $baseurl);

$table = new flexible_table('my_custom_user_table');

// Define columns and headers
$table->define_columns(['fullname', 'email', 'status']);
$table->define_headers([
    get_string('fullnameuser'),
    get_string('email'),
    get_string('status')
]);

// Optional settings
$table->define_baseurl($PAGE->url);
$table->set_attribute('class', 'generaltable generalbox');
$table->set_attribute('id', 'my-custom-user-table');
$table->sortable(true, 'fullname', SORT_ASC);
$table->pageable(true);
$table->setup();

foreach ($users as $user) {
    $row = [];

    // Check if user has image
    if (!empty($user->image_url)) {
        // Show image
        $userpic = html_writer::empty_tag('img', [
            'src' => $user->image_url,
            'alt' => $fullname,
            'class' => 'userpicture',
            'style' => 'width: 35px; height: 35px; object-fit: cover;'
        ]);
    } else {
        // Show initials or full name in styled circle
        $initials = strtoupper($user->firstname[0] . $user->lastname[0]);
        $userpic = html_writer::span($initials, 'userpicture', [
            'style' => '
                background-color: #ccc;
                color: #fff;                
                padding: 8px;
                margin-right: 2px;
            '
        ]);
    }

    $fullname = fullname($user);
    $profileurl = new moodle_url('/user/view.php', ['id' => $user->id]);

    $usercell = html_writer::link($profileurl, $fullname, ['class' => 'd-inline-flex align-items-center gap-2']);

    $row[] = $userpic . ' ' . $usercell;
    $row[] = $user->email;

    $table->add_data($row);
}
$table->print_html();

echo $OUTPUT->footer();
