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
 * Link Generator for the quizaccess_proctoring plugin.
 *
 * This class is responsible for generating a link to the proctoring report page
 * with support for both secure (HTTPS) and non-secure (HTTP) protocols. Additionally,
 * it can generate a custom URL for the proctoring protocol.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace quizaccess_proctoring;

use moodle_url;

/**
 * Class quizaccess_proctoring_link_generator
 *
 * This class provides a method to generate a link to the proctoring report page for quizzes.
 * The link can be customized to use either the proctoring:// scheme or the standard HTTP/HTTPS protocol.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class link_generator {
    /**
     * Get a link to force the download of the file over https or proctorings protocols.
     *
     * This method generates a URL to the proctoring report page for a specific quiz, with options to
     * use either the proctoring:// protocol or standard HTTPS/HTTP based on the provided parameters.
     *
     * @param string $courseid Course ID.
     * @param string $cmid Course module ID.
     * @param bool $proctoring Whether to use a proctoring:// scheme or fall back to http:// scheme.
     * @param bool $secure Whether to use HTTPS or HTTP protocol.
     * @return string A URL for the proctoring report page.
     */
    public static function get_link(string $courseid, string $cmid, $proctoring = false, $secure = true): string {
        // Check if course module exists.
        get_coursemodule_from_id('quiz', $cmid, 0, false, MUST_EXIST);

        $url = new moodle_url('/mod/quiz/accessrule/proctoring/report.php?courseid=' . $courseid.'&cmid=' . $cmid);
        if ($proctoring) {
            $secure ? $url->set_scheme('proctorings') : $url->set_scheme('proctoring');
        } else {
            $secure ? $url->set_scheme('https') : $url->set_scheme('http');
        }
        return $url->out();
    }
}
