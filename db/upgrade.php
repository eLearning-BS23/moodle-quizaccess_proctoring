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
 * This file keeps track of upgrades to Moodle.
 *
 * Sometimes, changes between versions involve
 * alterations to database structures and other
 * major things that may break installations.
 *
 * The upgrade function in this file will attempt
 * to perform all the necessary actions to upgrade
 * your older installation to the current version.
 *
 * If there's something it cannot do itself, it
 * will tell you what you need to do.
 *
 * The commands in here will all be database-neutral,
 * using the methods of database_manager class
 *
 * Please do not forget to use upgrade_set_timeout()
 * before any action that may take longer time to finish.
 *
 * @package   core_install
 * @category  upgrade
 * @copyright 2006 onwards Martin Dougiamas  http://dougiamas.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Main upgrade tasks to be executed on Moodle version bump
 *
 * This function is automatically executed after one bump in the Moodle core
 * version is detected. It's in charge of performing the required tasks
 * to raise core from the previous version to the next one.
 *
 * It's a collection of ordered blocks of code, named "upgrade steps",
 * each one performing one isolated (from the rest of steps) task. Usually
 * tasks involve creating new DB objects or performing manipulation of the
 * information for cleanup/fixup purposes.
 *
 * Each upgrade step has a fixed structure, that can be summarised as follows:
 *
 * if ($oldversion < XXXXXXXXXX.XX) {
 *     // Explanation of the update step, linking to issue in the Tracker if necessary
 *     upgrade_set_timeout(XX); // Optional for big tasks
 *     // Code to execute goes here, usually the XMLDB Editor will
 *     // help you here. See {@link http://docs.moodle.org/dev/XMLDB_editor}.
 *     upgrade_main_savepoint(true, XXXXXXXXXX.XX);
 * }
 *
 * All plugins within Moodle (modules, blocks, reports...) support the existence of
 * their own upgrade.php file, using the "Frankenstyle" component name as
 * defined at {@link http://docs.moodle.org/dev/Frankenstyle}, for example:
 *     - {@link xmldb_page_upgrade($oldversion)}. (modules don't require the plugintype ("mod_") to be used.
 *     - {@link xmldb_auth_manual_upgrade($oldversion)}.
 *     - {@link xmldb_workshopform_accumulative_upgrade($oldversion)}.
 *     - ....
 *
 * In order to keep the contents of this file reduced, it's allowed to create some helper
 * functions to be used here in the {@link upgradelib.php} file at the same directory. Note
 * that such a file must be manually included from upgrade.php, and there are some restrictions
 * about what can be used within it.
 *
 * For more information, take a look to the documentation available:
 *     - Data definition API: {@link http://docs.moodle.org/dev/Data_definition_API}
 *     - Upgrade API: {@link http://docs.moodle.org/dev/Upgrade_API}
 *
 * @param int $oldversion
 * @return bool always true
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

        upgrade_plugin_savepoint(true, 2021061102, 'mod', 'quizaccess_proctoring');
    }

    if ($oldversion < 2021061104) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('proctoring_facematch_task');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, true, true, null, null);
        $table->add_field('refimageurl', XMLDB_TYPE_TEXT, '500', null, true, false, null, null);
        $table->add_field('targetimageurl', XMLDB_TYPE_TEXT, '500', null, true, false, null, null);
        $table->add_field('reportid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        // Conditionally launch create table for fees.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2021061104, 'mod', 'quizaccess_proctoring');
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

        $table->add_key('id', XMLDB_KEY_PRIMARY, array('id'));

        upgrade_plugin_savepoint(true, 2021061106, 'mod', 'quizaccess_proctoring');
    }

    if ($oldversion < 2021070702) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('aws_api_log');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, true, true, null, null);
        $table->add_field('reportid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('apiresponse', XMLDB_TYPE_TEXT, '1000', null, true, false, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        // Conditionally launch create table for fees.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2021070702, 'mod', 'quizaccess_proctoring');
    }

    if ($oldversion < 2021071405) {
        // Define field output to be added to task_log.
        $table = new xmldb_table('proctoring_fm_warnings');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, true, true, null, null);
        $table->add_field('reportid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('quizid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, true, false, 0, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        // Conditionally launch create table for fees.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2021071405, 'mod', 'quizaccess_proctoring');
    }

    return true;
}
