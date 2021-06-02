<?php
require_once (__DIR__ . '/../../../../config.php');
// No guest autologin.
require_login(0, false);

// Get URL parameters.
$systemcontext = context_system::instance();
$contextid = optional_param('context', $systemcontext->id, PARAM_INT);

// Check permissions.
list($context, $course, $cm) = get_context_info_array($contextid);
require_login($course, false, $cm);
require_capability('quizaccess/proctoring:deletecamshots', $context);

$pageurl = new moodle_url('/mod/quiz/accessrule/proctoring/externalsettings.php');
$PAGE->set_url($pageurl);

$DB->set_field('quizaccess_proctoring_logs', 'userid', 0);

// Delete users file (webcam images).
$filesql = 'SELECT * FROM {files} WHERE component = \'quizaccess_proctoring\' AND filearea = \'picture\'';

$usersfile = $DB->get_records_sql($filesql);

$fs = get_file_storage();
foreach ($usersfile as $file):
    // Prepare file record object
    $fileinfo = array(
        'component' => 'quizaccess_proctoring',
        'filearea' => 'picture',     // Usually = table name.
        'itemid' => $file->itemid,               // Usually = ID of row in table.
        'contextid' => $context->id, // ID of context.
        'filepath' => '/',           // any path beginning and ending in /.
        'filename' => $file->filename); // any filename.

    // Get file
    $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
        $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

    // Delete it if it exists
    if ($file) {
        $file->delete();
    }
endforeach;
$url = new moodle_url('/');
redirect($url, get_string('settings:deleteallsuccess', 'quizaccess_proctoring'), -11,'success');