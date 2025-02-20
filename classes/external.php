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

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/externallib.php');
require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');

/**
 * External API class for the Quiz Proctoring plugin.
 *
 * This class provides external functions for the `quizaccess_proctoring` plugin, 
 * allowing integration with Moodle’s web services.
 *
 * @package   quizaccess_proctoring
 * @category  external
 * @copyright 2024 Brain Station 23
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_proctoring_external extends external_api {

    /**
     * Defines parameters for retrieving webcam shots.
     *
     * This function specifies the required parameters for fetching 
     * webcam images related to a specific course, quiz, and user.
     *
     * @return external_function_parameters The expected parameters.
     */

    public static function get_camshots_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'camshot course id'),
                'quizid' => new external_value(PARAM_INT, 'camshot quiz id'),
                'userid' => new external_value(PARAM_INT, 'camshot user id'),
            ]
        );
    }

    /**
     * Retrieves webcam shots for a given course, quiz, and user.
     *
     * This function fetches proctoring logs containing webcam images 
     * for a specific quiz attempt within a course. It validates parameters, 
     * checks user capabilities, and returns the list of captured images.
     *
     * @param int $courseid The ID of the course.
     * @param int $quizid The ID of the quiz (context module ID).
     * @param int $userid The ID of the user. If empty, the current user's ID is used.
     *
     * @return array An array containing webcam images and warnings.
     *               - 'camshots' (array): List of retrieved webcam images.
     *               - 'warnings' (array): List of warnings (if any).
     *
     * @throws dml_exception If a database query fails.
     * @throws invalid_parameter_exception If parameters are invalid.
     * @throws moodle_exception If an error occurs during execution.
     * @throws required_capability_exception If the user lacks required capabilities.
     */

    public static function get_camshots($courseid, $quizid, $userid) {
        global $DB, $USER;

        $params = [
            'courseid' => $courseid,
            'quizid' => $quizid,
            'userid' => $userid,
        ];

        // Validate the params.
        self::validate_parameters(self::get_camshots_parameters(), $params);

        $context = context_module::instance($params['quizid']);

        // Default value for userid.
        if (empty($params['userid'])) {
            $params['userid'] = $USER->id;
        }

        self::request_user_has_capability($params, $context, $USER);

        $warnings = [];
        if ($params['quizid']) {
            $camshots = $DB->get_records('quizaccess_proctoring_logs', $params, 'id DESC');
        } else {
            $camshots = $DB->get_records('quizaccess_proctoring_logs',
                ['courseid' => $courseid, 'userid' => $userid], 'id DESC');
        }

        $returnedcamhosts = [];

        foreach ($camshots as $camshot) {
            if ($camshot->webcampicture !== '') {
                $returnedcamhosts[] = [
                    'courseid' => $camshot->courseid,
                    'quizid' => $camshot->quizid,
                    'userid' => $camshot->userid,
                    'webcampicture' => $camshot->webcampicture,
                    'timemodified' => $camshot->timemodified,
                ];
            }
        }

        $result = [];
        $result['camshots'] = $returnedcamhosts;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Defines the structure of the data returned by get_camshots.
     *
     * This function specifies the return structure for the get_camshots web service, 
     * ensuring that the data is correctly formatted when sent back to the caller.
     *
     * @return external_single_structure The structured response containing:
     *      - 'camshots' (array): A list of webcam images captured during the quiz.
     *          - 'courseid' (int): The ID of the course where the proctoring took place.
     *          - 'quizid' (int): The ID of the quiz associated with the camshot.
     *          - 'userid' (int): The ID of the user who took the quiz.
     *          - 'webcampicture' (string): The URL or path to the captured webcam image.
     *          - 'timemodified' (int): The timestamp of when the image was captured.
     *      - 'warnings' (array): A list of warnings (if any).
     */

    public static function get_camshots_returns() {
        return new external_single_structure(
            [
                'camshots' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'courseid' => new external_value(PARAM_NOTAGS, 'camshot course id'),
                            'quizid' => new external_value(PARAM_NOTAGS, 'camshot quiz id'),
                            'userid' => new external_value(PARAM_NOTAGS, 'camshot user id'),
                            'webcampicture' => new external_value(PARAM_RAW, 'camshot webcam photo'),
                            'timemodified' => new external_value(PARAM_NOTAGS, 'camshot time modified'),
                        ]
                    ),
                    'list of camshots'
                ),
                'warnings' => new external_warnings(),
            ]
        );
    }

    /**
     * Defines the parameters required for sending a camshot.
     *
     * This function specifies the parameters that must be provided when calling 
     * the send_camshot web service.
     *
     * @return external_function_parameters The required parameters:
     *      - 'courseid' (int): The ID of the course where the proctoring took place.
     *      - 'screenshotid' (int): The unique ID of the captured screenshot.
     *      - 'quizid' (int): The ID of the quiz associated with the screenshot.
     *      - 'webcampicture' (string): The base64-encoded webcam image or file path.
     *      - 'imagetype' (int): The type of image being stored.
     *      - 'parenttype' (string): The parent type associated with the face image.
     *      - 'faceimage' (string): The base64-encoded cropped face image or file path.
     *      - 'facefound' (int): A flag indicating whether a face was detected (1 = Yes, 0 = No).
     */
    public static function send_camshot_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'course id'),
                'screenshotid' => new external_value(PARAM_INT, 'screenshot id'),
                'quizid' => new external_value(PARAM_INT, 'screenshot quiz id'),
                'webcampicture' => new external_value(PARAM_RAW, 'webcam photo'),
                'imagetype' => new external_value(PARAM_INT, 'image type'),
                'parenttype' => new external_value(PARAM_RAW, 'Face image parent type'),
                'faceimage' => new external_value(PARAM_RAW, 'Face Image'),
                'facefound' => new external_value(PARAM_INT, 'Face found flag'),
            ]
        );
    }

    /**
     * Store the cam shots in Moodle subsystems and insert into the log table.
     *
     * This function processes webcam images and face images, storing them in Moodle's file storage system
     * and inserting records into the `quizaccess_proctoring_logs` and `quizaccess_proctoring_face_images` tables.
     * The images are saved and linked to the appropriate quiz and user. Additionally, metadata like face found 
     * flag and parent type are saved.
     *
     * @param int $courseid The course ID where the proctoring took place.
     * @param int $screenshotid The ID of the screenshot being uploaded.
     * @param int $quizid The ID of the quiz associated with the screenshot (or CMID).
     * @param string $webcampicture The base64-encoded webcam image.
     * @param int $imagetype The type of image being uploaded (e.g., webcam photo or other).
     * @param string $parenttype The parent type, indicating whether the image is an Admin Image or Webcam Image.
     * @param string $faceimage The base64-encoded face image extracted from the webcam photo.
     * @param int $facefound A flag indicating whether a face was detected (1 = face found, 0 = face not found).
     *
     * @return array Returns an array with the following:
     *      - 'screenshotid' (int): The ID of the stored screenshot.
     *      - 'warnings' (array): A list of warnings generated during the process (if any).
     *
     * @throws dml_exception If there is a problem with database interaction.
     * @throws file_exception If there is an issue storing or retrieving files.
     * @throws invalid_parameter_exception If one or more parameters are invalid.
     * @throws stored_file_creation_exception If there is a problem creating or storing files.
     */
    public static function send_camshot
        ($courseid, $screenshotid, $quizid, $webcampicture, $imagetype, $parenttype, $faceimage, $facefound) {
        global $DB, $USER;

        // Validate the params.
        self::validate_parameters(
            self::send_camshot_parameters(),
            [
                'courseid' => $courseid,
                'screenshotid' => $screenshotid,
                'quizid' => $quizid,
                'webcampicture' => $webcampicture,
                'imagetype' => $imagetype,
                'parenttype' => $parenttype,
                'faceimage' => $faceimage,
                'facefound' => $facefound,
            ]
        );
        $warnings = [];

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

            $camshot = $DB->get_record('quizaccess_proctoring_logs', ['id' => $screenshotid]);

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
            if ($faceimage) {
                // For base64 to file.
                $data = $faceimage;
                list(, $data) = explode(';', $data);
                $url = self::quizaccess_proctoring_geturl_without_timecode(
                    $data, $screenshotid, $USER, $courseid, $record, $context, $fs);
            }
            $record = new stdClass();
            $record->parent_type = $parenttype;
            $record->parentid = $screenshotid;
            $record->faceimage = "{$url}";
            $record->facefound = $facefound;
            $record->timemodified = time();
            $screenshotid = $DB->insert_record('quizaccess_proctoring_face_images', $record, true);

            $result = [];
            $result['screenshotid'] = $screenshotid;
            $result['warnings'] = $warnings;
        } else {
            $result = [];
            $result['screenshotid'] = 100;
            $result['warnings'] = [];
        }

        return $result;
    }

    /**
     * Return structure for sending cam shots.
     *
     * This function defines the structure of the response that is returned after
     * sending a cam shot. It includes the `screenshotid` which is the identifier
     * for the stored screenshot and any warnings that might have occurred during
     * the operation.
     *
     * @return external_single_structure The structure of the return value, which contains:
     *      - 'screenshotid' (int): The ID of the screenshot that was sent.
     *      - 'warnings' (array): An array containing any warnings encountered during the process.
     */
    public static function send_camshot_returns() {
        return new external_single_structure(
            [
                'screenshotid' => new external_value(PARAM_INT, 'screenshot sent id'),
                'warnings' => new external_warnings(),
            ]
        );
    }

    /**
     * Check if the user has the required capability to view a report.
     *
     * This function checks whether the user has the appropriate capability to 
     * view a report for another user. It performs a set of checks to verify 
     * that the user is active, and if the user is not the same as the current 
     * logged-in user, it ensures they have the necessary permissions.
     *
     * @param array $params An array of parameters, which includes 'userid' for the user whose report is being accessed.
     * @param context $context The context of the module, which helps determine the user's capability within that context.
     * @param object $USER The current logged-in user object.
     * 
     * @return void
     * 
     * @throws dml_exception If there is an error with the database interaction.
     * @throws moodle_exception If there is a general Moodle-related exception.
     * @throws required_capability_exception If the user does not have the required capability to view the report.
     */
    protected static function request_user_has_capability(array $params, context $context, $USER) {
        $user = core_user::get_user($params['userid'], '*', MUST_EXIST);
        core_user::require_active_user($user);

        // Extra checks so only users with permissions can view other users reports.
        if ($USER->id != $user->id) {
            has_capability('quizaccess/proctoring:viewreport', $context);
        }
    }

    /**
     * Adds a timestamp to the captured image.
     *
     * This function takes an image in raw data format, adds a timestamp in the 
     * specified format to the image, and returns the updated image data.
     *
     * @param string $data The raw image data (in PNG or JPEG format) to which the timestamp will be added.
     * 
     * @return string The updated image data with the added timestamp.
     * 
     * @throws Exception If there is an issue with image creation or manipulation.
     */
    private static function add_timecode_to_image($data) {
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
     * Defines the parameters for the validate_face function.
     *
     * This function defines and returns the expected parameters for the 
     * validate_face function, which includes information about the course, 
     * the activity, the profile photo, webcam photo, face image, and face found flag.
     *
     * @return external_function_parameters The parameters required for the validate_face function.
     */
    public static function validate_face_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'course id'),
                'cmid' => new external_value(PARAM_INT, 'cm id'),
                'profileimage' => new external_value(PARAM_RAW, 'profile photo'),
                'webcampicture' => new external_value(PARAM_RAW, 'webcam photo'),
                'parenttype' => new external_value(PARAM_RAW, 'Face image parent type'),
                'faceimage' => new external_value(PARAM_RAW, 'Face Image'),
                'facefound' => new external_value(PARAM_INT, 'Face found flag'),
            ]
        );
    }

    /**
     * Stores the captured Cam shots in Moodle subsystems and logs them in the database.
     *
     * This function validates the parameters, processes the webcam and face images, stores them in Moodle's
     * file storage, inserts a record in the `quizaccess_proctoring_logs` and `quizaccess_proctoring_face_images` tables, 
     * performs face checking, and returns the result along with warnings if applicable.
     *
     * @param mixed $courseid The course ID.
     * @param mixed $cmid The course module ID.
     * @param mixed $profileimage The profile image of the user.
     * @param mixed $webcampicture The webcam image captured.
     * @param mixed $parenttype The type of parent image (e.g., Admin or Webcam).
     * @param mixed $faceimage The face image captured.
     * @param bool $facefound Flag indicating whether a face was detected (0 or 1).
     *
     * @return array An array containing the `screenshotid`, `status`, and `warnings`.
     *
     * @throws dml_exception If there is a database issue.
     * @throws file_exception If there is an issue with file handling.
     * @throws invalid_parameter_exception If any of the parameters are invalid.
     * @throws stored_file_creation_exception If there is an error creating the stored file.
     */
    public static function validate_face($courseid, $cmid, $profileimage, $webcampicture, $parenttype, $faceimage, $facefound) {
        global $DB, $USER, $CFG;

        // Validate the params.
        self::validate_parameters(
            self::validate_face_parameters(),
            [
                'courseid' => $courseid,
                'cmid' => $cmid,
                'profileimage' => $profileimage,
                'webcampicture' => $webcampicture,
                'parenttype' => $parenttype,
                'faceimage' => $faceimage,
                'facefound' => $facefound,
            ]
        );
        $warnings = [];
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
        if ($faceimage) {
            // For base64 to file.
            $data = $faceimage;
            list(, $data) = explode(';', $data);
            $url = self::quizaccess_proctoring_geturl_without_timecode(
                $data, $screenshotid, $USER, $courseid, $record, $context, $fs);
        }
        $record = new stdClass();
        $record->parent_type = $parenttype;
        $record->parentid = $screenshotid;
        $record->faceimage = "{$url}";
        $record->facefound = $facefound;
        $record->timemodified = time();
        $faceimageid = $DB->insert_record('quizaccess_proctoring_face_images', $record, true);
        $profileimageurl = quizaccess_proctoring_get_image_url( $USER->id);
        if ($profileimageurl == false) {
            $result = [];
            $result['screenshotid'] = $screenshotid;
            $result['status'] = 'photonotuploaded';
            $result['warnings'] = $warnings;
            return $result;
        }

        // Face check.
        require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');
        $method = quizaccess_proctoring_get_proctoring_settings("fcmethod");
        if ($method == "BS") {
            quizaccess_proctoring_bs_analyze_specific_image_from_validate($screenshotid);
        }

        $currentdata = $DB->get_record('quizaccess_proctoring_logs', ['id' => $screenshotid]);
        $awsscore = $currentdata->awsscore;
        $threshhold = (int)quizaccess_proctoring_get_proctoring_settings('awsfcthreshold');

        if ($awsscore > $threshhold) {
            $status = "success";
        } else {
            $status = "failed";
        }

        $result = [];
        $result['screenshotid'] = $screenshotid;
        $result['status'] = $status;
        $result['warnings'] = $warnings;
        return $result;
    }


    /**
     * Returns the structure for the cam shots validation response.
     *
     * This function defines the structure of the returned data when a cam shot validation is performed.
     * It returns the screenshot ID, validation status, and any warnings encountered during the process.
     *
     * @return external_single_structure A single structure containing:
     *  - 'screenshotid' => The ID of the screenshot sent for validation (integer).
     *  - 'status' => The response status of the validation (string).
     *  - 'warnings' => Any warnings encountered during validation (external_warnings).
     */
    public static function validate_face_returns() {
        return new external_single_structure(
            [
                'screenshotid' => new external_value(PARAM_INT, 'screenshot sent id'),
                'status' => new external_value(PARAM_TEXT, 'validation response'),
                'warnings' => new external_warnings(),
            ]
        );
    }

    /**
     * Returns the image URL from image data after adding a timecode at the top of the image.
     *
     * This function processes the base64 encoded image data, adds a timecode, and stores the image in Moodle's file system.
     * It then returns the URL of the stored image file.
     *
     * @param string $data The base64 encoded image data.
     * @param int $screenshotid The unique ID of the screenshot.
     * @param object $USER The current user object.
     * @param int $courseid The ID of the course.
     * @param stdClass $record The record object used to store file metadata.
     * @param context $context The context in which the file will be stored.
     * @param mixed $fs The file storage instance to handle file operations.
     * @return mixed The URL of the stored image file with the timecode added.
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
     * Returns the image URL without adding a timecode at the top of the image.
     *
     * This function processes the base64 encoded image data, stores the image in Moodle's file system without adding a timecode, 
     * and then returns the URL of the stored image file.
     *
     * @param string $data The base64 encoded image data.
     * @param int $screenshotid The unique ID of the screenshot.
     * @param object $USER The current user object.
     * @param int $courseid The ID of the course.
     * @param stdClass $record The record object used to store file metadata.
     * @param mixed $context The context in which the file will be stored.
     * @param mixed $fs The file storage instance to handle file operations.
     * @return mixed The URL of the stored image file without the timecode added.
     */
    private static function quizaccess_proctoring_geturl_without_timecode(
        string $data, int $screenshotid, $USER, int $courseid, stdClass $record, $context, $fs) {
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
