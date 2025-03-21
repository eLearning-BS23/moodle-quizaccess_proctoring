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
 * Access for the quizaccess_proctoring plugin.
 *
 * This file defines the capabilities for the quizaccess_proctoring plugin in Moodle,
 * which allows monitoring and proctoring of quizzes by capturing webcam camshot,
 * viewing proctoring logs, and deleting them when needed. These capabilities ensure
 * that only authorized roles can perform certain actions on the proctoring logs and images.
 *
 * @package    quizaccess_proctoring
 * @category   access
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// List of capabilities for the quizaccess_proctoring plugin.
$capabilities = [
    // This allows the student or manager to send a webcam screenshot when proctoring is active.
    'quizaccess/proctoring:sendcamshot' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'student' => CAP_ALLOW, // Students are allowed to send camshot.
            'manager' => CAP_ALLOW, // Managers can also send camshot.
        ],
    ],

    // This capability allows teachers, editing teachers, and managers to view the proctoring report.
    'quizaccess/proctoring:viewreport' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'teacher' => CAP_ALLOW, // Teachers can view the report.
            'editingteacher' => CAP_ALLOW, // Editing teachers can view the report.
            'manager' => CAP_ALLOW, // Managers can view the report.
        ],
    ],

    // This capability allows editing teachers and managers to delete webcam camshot.
    'quizaccess/proctoring:deletecamshots' => [
        'riskbitmask' => RISK_DATALOSS, // Action involves potential data loss.
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW, // Editing teachers can delete camshot.
            'manager' => CAP_ALLOW, // Managers can delete camshot.
        ],
    ],

    // This capability allows teachers, editing teachers, and managers to analyze the webcam camshot.
    'quizaccess/proctoring:analyzeimages' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'teacher' => CAP_ALLOW, // Teachers can analyze images.
            'editingteacher' => CAP_ALLOW, // Editing teachers can analyze images.
            'manager' => CAP_ALLOW, // Managers can analyze images.
        ],
    ],
];
