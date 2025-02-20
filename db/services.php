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
 * External services for the quizaccess_proctoring plugin.
 *
 * This file defines external services for the `quizaccess_proctoring` plugin, 
 * including methods for sending and retrieving webcam snapshots, 
 * as well as validating faces for proctoring purposes.
 *
 * @package    quizaccess_proctoring
 * @category   external_services
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die();

/**
 * List of external functions for the quizaccess_proctoring plugin.
 *
 * These functions handle communication with external systems, allowing
 * mobile apps and other services to interact with the proctoring functionality.
 *
 * @var array $functions
 */
$functions = [
    /**
     * Send a camera snapshot on the given session.
     *
     * This external function allows a camera snapshot to be sent to the server 
     * during a quiz attempt for proctoring purposes.
     *
     * @return array
     * @throws moodle_exception If the request fails or permissions are denied.
     */
    'quizaccess_proctoring_send_camshot' => [
        'classname'   => 'quizaccess_proctoring_external',
        'methodname'  => 'send_camshot',
        'description' => 'Send a camera snapshot on the given session.',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities'=> 'quizaccess/proctoring:sendcamshot',
        'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],

    /**
     * Get the list of camera snapshots in the given session.
     *
     * This external function retrieves all webcam snapshots taken during a 
     * quiz attempt for analysis by authorized users.
     *
     * @return array
     * @throws moodle_exception If the request fails or permissions are denied.
     */
    'quizaccess_proctoring_get_camshots' => [
        'classname'   => 'quizaccess_proctoring_external',
        'methodname'  => 'get_camshots',
        'description' => 'Get the list of camera snapshots in the given session.',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'quizaccess/proctoring:getcamshots',
        'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],

    /**
     * Send a camera snapshot to validate the face.
     *
     * This external function sends a camera snapshot for face validation 
     * during a quiz attempt to ensure the user is who they claim to be.
     *
     * @return array
     * @throws moodle_exception If the request fails or permissions are denied.
     */
    'quizaccess_proctoring_validate_face' => [
        'classname'   => 'quizaccess_proctoring_external',
        'methodname'  => 'validate_face',
        'description' => 'Send a camera snapshot to validate face.',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities'=> 'quizaccess/proctoring:sendcamshot',
        'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
];
