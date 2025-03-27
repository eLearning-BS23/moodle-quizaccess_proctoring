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
 * Quiz access proctoring plugin upgrade code
 *
 * @package     quizaccess_proctoring
 * @author      Brain station 23 ltd <brainstation-23.com>
 * @copyright   2020 Brain station 23 ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrades the database schema for the quizaccess_proctoring plugin.
 *
 * This function checks the old version of the plugin and applies necessary changes to the database schema, such as
 * adding new fields or tables, modifying existing ones, or performing other schema adjustments required for the upgrade.
 *
 * @param int $oldversion The version of the plugin we are upgrading from.
 *
 * @return bool True on success, false on failure.
 */
function xmldb_quizaccess_proctoring_upgrade($oldversion) {
    global $CFG, $DB;

    require_once($CFG->libdir.'/db/upgradelib.php'); // Core Upgrade-related functions.
    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2021061102) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('quizaccess_proctoring_logs');
        $field1 = new xmldb_field('awsscore', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $field2 = new xmldb_field('awsflag', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);

        // Conditionally launch add field forcedownload.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        upgrade_plugin_savepoint(true, 2021061102, 'quizaccess', 'proctoring');
    }

    if ($oldversion < 2021061104) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('proctoring_facematch_task');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, true, true, null, null);
        $table->add_field('refimageurl', XMLDB_TYPE_TEXT, '500', null, true, false, null, null);
        $table->add_field('targetimageurl', XMLDB_TYPE_TEXT, '500', null, true, false, null, null);
        $table->add_field('reportid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        // Conditionally launch create table for fees.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2021061104, 'quizaccess', 'proctoring');
    }

    if ($oldversion < 2021061106) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('proctoring_screenshot_logs');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, true, true, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('quizid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('screenshot', XMLDB_TYPE_TEXT, '10', null, true, false, null, null);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);

        $table->add_key('id', XMLDB_KEY_PRIMARY, ['id']);

        upgrade_plugin_savepoint(true, 2021061106, 'quizaccess', 'proctoring');
    }
    if ($oldversion < 2021071405) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('proctoring_fm_warnings');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, true, true, null, null);
        $table->add_field('reportid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('quizid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        // Conditionally launch create table for fees.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2021071405, 'quizaccess', 'proctoring');
    }

    if ($oldversion < 2021112601) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('proctoring_screenshot_logs');

        // Drop table proctoring_screenshot_logs.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }
        upgrade_plugin_savepoint(true, 2021112601, 'quizaccess', 'proctoring');
    }

    if ($oldversion < 2021112603) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('proctoring_user_images');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, true, true, null, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('photo_draft_id', XMLDB_TYPE_INTEGER, '20', null, true, false, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        // Conditionally launch create table for fees.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2021112603, 'quizaccess', 'proctoring');
    }

    if ($oldversion < 2021112604) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('proctoring_face_images');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, true, true, null, null);
        $table->add_field('parent_type', XMLDB_TYPE_CHAR, '20', null, true, false, 0, null);
        $table->add_field('parentid', XMLDB_TYPE_INTEGER, '20', null, true, false, 0, null);
        $table->add_field('faceimage', XMLDB_TYPE_TEXT, '256', null, true, false, null, null);
        $table->add_field('facefound', XMLDB_TYPE_INTEGER, '2', null, true, false, 0, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        // Conditionally launch create table for fees.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2021112604, 'quizaccess', 'proctoring');
    }

    if ($oldversion < 2024100102) {
        $table = new xmldb_table('proctoring_facematch_task');
        $dbman->rename_table($table, 'quizaccess_proctoring_facematch_task');

        upgrade_plugin_savepoint(true,  2024100102, 'quizaccess', 'proctoring');
    }
    if ($oldversion < 2024100103) {
        $table = new xmldb_table('proctoring_fm_warnings');
        $dbman->rename_table($table, 'quizaccess_proctoring_fm_warnings');
        $table = new xmldb_table('proctoring_user_images');
        $dbman->rename_table($table, 'quizaccess_proctoring_user_images');
        $table = new xmldb_table('proctoring_face_images');
        $dbman->rename_table($table, 'quizaccess_proctoring_face_images');

        upgrade_plugin_savepoint(true,  2024100103, 'quizaccess', 'proctoring');
    }

    if ($oldversion < 2024100104) {
        $table = new xmldb_table('aws_api_log');

        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Upgrade plugin version.
        upgrade_plugin_savepoint(true, 2024100104, 'quizaccess', 'proctoring');
    }

    if ($oldversion < 2025011005) {
        // Define field deletationprogress to be added to quizaccess_proctoring_logs.
        $table = new xmldb_table('quizaccess_proctoring_logs');
        $field = new xmldb_field('deletionprogress', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        // Check if the field exists, and if not, add it with default value 0.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2025011005, 'quizaccess', 'proctoring');
    }

    if ($oldversion < 2025030606) {
        // Fetch the scheduled task record that needs to be updated.
        $task = $DB->get_record('task_scheduled', ['classname' => '\quizaccess_proctoring\task\DeleteImagesTask']);
        // If the record exists, update it.
        if ($task) {
            $task->classname = '\quizaccess_proctoring\task\delete_images_task'; // New classname.
            $DB->update_record('task_scheduled', $task);
        }

        $task2 = $DB->get_record('task_scheduled', ['classname' => '\quizaccess_proctoring\task\ExecuteFacematchTask']);
        if ($task2) {
            $task2->classname = '\quizaccess_proctoring\task\execute_facematch_task'; // New classname.
            $DB->update_record('task_scheduled', $task2);
        }

        $task3 = $DB->get_record('task_scheduled', ['classname' => '\quizaccess_proctoring\task\InitiateFacematchTask']);
        if ($task3) {
            $task3->classname = '\quizaccess_proctoring\task\initiate_face_match_task'; // New classname.
            $DB->update_record('task_scheduled', $task3);
        }

        // Upgrade Moodle's internal version to mark the change.
        upgrade_plugin_savepoint(true, 2025030606, 'quizaccess', 'proctoring');
    }

    return true;
}
