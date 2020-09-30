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
 * Events for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23 <moodle@brainstation-23.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => '\mod_quiz\event\attempt_started',
        'callback' => 'quizaccess_proctoring\proctoring_observer::handle_quiz_attempt_started',
    ),
    array(
        'eventname' => '\mod_quiz\event\quiz_attempt_submitted',
        'callback' => 'quizaccess_proctoring\proctoring_observer::handle_quiz_attempt_submitted',
    ),
    array(
        'eventname' => 'quizaccess_proctoring\take_screenshot',
        'callback' => 'quizaccess_proctoring\proctoring_observer::take_screenshot',
    ),
);
