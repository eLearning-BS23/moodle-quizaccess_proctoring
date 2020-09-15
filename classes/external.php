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
 * Extrarnal for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23 <moodle@brainstation-23.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');

/**
 * External class.
 *
 * @copyright  2020 Brain Station 2
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_proctoring_external extends external_api
{

    public static function get_camshots_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_ALPHANUM, 'camshot course id'),
                'quizid' => new external_value(PARAM_ALPHANUM, 'camshot quiz id'),
                'userid' => new external_value(PARAM_ALPHANUM, 'camshot user id')
            )
        );
    }

    public static function get_camshots($courseid, $quizid ='', $userid) {
        global $DB;

        $warnings = array();

        if ($quizid) {
            $camshots = $DB->get_records('quizaccess_proctoring_logs', 
            array('courseid' => $courseid, 'quizid' => $quizid, 'userid' => $userid, 'status'=>10001), 'id DESC');
        } else {
            $camshots = $DB->get_records('quizaccess_proctoring_logs', 
            array('courseid' => $courseid, 'userid' => $userid, 'status' => 10001), 'id DESC');
        }

        $returnedcamhosts = array();

        foreach ($camshots as $camshot) {
            if ($camshot->webcampicture !== '') {
                $returnedcamhosts[] = array(
                    'courseid' => $camshot->courseid,
                    'quizid' => $camshot->quizid,
                    'userid' => $camshot->userid,
                    'webcampicture' => $camshot->webcampicture,
                    'timemodified' => $camshot->timemodified,
                );

            }
        }

        $result = array();
        $result['camshots'] = $returnedcamhosts;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_camshots_returns() {
        return new external_single_structure(
            array(
                'camshots' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'courseid' => new external_value(PARAM_NOTAGS, 'camshot course id'),
                            'quizid' => new external_value(PARAM_NOTAGS, 'camshot quiz id'),
                            'userid' => new external_value(PARAM_NOTAGS, 'camshot user id'),
                            'webcampicture' => new external_value(PARAM_RAW, 'camshot webcam photo'),
                            'timemodified' => new external_value(PARAM_NOTAGS, 'camshot time modified'),
                        )
                    ),
                    'list of camshots'
                ),
                'warnings' => new external_warnings()
            )
        );
    }


    public static function send_camshot_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_ALPHANUM, 'course id'),
                'screenshotid' => new external_value(PARAM_ALPHANUM, 'screenshot id'),
                'quizid' => new external_value(PARAM_ALPHANUM, 'screenshot quiz id'),
                'webcampicture' => new external_value(PARAM_RAW, 'webcam photo'),
            )
        );
    }

    public static function send_camshot($courseid, $screenshotid, $quizid, $webcampicture) {
        global $CFG, $DB, $USER;

        $warnings = array();

        $record = new stdClass();
        $record->filearea = 'picture';
        $record->component = 'quizaccess_proctoring';
        $record->filepath = '';
        $record->itemid   = 0;
        $record->license  = '';
        $record->author   = '';

        $context = context_user::instance($USER->id);
        $elname = 'repo_upload_file_proctoring';
        $fs = get_file_storage();
        $sm = get_string_manager();
        $record->filepath = file_correct_filepath($record->filepath);
        $contextquiz = $DB->get_record('course_modules', array('id' => $cmid));

         // For base64 to file.
         $data = $webcampicture;
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $filename = 'webcam-' . $USER->id . '-' . $courseid . '-quizaccess_proctoring-' . time() . '.png';

        $record->courseid = $courseid;
        $record->filename = $filename;
        $record->itemid = 0;
        $record->contextid = $context->id;
        $record->userid    = $USER->id;

        $fs->create_file_from_string($record, $data);

        $url = moodle_url::make_pluginfile_url($context->id, $record->component, $record->filearea, $record->itemid, $record->filepath, $record->filename, false);

        $record = new stdClass();
        $record->courseid = $courseid;
        $record->quizid = $quizid;
        $record->userid = $USER->id;
        $record->webcampicture = "{$url}";
        $record->status = 10001;
        $record->timemodified = time();
        $screenshotid = $DB->insert_record('quizaccess_proctoring_logs', $record, true);

        $result = array();
        $result['screenshotid'] = $screenshotid;
        $result['warnings'] = $warnings;
        return $result;
    }


    public static function send_camshot_returns() {
        return new external_single_structure(
            array(
                'screenshotid' => new external_value(PARAM_INT, 'screenshot sent id'),
                'warnings' => new external_warnings()
            )
        );
    }

}
