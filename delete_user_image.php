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
 * Delete User Images for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . '/lib.php');
global $CFG, $DB, $PAGE;
// No guest autologin.
require_login(0, false);

// Get URL parameters.
$systemcontext = context_system::instance();
$contextid = optional_param('context', $systemcontext->id, PARAM_INT);
$userid = required_param('userid', PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 1, PARAM_INT);
$pageurl = new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php');
$PAGE->set_url($pageurl);

$imagefile = quizaccess_proctoring_get_image_file($userid);
if ($imagefile) {
    $imagefile->delete();
}
$url = new moodle_url("/mod/quiz/accessrule/proctoring/userslist.php?perpage=$perpage&page=$page");
redirect($url, get_string('settings:deleteuserimagesuccess', 'quizaccess_proctoring'), -11, 'success');
