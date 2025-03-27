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
require_once($CFG->dirroot . '/mod/quiz/accessrule/proctoring/classes/form/image_upload_form.php');
require_once($CFG->dirroot . '/mod/quiz/accessrule/proctoring/lib.php');

use quizaccess_proctoring\form\image_upload_form;

$PAGE->set_url(new moodle_url('/mod/quiz/accessrule/proctoring/upload_image.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('upload_image_title', 'quizaccess_proctoring'));

$PAGE->set_heading(get_string('upload_image_heading', 'quizaccess_proctoring'));

// Add navigation nodes.
$PAGE->navbar->add(get_string('pluginname', 'quizaccess_proctoring'),
    new moodle_url('/admin/settings.php', ['section' => 'modsettingsquizcatproctoring']));
$PAGE->navbar->add(get_string('users_list', 'quizaccess_proctoring'),
    new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php'));
$PAGE->navbar->add(get_string('upload_image', 'quizaccess_proctoring'), $PAGE->url);

require_login();


if (!is_siteadmin()) {
    redirect($CFG->wwwroot, get_string('no_permission', 'quizaccess_proctoring'), null, \core\output\notification::NOTIFY_ERROR);
}

$PAGE->set_pagelayout('admin');

$userid = required_param('id', PARAM_INT);

$mform = new image_upload_form();

// Checking form.
if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/mod/quiz/accessrule/proctoring/userslist.php',
            get_string('cancel_image_upload', 'quizaccess_proctoring'),
            null,
            \core\output\notification::NOTIFY_INFO);
} else if ($data = $mform->get_data()) {
    require_sesskey();
    // Check if the image has face.
    if ($data->face_image == 'null'  || empty($data->face_image )) {
        redirect($CFG->wwwroot . '/mod/quiz/accessrule/proctoring/userslist.php',
                get_string('image_not_uploaded', 'quizaccess_proctoring'),
                null,
                \core\output\notification::NOTIFY_ERROR);
    }

    // Store or update $student.
    file_save_draft_area_files(
        $data->user_photo,
        $data->context_id,
        'quizaccess_proctoring',
        'user_photo',
        $data->id,
        [
            'subdirs' => 0,
            'maxfiles' => 50,
        ]
    );

    // Save the face image.
    $faceimagefile = new stdClass();
    $faceimagefile->filearea = 'face_image';
    $faceimagefile->component = 'quizaccess_proctoring';
    $faceimagefile->filepath = '';
    $faceimagefile->itemid = $userid;
    $faceimagefile->license = '';
    $faceimagefile->author = '';

    $context = context_system::instance();
    $fs = get_file_storage();
    $faceimagefile->filepath = file_correct_filepath($faceimagefile->filepath);

    // For base64 to file.
    $faceimagedata = $data->face_image;
    list(, $faceimagedata) = explode(';', $faceimagedata);

    // Get the face image url of admin uploaded image.
    $url = quizaccess_proctoring_geturl_of_faceimage($faceimagedata, $userid, $faceimagefile, $context, $fs);
    $facetablerecord = new stdClass();
    $facetablerecord->parent_type = 'admin_image';
    $facetablerecord->faceimage = "{$url}";
    $facetablerecord->facefound = 1;
    $facetablerecord->timemodified = time();

    if ($DB->record_exists_select('quizaccess_proctoring_user_images', 'user_id = :id', ['id' => $data->id])) {
        $record = $DB->get_record_select('quizaccess_proctoring_user_images', 'user_id = :id', ['id' => $data->id]);
        $record->photo_draft_id = $data->user_photo;
        $DB->update_record('quizaccess_proctoring_user_images', $record);

        // Save face image in face table.
        $facetablerecord->parentid = $record->id;

        if ($DB->record_exists('quizaccess_proctoring_face_images',
                                [
                                    'parentid' => $facetablerecord->parentid,
                                    'parent_type' => $facetablerecord->parent_type,
                                ])) {
            $facetablerow = $DB->get_record('quizaccess_proctoring_face_images',
                                        [
                                            'parentid' => $facetablerecord->parentid,
                                            'parent_type' => $facetablerecord->parent_type,
                                        ]);
            $facetablerecord->id = $facetablerow->id;
            $DB->update_record('quizaccess_proctoring_face_images', $facetablerecord);
        } else {
            $DB->insert_record('quizaccess_proctoring_face_images', $facetablerecord);
        }
        redirect($CFG->wwwroot . '/mod/quiz/accessrule/proctoring/userslist.php',
                get_string('image_updated', 'quizaccess_proctoring'),
                null,
                \core\output\notification::NOTIFY_SUCCESS);
    } else {
        $record = new stdClass;
        $record->user_id = $data->id;
        $record->photo_draft_id = $data->user_photo;
        $parentid = $DB->insert_record('quizaccess_proctoring_user_images', $record);

        $facetablerecord->parentid = $parentid;
        if ($DB->record_exists('quizaccess_proctoring_face_images',
                [
                    'parentid' => $facetablerecord->parentid,
                    'parent_type' => $facetablerecord->parent_type,
                ])) {
            $facetablerow = $DB->get_record('quizaccess_proctoring_face_images',
                                [
                                    'parentid' => $facetablerecord->parentid,
                                    'parent_type' => $facetablerecord->parent_type,
                                ]);
            $facetablerecord->id = $facetablerow->id;
            $DB->update_record('quizaccess_proctoring_face_images', $facetablerecord);
        } else {
            $DB->insert_record('quizaccess_proctoring_face_images', $facetablerecord);
        }
        redirect($CFG->wwwroot . '/mod/quiz/accessrule/proctoring/userslist.php',
                get_string('image_updated', 'quizaccess_proctoring'),
                null, \core\output\notification::NOTIFY_SUCCESS);
    }
}

$context = context_system::instance();
$username = $DB->get_record_select('user', 'id=:id', ['id' => $userid], 'firstname ,lastname');

// Prepare image file.
if (empty($user->id)) {
    $user = new stdClass;
    $user->id = $userid;
    $user->username = $username->firstname . ' ' . $username->lastname;
    $user->context_id = $context->id;
    $user->face_image = "";
}

$draftitemid = file_get_submitted_draft_itemid('user_photo');

file_prepare_draft_area(
    $draftitemid,
    $context->id,
    'quizaccess_proctoring',
    'user_photo',
    $user->id,
    [
        'subdirs' => 0,
        'maxfiles' => 1,
    ]
);

$user->user_photo = $draftitemid;

$mform->set_data($user);

$modelurl = $CFG->wwwroot . '/mod/quiz/accessrule/proctoring/thirdpartylibs/models';
$PAGE->requires->js("/mod/quiz/accessrule/proctoring/amd/build/face-api.min.js", true);
$PAGE->requires->js_call_amd('quizaccess_proctoring/validateAdminUploadedImage', 'setup', [$modelurl]);

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
