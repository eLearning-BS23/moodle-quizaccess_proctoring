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
 * Screenshot for the quizaccess_proctoring plugin.
 *
 * This class defines the properties and methods related to the screenshot functionality
 * in the proctoring plugin. It extends the `persistent` class to handle proctoring logs
 * for quiz attempts.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_proctoring;
use core\persistent;

/**
 * Screenshot class for the proctoring plugin.
 *
 * This class represents a screenshot record in the `quizaccess_proctoring_logs` table.
 * It defines the properties of the record, such as course ID, quiz ID, user ID,
 * webcam picture, proctoring status, and the time the record was last modified.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class screenshot extends persistent {

    /**
     * Return the definition of the properties of this model.
     *
     * This method defines the properties and their respective types for the `screenshot` class.
     * It is used to interact with the `quizaccess_proctoring_logs` table.
     *
     * @return array The properties and their types for the screenshot model.
     */
    protected static function define_properties() {
        return [
            'courseid' => [
                'type' => PARAM_INT,
                'default' => '',
            ],
            'quizid' => [
                'type' => PARAM_INT,
                'default' => '',
            ],
            'userid' => [
                'type' => PARAM_INT,
                'default' => '',
            ],
            'webcampicture' => [
                'type' => PARAM_TEXT,
            ],
            'status' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'timemodified' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
        ];
    }
}
