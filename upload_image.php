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
 * @copyright  2022 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/mod/quiz/accessrule/proctoring/classes/form/upload_image_form.php');

$PAGE->set_url(new moodle_url('/mod/quiz/accessrule/proctoring/upload_image.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('upload_image_title', 'quizaccess_proctoring'));

require_login();

if (!is_siteadmin()) {
    redirect($CFG->wwwroot, get_string('no_permission', 'quizaccess_proctoring'), null, \core\output\notification::NOTIFY_ERROR);
}

// Instantiate imageupload_form
$mform = new imageupload_form();

// checking form
if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '', get_string('cancel_image_upload', 'quizaccess_proctoring'), null, \core\output\notification::NOTIFY_INFO);
} else if ($data = $mform->get_data()) {
    // ... store or update $student
    file_save_draft_area_files(
        $data->user_photo,
        $data->context_id,
        'quizaccess_proctoring',
        'user_photo',
        $data->id,
        array('subdirs' => 0, 'maxfiles' => 50)
    );

    if ($DB->record_exists_select('proctoring_user_images', 'user_id = :id', array('id' => $data->id))) {
        $record = $DB->get_record_select('proctoring_user_images', 'user_id = :id', array('id' => $data->id));
        $record->photo_draft_id = $data->user_photo;
        $DB->update_record('proctoring_user_images', $record);
        redirect($CFG->wwwroot . '/mod/quiz/accessrule/proctoring/userslist.php', get_string('image_updated', 'quizaccess_proctoring'), null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        $record = new stdClass;
        $record->user_id = $data->id;
        $record->photo_draft_id = $data->user_photo;
        $DB->insert_record('proctoring_user_images', $record);
        redirect($CFG->wwwroot . '/mod/quiz/accessrule/proctoring/userslist.php', get_string('image_updated', 'quizaccess_proctoring'), null, \core\output\notification::NOTIFY_SUCCESS);
    }
}

$userid = optional_param('id', -1, PARAM_INT);

$context = context_system::instance();
$username = $DB->get_record_select('user', 'id=:id', array('id' => $userid), 'firstname ,lastname');

// prepare image file
if (empty($user->id)) {
    $user = new stdClass;
    $user->id = $userid;
    $user->username = $username->firstname . ' ' . $username->lastname;
    $user->context_id = $context->id;
}

$draftitemid = file_get_submitted_draft_itemid('user_photo');

file_prepare_draft_area(
    $draftitemid,
    $context->id,
    'quizaccess_proctoring',
    'user_photo',
    $user->id,
    array('subdirs' => 0, 'maxfiles' => 1)
);

$user->user_photo = $draftitemid;

$mform->set_data($user);

$modelurl = $CFG->wwwroot . '/mod/quiz/accessrule/proctoring/thirdpartylibs/models';
$PAGE->requires->js("/mod/quiz/accessrule/proctoring/amd/build/face-api.min.js", true);
$PAGE->requires->js_call_amd('quizaccess_proctoring/validateAdminUploadedImage', 'setup', array($modelurl));

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
