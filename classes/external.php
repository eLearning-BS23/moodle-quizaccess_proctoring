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
 * @package   quizaccess_proctoring
 * @copyright 2020 Brain Station 23
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/externallib.php');

/**
 * External class.
 *
 * @package quizaccess_proctoring
 * @copyright 2020 Brain Station 23
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_proctoring_external extends external_api {

    /**
     * Set the cam shots parameters.
     *
     * @return external_function_parameters
     */
    public static function get_camshots_parameters () {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'camshot course id'),
                'quizid' => new external_value(PARAM_INT, 'camshot quiz id'),
                'userid' => new external_value(PARAM_INT, 'camshot user id')
            )
        );
    }

    /**
     * Get the cam shots as service.
     *
     * @param mixed $courseid course id.
     * @param mixed $quizid context/quiz id.
     * @param mixed $userid user id.
     *
     * @return array
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws moodle_exception
     * @throws required_capability_exception
     */
    public static function get_camshots($courseid, $quizid, $userid) {
        global $DB, $USER;

        $params = array(
            'courseid' => $courseid,
            'quizid' => $quizid,
            'userid' => $userid
        );

        // Validate the params.
        self::validate_parameters(self::get_camshots_parameters(), $params);

        $context = context_module::instance($params['quizid']);

        // Default value for userid.
        if (empty($params['userid'])) {
            $params['userid'] = $USER->id;
        }

        self::request_user_require_capability($params, $context, $USER);

        $warnings = array();
        if ($params['quizid']) {
            $camshots = $DB->get_records('quizaccess_proctoring_logs', $params, 'id DESC');
        } else {
            $camshots = $DB->get_records('quizaccess_proctoring_logs',
                array('courseid' => $courseid, 'userid' => $userid), 'id DESC');
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

    /**
     * Cam shot return parameters.
     *
     * @return external_single_structure
     */
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


    /**
     * Store parameters.
     *
     * @return external_function_parameters
     */
    public static function send_camshot_parameters () {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'course id'),
                'screenshotid' => new external_value(PARAM_INT, 'screenshot id'),
                'quizid' => new external_value(PARAM_INT, 'screenshot quiz id'),
                'webcampicture' => new external_value(PARAM_RAW, 'webcam photo'),
                'imagetype' => new external_value(PARAM_INT, 'image type'),
                'parenttype' => new external_value(PARAM_RAW, 'Face image parent type'), 
                'faceimage' => new external_value(PARAM_RAW, 'Face Image'), 
                'facefound' => new external_value(PARAM_INT, 'Face found flag')
            )
        );
    }

    /**
     * Store the Cam shots in Moodle subsystems and insert in log table
     *
     * @param mixed $courseid
     * @param mixed $screenshotid
     * @param mixed $quizid Quizid OR cmid
     * @param mixed $webcampicture
     *
     * @return array
     * @throws dml_exception
     * @throws file_exception
     * @throws invalid_parameter_exception
     * @throws stored_file_creation_exception
     */
    public static function send_camshot($courseid, $screenshotid, $quizid, $webcampicture, $imagetype, $parenttype, $faceimage, $facefound) {
        global $DB, $USER;

        // Validate the params.
        self::validate_parameters(
            self::send_camshot_parameters(),
            array(
                'courseid' => $courseid,
                'screenshotid' => $screenshotid,
                'quizid' => $quizid,
                'webcampicture' => $webcampicture,
                'imagetype' => $imagetype,
                'parenttype' => $parenttype, 
                'faceimage' => $faceimage, 
                'facefound' => $facefound
            )
        );
        $warnings = array();

        if ($imagetype == 1) {
            $record = new stdClass();
            $record->filearea = 'picture';
            $record->component = 'quizaccess_proctoring';
            $record->filepath = '';
            $record->itemid = $screenshotid;
            $record->license = '';
            $record->author = '';

            $context = context_module::instance($quizid);
            $fs = get_file_storage();
            $record->filepath = file_correct_filepath($record->filepath);

            // For base64 to file.
            $data = $webcampicture;
            list(, $data) = explode(';', $data);
            $url = self::geturl($data, $screenshotid, $USER, $courseid, $record, $context, $fs);

            $camshot = $DB->get_record('quizaccess_proctoring_logs', array('id' => $screenshotid));

            $record = new stdClass();
            $record->courseid = $courseid;
            $record->quizid = $quizid;
            $record->userid = $USER->id;
            $record->webcampicture = "{$url}";
            $record->status = $camshot->status;
            $record->timemodified = time();
            $screenshotid = $DB->insert_record('quizaccess_proctoring_logs', $record, true);


            // Save the face image. 
            $record = new stdClass();
            $record->filearea = 'face_image';
            $record->component = 'quizaccess_proctoring';
            $record->filepath = '';
            $record->itemid = $screenshotid;
            $record->license = '';
            $record->author = '';

            $context = context_module::instance($quizid);
            $fs = get_file_storage();
            $record->filepath = file_correct_filepath($record->filepath);

            $url = "";
            if($faceimage) {
            // For base64 to file.
                $data = $faceimage;
                list(, $data) = explode(';', $data);
                $url = self::quizaccess_proctoring_geturl_without_timecode($data, $screenshotid, $USER, $courseid, $record, $context, $fs);
            }   
            $record = new stdClass();
            $record->parent_type = $parenttype;
            $record->parentid = $screenshotid;
            $record->faceimage = "{$url}";
            $record->facefound = $facefound;
            $record->timemodified = time();
            $screenshotid = $DB->insert_record('proctoring_face_images', $record, true);


            $result = array();
            $result['screenshotid'] = $screenshotid;
            $result['warnings'] = $warnings;
        } else {
            $result = array();
            $result['screenshotid'] = 100;
            $result['warnings'] = array();
        }

        return $result;
    }


    /**
     * Cam shots return parameters.
     *
     * @return external_single_structure
     */
    public static function send_camshot_returns() {
        return new external_single_structure(
            array(
                'screenshotid' => new external_value(PARAM_INT, 'screenshot sent id'),
                'warnings' => new external_warnings()
            )
        );
    }



    /**
     * Check user capability
     * @param array $params
     * @param context $context
     * @param $USER
     * @return void
     * @throws dml_exception
     * @throws moodle_exception
     * @throws required_capability_exception
     */
    protected static function request_user_require_capability(array $params, context $context, $USER) {
        $user = core_user::get_user($params['userid'], '*', MUST_EXIST);
        core_user::require_active_user($user);

        // Extra checks so only users with permissions can view other users reports.
        if ($USER->id != $user->id) {
            require_capability('quizaccess/proctoring:viewreport', $context);
        }
    }

    /**
     * Adds timestamp information to captured image.
     * @param $data
     * @return string
     */
    private static function add_timecode_to_image ($data) {
        global $CFG;

        $image = imagecreatefromstring($data);
        imagefilledrectangle($image, 0, 0, 120, 22, imagecolorallocatealpha($image, 255, 255, 255, 60));
        imagefttext($image, 9, 0, 4, 16, imagecolorallocate($image, 0, 0, 0),
            $CFG->dirroot . '/mod/quiz/accessrule/proctoring/assets/Roboto-Light.ttf', date('d-m-Y H:i:s') );
        ob_start();
        imagepng($image);
        $data = ob_get_clean();
        ob_end_clean();
        imagedestroy($image);
        return $data;
    }

    /**
     * Store parameters.
     *
     * @return external_function_parameters
     */
    public static function validate_face_parameters () {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'course id'),
                'cmid' => new external_value(PARAM_INT, 'cm id'),
                'profileimage' => new external_value(PARAM_RAW, 'profile photo'),
                'webcampicture' => new external_value(PARAM_RAW, 'webcam photo'),
                'parenttype' => new external_value(PARAM_RAW, 'Face image parent type'), 
                'faceimage' => new external_value(PARAM_RAW, 'Face Image'), 
                'facefound' => new external_value(PARAM_INT, 'Face found flag')
            )
        );
    }

    /**
     * Store the Cam shots in Moodle subsystems and insert in log table
     *
     * @param mixed $courseid
     * @param mixed $screenshotid
     * @param mixed $quizid Quizid OR cmid
     * @param mixed $webcampicture
     *
     * @return array
     * @throws dml_exception
     * @throws file_exception
     * @throws invalid_parameter_exception
     * @throws stored_file_creation_exception
     */
    public static function validate_face($courseid, $cmid, $profileimage, $webcampicture, $parenttype, $faceimage, $facefound) {
        global $DB, $USER, $CFG;

        // Validate the params.
        self::validate_parameters(
            self::validate_face_parameters(),
            array(
                'courseid' => $courseid,
                'cmid' => $cmid,
                'profileimage' => $profileimage,
                'webcampicture' => $webcampicture,
                'parenttype' => $parenttype, 
                'faceimage' => $faceimage, 
                'facefound' => $facefound
            )
        );
        $warnings = array();
        $screenshotid = time();
        $record = new stdClass();
        $record->filearea = 'picture';
        $record->component = 'quizaccess_proctoring';
        $record->filepath = '';
        $record->itemid = $screenshotid;
        $record->license = '';
        $record->author = '';

        $context = context_module::instance($cmid);
        $fs = get_file_storage();
        $record->filepath = file_correct_filepath($record->filepath);

        // For base64 to file.
        $data = $webcampicture;
        $url = self::geturl($data, $screenshotid, $USER, $courseid, $record, $context, $fs);

        $record = new stdClass();
        $record->courseid = $courseid;
        $record->quizid = $cmid;
        $record->userid = $USER->id;
        $record->webcampicture = "{$url}";
        $record->status = $screenshotid;
        $record->timemodified = time();
        $screenshotid = $DB->insert_record('quizaccess_proctoring_logs', $record, true);

        // Save the face image. 
        $record = new stdClass();
        $record->filearea = 'face_image';
        $record->component = 'quizaccess_proctoring';
        $record->filepath = '';
        $record->itemid = $screenshotid;
        $record->license = '';
        $record->author = '';

        $context = context_module::instance($cmid);
        $fs = get_file_storage();
        $record->filepath = file_correct_filepath($record->filepath);

        $url = "";
        if($faceimage) {
        // For base64 to file.
            $data = $faceimage;
            list(, $data) = explode(';', $data);
            $url = self::quizaccess_proctoring_geturl_without_timecode($data, $screenshotid, $USER, $courseid, $record, $context, $fs);
        }   
        $record = new stdClass();
        $record->parent_type = $parenttype;
        $record->parentid = $screenshotid;
        $record->faceimage = "{$url}";
        $record->facefound = $facefound;
        $record->timemodified = time();
        $faceimageid = $DB->insert_record('proctoring_face_images', $record, true);
        
        // Face check.
        require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');
        $method = get_proctoring_settings("fcmethod");
        if ($method == "AWS") {
            aws_analyze_specific_image($screenshotid);
        } else if ($method == "BS") {
            bs_analyze_specific_image_from_validate($screenshotid);
        }

        $currentdata = $DB->get_record('quizaccess_proctoring_logs', array('id' => $screenshotid));
        $awsscore = $currentdata->awsscore;
        $threshhold = (int)get_proctoring_settings('awsfcthreshold');

        if ($awsscore > $threshhold) {
            $status = "success";
        } else {
            $status = "failed";
        }

        $result = array();
        $result['screenshotid'] = $screenshotid;
        $result['status'] = $status;
        $result['warnings'] = $warnings;
        return $result;
    }


    /**
     * Cam shots return parameters.
     *
     * @return external_single_structure
     */
    public static function validate_face_returns() {
        return new external_single_structure(
            array(
                'screenshotid' => new external_value(PARAM_INT, 'screenshot sent id'),
                'status' => new external_value(PARAM_TEXT, 'validation response'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * @param string $data
     * @param int $screenshotid
     * @param $USER
     * @param int $courseid
     * @param stdClass $record
     * @param $context
     * @param $fs
     * @return mixed
     */
    private static function geturl(string $data, int $screenshotid, $USER, int $courseid, stdClass $record, $context, $fs) {
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        $filename = 'webcam-' . $screenshotid . '-' . $USER->id . '-' . $courseid . '-' . time() . random_int(1, 1000) . '.png';

        $data = self::add_timecode_to_image($data);

        $record->courseid = $courseid;
        $record->filename = $filename;
        $record->contextid = $context->id;
        $record->userid = $USER->id;

        $fs->create_file_from_string($record, $data);

        return moodle_url::make_pluginfile_url(
            $context->id,
            $record->component,
            $record->filearea,
            $record->itemid,
            $record->filepath,
            $record->filename,
            false
        );
    }

    /**
     * @param string $data
     * @param int $screenshotid
     * @param $USER
     * @param int $courseid
     * @param stdClass $record
     * @param $context
     * @param $fs
     * @return mixed
     */
    private static function quizaccess_proctoring_geturl_without_timecode(string $data, int $screenshotid, $USER, int $courseid, stdClass $record, $context, $fs) {
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        $filename = 'webcam-' . $screenshotid . '-' . $USER->id . '-' . $courseid . '-' . time() . random_int(1, 1000) . '.png';

        $record->courseid = $courseid;
        $record->filename = $filename;
        $record->contextid = $context->id;
        $record->userid = $USER->id;

        $fs->create_file_from_string($record, $data);

        return moodle_url::make_pluginfile_url(
            $context->id,
            $record->component,
            $record->filearea,
            $record->itemid,
            $record->filepath,
            $record->filename,
            false
        );
    }
}
