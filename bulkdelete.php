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
 * Bulk Delete for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/lib/tablelib.php');
require_once(__DIR__ . '/classes/additional_settings_helper.php');
use quizaccess_proctoring\additional_settings_helper;

require_login();

// Get parameters.
$cmid = required_param('cmid', PARAM_INT);
$type = required_param('type', PARAM_TEXT);
$id = required_param('id', PARAM_INT);
$sesskey = required_param('sesskey', PARAM_ALPHANUM);
if (!confirm_sesskey($sesskey)) {
    throw new moodle_exception('invalidsesskey', 'quizaccess_proctoring');
}

// Make sure debugging is not interfering with redirection.
$context = context_module::instance($cmid, MUST_EXIST);
// Ensure the user has the required capability to delete camshots.
if (!has_capability('quizaccess/proctoring:deletecamshots', $context)) {
    // Show a notification and redirect back to the previous page (or a specific page).
    $url = new moodle_url('/mod/quiz/view.php', ['id' => $cmid]);  // Redirects to the quiz page.
    $message = get_string('nopermission', 'quizaccess_proctoring');  // You can create a string in lang file for 'nopermission'.

    // Show the notification.
    \core\notification::error($message);

    // Redirect with the notification.
    redirect($url);
}

$params = [
    'cmid' => $cmid,
    'type' => $type,
    'id' => $id,
];

// Check the type and prepare URL for redirect.
if ($type == 'course' || $type == 'quiz') {

    $helper = new additional_settings_helper();

    if ($type == 'course') {
        $camshotdata = $helper->searchbycourseid($id);
    } else if ($type == 'quiz') {
        $camshotdata = $helper->searchbyquizid($id);
    }

    if (empty($camshotdata)) {
        // If no data is found, show an error message.
        throw new moodle_exception('nodata', 'quizaccess_proctoring');
    }

    $rowids = [];
    foreach ($camshotdata as $row) {
        array_push($rowids, $row->id);
    }

    $rowidstring = implode(',', $rowids);
    $helper->deletelogs($rowidstring);

    // Redirect before any output is made.
    $params = [
        'cmid' => $cmid,
    ];
    $url = new moodle_url('/mod/quiz/accessrule/proctoring/proctoringsummary.php', $params);
    redirect($url, get_string('settings:deleteallsuccess', 'quizaccess_proctoring'), -11, 'success');
} else {
    // Invalid type, show error message.
    throw new moodle_exception('invalidtype', 'quizaccess_proctoring');
}
