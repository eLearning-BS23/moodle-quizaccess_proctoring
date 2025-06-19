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
$perpage = optional_param('perpage', 30, PARAM_INT);
$search = optional_param('search', '', PARAM_TEXT);
$sort = optional_param('tsort', '', PARAM_ALPHANUMEXT);
$dir = optional_param('tdir', 4, PARAM_INT);

$PAGE->set_pagelayout('admin');
$PAGE->set_url('/mod/quiz/accessrule/proctoring/userslist.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('users_list', 'quizaccess_proctoring'));
$PAGE->set_heading(get_string('users_list', 'quizaccess_proctoring'));

$PAGE->requires->js_call_amd('quizaccess_proctoring/userpic_modal', 'init');

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

// Validate and Determine the sorting direction.
$direction = ($dir == 4 || $dir == 0) ? 'ASC' : 'DESC';

$sortsql = '';
if (!in_array($sort, ['firstname', 'lastname', 'email'])) {
    // If the sort parameter is invalid, default to sorting by firstname.
    $sortsql = 'firstname';
} else {
    // If the sort parameter is valid, use it.
    $sortsql = $sort;
}

// Complete the SQL query with sorting and pagination.
$sql .= " ORDER BY u.$sortsql $direction";

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

// Set the base URL for pagination and sorting.
$baseurl = new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php',
        ['perpage' => $perpage, 'search' => $search, 'tdir' => $dir]);

// Create a flexible table instance for displaying user data.
$table = new flexible_table('quizaccess_proctoring_user_table');

// Define columns and headers for the table.
$table->define_columns(['fullname', 'email', 'actions']);
$table->define_headers([
    get_string('fullnameuser'),
    get_string('email'),
    get_string('actions'),
]);

// Additional settings for the table.
$table->define_baseurl($baseurl);
$table->set_attribute('class', 'generaltable generalbox');
$table->set_attribute('id', 'quizaccess_proctoring_user_table');
$table->sortable(true, 'fullname', SORT_ASC);
$table->no_sorting('actions'); // Actions column should not be sortable.
$table->pageable(true);
$table->setup();

// Process users.
foreach ($users as $user) {
    // Full name of the user.
    $fullname = fullname($user);

    // Prepare user data for display.
    $row = [];
    $userpic = '';

    // Prepare the action menu for each user.
    $actionmenu = new action_menu();
    $actionmenu->set_kebab_trigger(get_string('actions'));

    // Process image URLs.
    $user->image_url = quizaccess_proctoring_get_image_url($user->id);
    if (!empty($user->image_url)) {
        $sesskey = sesskey();

        // Add Edit action to the action menu.
        $editimageurl = new moodle_url($CFG->wwwroot . '/mod/quiz/accessrule/proctoring/upload_image.php', [
            'id' => $user->id,
            'sesskey' => $sesskey,
        ]);

        // Prepare image tag with user picture.
        $userpic = html_writer::empty_tag('img', [
            'src' => $user->image_url,
            'alt' => $fullname,
            'class' => 'userpicture',
            'style' => 'width: 35px; height: 35px; object-fit: cover;',
            'loading' => 'lazy',
        ]);

        $userpic = html_writer::tag('span', $userpic, [
            'class' => 'userpic-modal-trigger',
            'data-userfullname' => $fullname,
            'data-imgsrc' => $user->image_url,
            'role' => 'link',
            'tabindex' => '0',
            'style' => 'cursor: pointer;',
        ]);

        $editaction = new action_menu_link_secondary(
            $editimageurl,
            new pix_icon('t/edit', '', 'moodle'),
            get_string('edit')
        );
        $actionmenu->add($editaction);

        // Add Delete action to the action menu.
        $deleteimageurl = new moodle_url($CFG->wwwroot . '/mod/quiz/accessrule/proctoring/delete_user_image.php', [
            'userid' => $user->id,
            'perpage' => $perpage,
            'page' => $page,
            'sesskey' => $sesskey,
        ]);

        // Prepare attributes for the delete action.
        $attributes = [
            'data-confirmation' => 'modal',
            'data-confirmation-type' => 'delete',
            'data-confirmation-title-str' => json_encode(['delete', 'core']),
            'data-confirmation-content-str' => json_encode(['areyousure_delete_image', 'quizaccess_proctoring']),
            'data-confirmation-yes-button-str' => json_encode(['delete', 'core']),
            'data-confirmation-action-url' => $deleteimageurl->out(false),
            'data-confirmation-destination' => $deleteimageurl->out(false),
            'class' => 'text-danger',
        ];

        $deleteaction = new action_menu_link_secondary(
            $deleteimageurl,
            new pix_icon('t/delete', '', 'moodle'),
            get_string('delete'),
            $attributes
        );

        $actionmenu->add($deleteaction);

    } else {
        // If no image is available, provide an upload option.
        $uploadimageurl = new moodle_url('/mod/quiz/accessrule/proctoring/upload_image.php', [
            'id' => $user->id,
        ]);


        // Show initials or full name in styled circle if no image is available.
        $initials = strtoupper($user->firstname[0] . $user->lastname[0]);
        $userpic = html_writer::span($initials, 'userpicture', [
            'style' => '
                background-color: #e9ecef;
                color: #343a40;
                padding: 8px;
                margin-right: 2px;
            ',
        ]);

         // Wrap the image in a link to the upload page.
        $userpic = html_writer::link($uploadimageurl, $userpic, [
            'class' => 'text-decoration-none',
        ]);

        $uploadaction = new action_menu_link_secondary(
            $uploadimageurl,
            new pix_icon('i/cloudupload', '', 'moodle'),
            get_string('upload')
        );

        $actionmenu->add($uploadaction);
    }

    // Prepare the profile link and user name.
    $profileurl = new moodle_url('/user/view.php', ['id' => $user->id]);
    $usercell = html_writer::link($profileurl, $fullname, ['class' => 'd-inline-flex align-items-center gap-2']);

    // Combine user picture and name.
    $row[] = $userpic . ' ' . $usercell;
    $row[] = $user->email;

    // Finally, render the menu and add it to the row.
    $row[] = $OUTPUT->render($actionmenu);

    $table->add_data($row);
}

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

$onclick = (empty($search) && empty($page) && ($page != 0))
    ? 'window.history.back();'
    : 'window.location.href="' . new moodle_url('/admin/settings.php', ['section' => 'modsettingsquizcatproctoring']) . '";';

echo html_writer::tag('button', get_string('back', 'quizaccess_proctoring'), [
    'type' => 'button',
    'class' => 'btn btn-secondary',
    'onclick' => $onclick,
]);
echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
$proversionlink = html_writer::link(
    'https://elearning23.com/moodle-proctoring-pro-details/',
    get_string('pro_version_title_text', 'quizaccess_proctoring'),
);
echo html_writer::tag('p', get_string('users_list_info_description', 'quizaccess_proctoring') . ' ' . $proversionlink);
echo $OUTPUT->box_end();

echo $OUTPUT->render_from_template('quizaccess_proctoring/users_list', $templatecontext);
$table->print_html();
echo $OUTPUT->paging_bar($totaluser, $page, $perpage, $baseurl);
echo $OUTPUT->footer();
