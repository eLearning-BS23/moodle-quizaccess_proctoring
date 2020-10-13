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
 * Helper for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace quizaccess_proctoring;


use CFPropertyList\CFPropertyList;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper class.
 *
 * @copyright  2020 Brain Station 23
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {
    /**
     * Get a filler icon for display in the actions column of a table.
     *
     * @param string $url The URL for the icon.
     * @param string $icon The icon identifier.
     * @param string $alt The alt text for the icon.
     * @param string $iconcomponent The icon component.
     * @param array $options Display options.
     * @return string
     */
    public static function format_icon_link($url, $icon, $alt, $iconcomponent = 'moodle', $options = array()) {
        global $OUTPUT;

        return $OUTPUT->action_icon(
            $url,
            new \pix_icon($icon, $alt, $iconcomponent, [
                'title' => $alt,
            ]),
            null,
            $options
        );
    }

    /**
     * Validate proctoring config string.
     *
     * @param string $proctoringconfig
     * @return bool
     */
    public static function is_valid_proctoring_config(string $proctoringconfig) : bool {
        $result = true;

        set_error_handler(function($errno, $errstr, $errfile, $errline ){
            throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
        });

        $plist = new CFPropertyList();
        try {
            $plist->parse($proctoringconfig);
        } catch (\ErrorException $e) {
            $result = false;
        } catch (\Exception $e) {
            $result = false;
        }

        restore_error_handler();

        return $result;
    }

    /**
     * A helper function to get a list of proctoring config file headers.
     *
     * @param int|null $expiretime  Unix timestamp
     * @return array
     */
    public static function get_proctoring_file_headers(int $expiretime = null) : array {
        if (is_null($expiretime)) {
            $expiretime = time();
        }
        $headers = [];
        $headers[] = 'Cache-Control: private, max-age=1, no-transform';
        $headers[] = 'Expires: '. gmdate('D, d M Y H:i:s', $expiretime) .' GMT';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Content-Disposition: attachment; filename=config.proctoring';
        $headers[] = 'Content-Type: application/proctoring';

        return $headers;
    }

    /**
     * Get proctoring config content for a particular quiz. This method checks caps.
     *
     * @param string $cmid The course module ID for a quiz with config.
     * @return string SEB config string.
     */
    public static function get_proctoring_config_content(string $cmid) : string {
        // Try and get the course module.
        $cm = get_coursemodule_from_id('quiz', $cmid, 0, false, MUST_EXIST);

        // Make sure the user is logged in and has access to the module.
        require_login($cm->course, false, $cm);

        // Retrieve the config for quiz.
        $config = quiz_settings::get_config_by_quiz_id($cm->instance);
        if (empty($config)) {
            throw new \moodle_exception('noconfigfound', 'quizaccess_proctoring', '', $cm->id);
        }
        return $config;
    }

    /**
     * Serve a file to browser for download.
     *
     * @param string $contents Contents of file.
     */
    public static function send_proctoring_config_file(string $contents) {
        // We can now send the file back to the browser.
        foreach (self::get_proctoring_file_headers() as $header) {
            header($header);
        }

        echo($contents);
    }

}

