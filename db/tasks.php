<?php
// This file is part of the Zoom plugin for Moodle - http://moodle.org/
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
 * List of scheduled tasks for the quizaccess_proctoring plugin.
 *
 * This file defines the scheduled tasks for the `quizaccess_proctoring` plugin.
 * The tasks include processes related to proctoring, such as initiating face match,
 * executing the face match, and deleting images. These tasks are scheduled to run
 * periodically based on the configurations in the array below.
 *
 * The tasks are as follows:
 * 1. Initiate face match task: Periodically initiates the face match process.
 * 2. Execute face match task: Periodically runs the face match process for verification.
 * 3. Delete images task: Periodically deletes face match images to free up space.
 *
 * The `disabled` or `enabled` flags control whether the tasks are active or not.
 * The `minute`, `hour`, `day`, `month`, and `dayofweek` parameters define the execution intervals for the tasks.
 *
 * @package    quizaccess_proctoring
 * @category   task
 * @copyright  2024 Brain station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// List of scheduled tasks for the proctoring plugin.
$tasks = [
    // This task is responsible for initiating the face match process periodically (every 5 minutes).
    [
        'classname' => 'quizaccess_proctoring\task\initiate_facematch_task',
        'blocking'  => 0,
        'minute'    => '*/5', // Run every 5 minutes.
        'hour'      => '*',
        'day'       => '*',
        'month'     => '*',
        'dayofweek' => '*',
        'enable'    => false,
        'disabled'  => 1, // Task is disabled by default.
    ],

    // This task runs the face match verification process at regular intervals (every 2 minutes).
    [
        'classname' => 'quizaccess_proctoring\task\execute_facematch_task',
        'blocking'  => 0,
        'minute'    => '*/2', // Run every 2 minutes.
        'hour'      => '*',
        'day'       => '*',
        'month'     => '*',
        'dayofweek' => '*',
        'disabled'  => 1, // Task is disabled by default.
    ],

    // This task runs every minute and ensures that quiz images deleted periodically to free up storage.
    [
        'classname' => 'quizaccess_proctoring\task\delete_images_task',
        'blocking'  => 0,
        'minute'    => '*', // Run every minute.
        'hour'      => '*',
        'day'       => '*',
        'month'     => '*',
        'dayofweek' => '*',
        'enabled'   => 1, // Task is enabled by default.
    ],
];
