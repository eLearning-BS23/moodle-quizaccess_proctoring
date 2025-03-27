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
 * Delete Images for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');

// No guest autologin.
require_login();
require_sesskey();

if (!is_siteadmin()) {
    redirect($CFG->wwwroot, get_string('no_permission', 'quizaccess_proctoring'), null, \core\output\notification::NOTIFY_ERROR);
}

// Get URL parameters.
$systemcontext = context_system::instance();
$contextid = optional_param('context', $systemcontext->id, PARAM_INT);

// Check permissions.
list($context, $course, $cm) = get_context_info_array($contextid);

require_login($course, false, $cm);
has_capability('quizaccess/proctoring:deletecamshots', $context);

// Updating the proctoring logs.
$DB->set_field('quizaccess_proctoring_logs', 'deletionprogress', 1);

// Redirect to the settings page.
$url = new moodle_url('/admin/settings.php', ['section' => 'modsettingsquizcatproctoring']);

// Redirect to the settings page with a success message.
redirect($url, get_string('settings:deleteallsuccess', 'quizaccess_proctoring'), null, \core\output\notification::NOTIFY_SUCCESS);
