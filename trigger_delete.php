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

// Check if the setting exists, and create it if it doesn't.
$settingexists = $DB->record_exists('config_plugins', ['plugin' => 'quizaccess_proctoring', 'name' => 'deletion_in_progress']);

if (!$settingexists) {
    // If the setting doesn't exist, create it with the default value of 0.
    $DB->insert_record('config_plugins', [
        'plugin' => 'quizaccess_proctoring',
        'name' => 'deletion_in_progress',
        'value' => '0'
    ]);
    mtrace('Setting "deletion_in_progress" was created with value 0.');
}

$DB->set_field('config_plugins', 'value', 1, ['plugin' => 'quizaccess_proctoring', 'name' => 'deletion_in_progress']);

// Redirect to the settings page
// After performing the delete operation, set the URL to redirect to the desired page.
$url = new moodle_url('/admin/settings.php', ['section' => 'modsettingsquizcatproctoring']);

// Redirect to the settings page with a success message.
redirect($url, get_string('settings:deleteallsuccess', 'quizaccess_proctoring'), null, \core\output\notification::NOTIFY_SUCCESS);
