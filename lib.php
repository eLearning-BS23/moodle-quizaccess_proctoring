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
 * Library function for the quizaccess_proctoring plugin.
 *
 * @package     quizaccess_proctoring
 * @author      Brain station 23 <brainstation-23.com>
 * @copyright   2024 Brain station 23
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/filelib.php'); // Required for Moodle's cURL class.

$token = "";

/**
 * Serves files for the quizaccess proctoring plugin.
 *
 * This function handles the process of serving files that are stored in the file storage for the quizaccess proctoring plugin.
 * It retrieves the requested file based on the file area, item ID, and path, and then sends the file to the user.
 *
 * @param stdClass $course The course object.
 * @param stdClass $cm The course module object.
 * @param context $context The context within which the file is being served.
 * @param string $filearea The name of the file area where the file is stored.
 * @param array $args Extra arguments used to locate the file, including itemid and the path.
 * @param bool $forcedownload Whether or not the file should be forced to download.
 * @param array $options Additional options affecting the file serving.
 *
 * @return bool Returns false if the file cannot be found.
 */
function quizaccess_proctoring_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    $itemid = array_shift($args);
    $filename = array_pop($args);

    if (!$args) {
        $filepath = '/';
    } else {
        $filepath = '/' . implode('/', $args) . '/';
    }

    $fs = get_file_storage();

    $file = $fs->get_file($context->id, 'quizaccess_proctoring', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false;
    }
    send_stored_file($file, 0, 0, $forcedownload, $options);
}

/**
 * Returns the image URL of a specific user from the quizaccess proctoring plugin.
 *
 * This function retrieves the image associated with a specific user by searching the `user_photo`
 * file area within the context of the system.
 * It then constructs and returns the image URL for that user, if the image exists.
 *
 * @param int $userid The user ID for which the image URL is to be fetched.
 *
 * @return string|false The image URL if the image is found, or false if no image is found.
 */
function quizaccess_proctoring_get_image_url($userid) {
    $context = context_system::instance();
    $fs = get_file_storage();

    if ($files = $fs->get_area_files($context->id, 'quizaccess_proctoring', 'user_photo')) {
        foreach ($files as $file) {
            if ($userid == $file->get_itemid() && $file->get_filename() != '.') {
                $fileurl = moodle_url::make_pluginfile_url(
                    $file->get_contextid(), $file->get_component(), $file->get_filearea(),
                    $file->get_itemid(), $file->get_filepath(), $file->get_filename(), true);
                return $fileurl->out(false); // Properly formatted URL without trailing slash.
            }
        }
    }

    return false;
}


/**
 * Returns the image file of a specific user.
 *
 * This function retrieves the image file associated with a specific user by searching the `user_photo` file area
 * in the `quizaccess_proctoring` context. If an image is found, it also deletes the corresponding records from
 * the `quizaccess_proctoring_user_images` and `quizaccess_proctoring_face_images` tables, ensuring that the
 * image is removed from the database and the related image records are cleaned up.
 *
 * @param int $userid The user ID for which the image file is to be fetched.
 *
 * @return mixed The image file object if the image is found, or false if no image is found for the user.
 */
function quizaccess_proctoring_get_image_file($userid) {
    global $DB;
    $context = context_system::instance();

    $fs = get_file_storage();
    if ($files = $fs->get_area_files($context->id, 'quizaccess_proctoring', 'user_photo')) {

        foreach ($files as $file) {
            if ($userid == $file->get_itemid() && $file->get_filename() != '.') {

                // Get the record ID from the database.
                $recordid = $DB->get_field('quizaccess_proctoring_user_images', 'id', ['user_id' => $userid]);

                // Delete the record from the database.
                $DB->delete_records('quizaccess_proctoring_user_images', ['user_id' => $userid]);

                // Delete associated row from proctoring_face_images table.
                $DB->delete_records('quizaccess_proctoring_face_images', ['parentid' => $recordid]);

                return $file;
            }
        }
    }
    return false;
}


/**
 * Updates match result.
 *
 * This function updates the match result for a specific report in the `quizaccess_proctoring_logs` table.
 * It takes the report ID, the similarity match result, and an AWS flag indicating the status of the analyzed images.
 * The match result (similarity) is stored as an integer score, and the AWS flag indicates the result of the analysis.
 *
 * @param int $rowid The report ID (`rowid`) of the record to be updated.
 * @param string $matchresult The similarity score, which will be converted to an integer.
 * @param int $awsflag Flag indicating the status of the analyzed images (1/2/3).
 *
 * @return void This function does not return any value.
 */
function quizaccess_proctoring_update_match_result($rowid, $matchresult, $awsflag) {
    global $DB;
    $score = (int)$matchresult;

    // Prepare the record with fields to be updated.
    $record = new stdClass();
    $record->id = $rowid;
    $record->awsflag = $awsflag;
    $record->awsscore = $score;

    // Update the record using Moodle's update_record method.
    $DB->update_record('quizaccess_proctoring_logs', $record);
}

/**
 * Execute face recognition task.
 *
 * This function fetches up to 5 tasks from the `quizaccess_proctoring_facematch_task` table, processes each task
 * by performing a face recognition operation, and deletes the processed tasks. The face matching is done using the
 * method specified in the `fcmethod` setting.
 *
 * The function supports the 'BS' method for face recognition, where it retrieves face images and calls
 * the `quizaccess_proctoring_extracted`
 * function to perform the face matching. After processing, the task is removed from the table.
 *
 * @return bool Returns false if no records are found to process, otherwise performs the task and deletes processed records.
 */
function quizaccess_proctoring_execute_fm_task() {
    global $DB;

    // Fetch up to 5 face match tasks.
    $tasks = $DB->get_records('quizaccess_proctoring_facematch_task', null, '', '*', 0, 5);

    // Get face match method from plugin settings.
    $facematchmethod = quizaccess_proctoring_get_proctoring_settings('fcmethod');

    // If there are no tasks, exit early.
    if (empty($tasks)) {
        mtrace('No face match tasks found.');
        return;
    }

    // Validate face match method.
    if ($facematchmethod !== 'BS') {
        mtrace("Invalid face match method: {$facematchmethod}");
        return;
    }

    // Process each task.
    foreach ($tasks as $row) {
        $rowid = $row->id;
        $reportid = $row->reportid;

        // Fetch face image URLs.
        list($userfaceimageurl, $webcamfaceimageurl) = quizaccess_proctoring_get_face_images($reportid);

        mtrace('Profile Image URL: ' . $userfaceimageurl);
        mtrace('Target Image URL: ' . $webcamfaceimageurl);
        if (!empty($userfaceimageurl) && !empty($webcamfaceimageurl)) {
            // Perform face matching operation.
            quizaccess_proctoring_extracted($userfaceimageurl, $webcamfaceimageurl, $reportid);

            // Execute the query.
            $result = $DB->get_record(
                'quizaccess_proctoring_logs',
                ['id' => $reportid],
                'awsscore',
                MUST_EXIST
            );
            mtrace('Face match result: ' . $result->awsscore);

            if ($result->awsscore > 0) {
                // Delete the task if processed successfully.
                $DB->delete_records('quizaccess_proctoring_facematch_task', ['id' => $rowid]);
                mtrace("Successfully processed and deleted task ID {$rowid} (Report ID: {$reportid}).");
            } else {
                mtrace("Face match failed for report ID {$reportid}.");
            }
        } else {
            mtrace("Missing image URLs for report ID {$reportid}.");
        }
    }
}

/**
 * Execute face recognition logging task.
 *
 * This function fetches distinct records from the `quizaccess_proctoring_logs` table where the `awsflag` is 0, and then processes
 * each record by logging specific quiz details for the corresponding user, course, and quiz ID. After logging the information,
 * a success message is displayed.
 *
 * @return bool Returns false if no records are found to process, otherwise processes the records and logs the data.
 */
function quizaccess_proctoring_log_facematch_task() {
    global $DB;

    // Fetch distinct records where awsflag is 0 using Moodle's get_records_sql.
    $sql = 'SELECT DISTINCT id, courseid, quizid, userid
             FROM {quizaccess_proctoring_logs}
             WHERE awsflag = :awsflag';
    $params = ['awsflag' => 0];
    $records = $DB->get_records_sql($sql, $params);
    // Process each record.
    foreach ($records as $record) {
        $courseid = $record->courseid;
        $quizid = $record->quizid;
        $userid = $record->userid;

        // Log specific quiz details.
        quizaccess_proctoring_log_specific_quiz($courseid, $quizid, $userid);
    }

    // Use Moodle's notification API for success messages.
    mtrace('Log success');

}

/**
 * Log the analysis of a specific quiz for a student.
 *
 * This function fetches the user's profile image and updates the `awsflag` field to mark records as attempted.
 * It then queries the `quizaccess_proctoring_logs` table to retrieve specific records for the quiz and student,
 * checks a random limit for the number of records, and logs the results for each match task.
 *
 * @param int $courseid The ID of the course.
 * @param int $cmid The ID of the course module.
 * @param int $studentid The ID of the student.
 *
 * @return bool Returns `true` if records were processed, `false` if no record was found.
 */
function quizaccess_proctoring_log_specific_quiz($courseid, $cmid, $studentid) {
    global $DB;

    // Get user profile image.
    $profileimageurl = quizaccess_proctoring_get_image_url($studentid);
    if (empty($profileimageurl)) {
        mtrace("No profile image found for user ID {$studentid}.");
        return false;
    }

    // Update all logs to mark as processed.
    $updateparams = [
        'courseid' => $courseid,
        'quizid' => $cmid,
        'userid' => $studentid,
    ];
    $DB->set_field('quizaccess_proctoring_logs', 'awsflag', 1, $updateparams);

    // Get limit from settings or default.
    $defaultlimit = 5;
    $awschecknumber = quizaccess_proctoring_get_proctoring_settings('awschecknumber');

    if ($awschecknumber == '') {
        $limit = $defaultlimit;
    } else if ($awschecknumber > 0) {
        $limit = (int)$awschecknumber;
    } else {
        $limit = $defaultlimit;
    }

    mtrace("Limit for face match task: {$limit}");

    // First get all matching IDs (only IDs for performance).
    $idparams = [
        'courseid' => $courseid,
        'quizid' => $cmid,
        'userid' => $studentid,
    ];
    $idsql = "SELECT id
              FROM {quizaccess_proctoring_logs}
              WHERE courseid = :courseid
              AND quizid = :quizid
              AND userid = :userid
              AND webcampicture != ''";
    $allrecords = $DB->get_fieldset_sql($idsql, $idparams);

    if (empty($allrecords)) {
        mtrace("No snapshots found for user ID {$studentid}");
        return false;
    }

    // Shuffle and slice IDs for randomness.
    shuffle($allrecords);
    $selectedids = array_slice($allrecords, 0, $limit);

    // Avoid proceeding if selected IDs are empty.
    if (empty($selectedids)) {
        mtrace("No selected snapshot IDs to process for user ID {$studentid}");
        return false;
    }

    // Now fetch full data for those selected IDs.
    list($insql, $inparams) = $DB->get_in_or_equal($selectedids, SQL_PARAMS_NAMED);
    $finalsql = "SELECT id, webcampicture
                 FROM {quizaccess_proctoring_logs}
                 WHERE id $insql";
    $records = $DB->get_records_sql($finalsql, $inparams);

    // Insert each snapshot into facematch task table.
    foreach ($records as $record) {
        $facematch = new stdClass();
        $facematch->refimageurl = $profileimageurl;
        $facematch->targetimageurl = $record->webcampicture;
        $facematch->reportid = $record->id;
        $facematch->timemodified = time();

        $DB->insert_record('quizaccess_proctoring_facematch_task', $facematch);
        mtrace("Facematch task created for report ID {$record->id}");
    }

    return true;
}



/**
 * Analyze specific quiz images for face matching.
 *
 * This function fetches the user's profile image, redirects if not available,
 * and processes the quiz records for the student. It fetches the webcam face
 * images for the student, compares them with the profile image, and updates
 * the face match status in the database. The function also handles logging
 * of warnings and updating the `awsflag` status based on the results.
 *
 * @param int $courseid The ID of the course.
 * @param int $cmid The ID of the course module.
 * @param int $studentid The ID of the student.
 * @param mixed $reportpageurl The URL to redirect to in case the reportpage .
 *
 * @return bool Returns `true` if records were processed successfully, `false` if no records found.
 */
function quizaccess_proctoring_bs_analyze_specific_quiz($courseid, $cmid, $studentid, $reportpageurl) {
    global $DB;

    // Get user profile image.
    $profileimageurl = quizaccess_proctoring_get_image_url($studentid);
    $redirecturl = new moodle_url('/mod/quiz/accessrule/proctoring/upload_image.php', ['id' => $studentid]);

    // Redirect if profile image is not available.
    if (!$profileimageurl) {
        redirect(
            $redirecturl,
            get_string('user_image_not_uploaded', 'mod_quiz'),
            1,
            \core\output\notification::NOTIFY_WARNING
        );
    }

    // Update all as attempted.
    $DB->set_field_select(
        'quizaccess_proctoring_logs',
        'awsflag',
        1,
        "courseid = :courseid AND quizid = :quizid AND userid = :userid AND awsflag = :awsflag",
        [
            'courseid' => $courseid,
            'quizid' => $cmid,
            'userid' => $studentid,
            'awsflag' => 0,
        ]
    );

    // Check random limit.
    $limit = 5;
    $awschecknumber = quizaccess_proctoring_get_proctoring_settings('awschecknumber');

    if ($awschecknumber > 0) {
        $limit = (int)$awschecknumber;
    }

    // Prepare SQL query and parameters.
    $basequery = "SELECT e.id as reportid, e.userid as studentid, e.webcampicture as webcampicture,
        e.status as status, e.timemodified as timemodified, u.firstname as firstname,
        u.lastname as lastname, u.email as email
        FROM {quizaccess_proctoring_logs} e
        INNER JOIN {user} u ON u.id = e.userid
        WHERE e.courseid = :courseid AND e.quizid = :quizid AND u.id = :userid AND e.webcampicture != ''";

    $params = [
        'courseid' => $courseid,
        'quizid' => $cmid,
        'userid' => $studentid,
    ];

    if ($limit > 0) {
        $basequery .= " ORDER BY RAND() LIMIT " . (int)$limit; // Ensure $limit is sanitized.
    }
    // Execute the query.
    $sqlexecuted = $DB->get_recordset_sql($basequery, $params);

    // Process each record.
    foreach ($sqlexecuted as $row) {
        $reportid = $row->reportid;

        // Get face images for comparison.
        list($userfaceimageurl, $webcamfaceimageurl) = quizaccess_proctoring_get_face_images($reportid);

        if (!$userfaceimageurl || !$webcamfaceimageurl) {
            // Log warning if faces are not found.
            quizaccess_proctoring_log_fm_warning($reportid);

            // Set awsflag = 3 if face not found.
            quizaccess_proctoring_update_match_result($reportid, 0, 3);
            continue;
        }

        // Perform face extraction and comparison.
        quizaccess_proctoring_extracted($userfaceimageurl, $webcamfaceimageurl, $reportid, $reportpageurl);
    }

    // Close the recordset.
    $sqlexecuted->close();

    return true;
}


/**
 * Get proctoring settings values from the database.
 *
 * This function retrieves the value of a specific proctoring setting for the
 * plugin `quizaccess_proctoring` from the Moodle configuration table.
 * If the setting is not found, it returns an empty string.
 *
 * @param string $settingtype The name of the setting to retrieve (e.g., 'awschecknumber').
 *
 * @return string The value of the specified setting, or an empty string if the setting is not found.
 */
function quizaccess_proctoring_get_proctoring_settings($settingtype) {
    global $DB;

    // Query the settings table for the specified setting type.
    $record = $DB->get_record('config_plugins', [
        'plugin' => 'quizaccess_proctoring',
        'name' => $settingtype,
    ], 'value', IGNORE_MISSING);

    // Return the value or an empty string if the setting is not found.
    return $record ? $record->value : '';
}

/**
 * Analyze a specific image for face match and logging.
 *
 * This function performs analysis on a specific image associated with a report.
 * It retrieves face images, performs a face match operation, and updates the database with the results.
 * If the face images are not found, an error is logged, and the user is redirected with an error message.
 *
 * @param int $reportid The ID of the proctoring report record to analyze.
 * @param mixed $redirecturl The URL to redirect to if an error occurs.
 *
 * @return bool Returns true if the analysis was successful, false if no record is found or if an error occurs.
 */
function quizaccess_proctoring_bs_analyze_specific_image($reportid, $redirecturl) {
    global $DB;

    // Fetch the record for the specific report ID.
    $reportdata = $DB->get_record('quizaccess_proctoring_logs', ['id' => $reportid], 'id, courseid, quizid, userid, webcampicture');

    if (!$reportdata) {
        redirect(
            $redirecturl,
            get_string('error_invalid_report', 'quizaccess_proctoring'),
            1,
            \core\output\notification::NOTIFY_ERROR
        );
        return false;
    }

    $studentid = $reportdata->userid;
    $courseid = $reportdata->courseid;
    $cmid = $reportdata->quizid;

    // Retrieve face images.
    list($userfaceimageurl, $webcamfaceimageurl) = quizaccess_proctoring_get_face_images($reportid);

    if (!$userfaceimageurl || !$webcamfaceimageurl) {
        // Log a face match warning.
        quizaccess_proctoring_log_fm_warning($reportid);

        // Update the match result with an error flag (awsflag = 3).
        quizaccess_proctoring_update_match_result($reportid, 0, 3);

        // Redirect with an error message.
        redirect(
            $redirecturl,
            get_string('error_face_not_found', 'quizaccess_proctoring'),
            1,
            \core\output\notification::NOTIFY_ERROR
        );
        return true;
    }

    // Update logs to mark all as attempted.
    $DB->execute(
        "UPDATE {quizaccess_proctoring_logs}
         SET awsflag = 1
         WHERE courseid = :courseid AND quizid = :quizid AND userid = :userid AND awsflag = 0",
        [
            'courseid' => $courseid,
            'quizid' => $cmid,
            'userid' => $studentid,
        ]
    );

    // Perform face extraction analysis.
    quizaccess_proctoring_extracted($userfaceimageurl, $webcamfaceimageurl, $reportid, $redirecturl);
    redirect(
        $redirecturl,
        get_string('facematch', 'quizaccess_proctoring'),
        1,
        \core\output\notification::NOTIFY_SUCCESS
    );

    return true;
}


/**
 * Analyze a specific image for face match and logging.
 *
 * This function performs analysis on a specific image associated with a report.
 * It retrieves face images, performs a face match operation, and updates the database with the results.
 * If the face images are not found, an error is logged, and the user is redirected with an error message.
 *
 * @param int $reportid The ID of the proctoring report record to analyze.
 *
 * @return bool Returns true if the analysis was successful, false if no record is found or if an error occurs.
 */
function quizaccess_proctoring_bs_analyze_specific_image_from_validate($reportid) {
    global $DB;

    // Fetch report data from the database based on the provided report ID.
    $reportdata = $DB->get_record('quizaccess_proctoring_logs', ['id' => $reportid], 'id, courseid, quizid, userid, webcampicture');

    // If the report data exists, proceed with analysis.
    if ($reportdata) {
        $studentid = $reportdata->userid;
        $courseid = $reportdata->courseid;
        $cmid = $reportdata->quizid;

        // Retrieve the user's face image and webcam image for comparison.
        list($userfaceimageurl, $webcamfaceimageurl) = quizaccess_proctoring_get_face_images($reportid);

        // If either face image is not found, log the warning and update the result.
        if (!$userfaceimageurl || !$webcamfaceimageurl) {
            // Log the warning for face match.
            quizaccess_proctoring_log_fm_warning($reportid);

            // Update the match result with flag indicating face match failure (awsflag = 3).
            $awsflag = 3;
            quizaccess_proctoring_update_match_result($reportid, 0, $awsflag);
            return;
        }

        // Update all logs as attempted by setting awsflag to 1.
        $DB->execute(
            "UPDATE {quizaccess_proctoring_logs}
             SET awsflag = 1
             WHERE courseid = :courseid AND quizid = :quizid AND userid = :userid AND awsflag = 0",
            [
                'courseid' => $courseid,
                'quizid' => $cmid,
                'userid' => $studentid,
            ]
        );

        // Perform the extraction process for face images.
        $bsapi = quizaccess_proctoring_get_proctoring_settings('bsapi');
        $bsapikey = quizaccess_proctoring_get_proctoring_settings('bs_api_key');

        // Perform the extraction process for face images.
        if (!empty($bsapi) && !empty($bsapikey)) {
            quizaccess_proctoring_extracted($userfaceimageurl, $webcamfaceimageurl, $reportid);
        } else {
            quizaccess_proctoring_update_match_result($reportid, 0, 101); // If api is not set.
            return;
        }
    }

    return true;
}


/**
 * Retrieve the face images for a specific report.
 *
 * This function fetches both the user's face image and the webcam face image associated with
 * a given proctoring report. If the user's image is not uploaded, it redirects to the image upload page.
 * If no images are found, the function returns `null` for both face images.
 *
 * @param int $reportid The ID of the proctoring report to fetch the images for.
 *
 * @return array An array containing the user's face image URL and the webcam face image URL.
 *               Both values will be `null` if no images are found.
 */
function quizaccess_proctoring_get_face_images($reportid) {
    global $DB;

    // Fetch report data for the given report ID.
    $reportdata = $DB->get_record('quizaccess_proctoring_logs', ['id' => $reportid]);

    if (!$reportdata) {
        return [null, null];
    }

    $studentid = $reportdata->userid;

    // Fetch webcam face images associated with the report.
    $webcamfaceimage = $DB->get_records(
        'quizaccess_proctoring_face_images',
        [
            'parentid' => $reportid,
            'parent_type' => 'camshot_image',
            'facefound' => 1,
        ]
    );

    $webcamfaceimageurl = '';
    if ($webcamfaceimage) {
        // If there are multiple webcam images, use the first one.
        $firstwebcamimage = reset($webcamfaceimage);
        $webcamfaceimageurl = $firstwebcamimage->faceimage;
    }

    // Fetch user image data.
    $userimagerow = $DB->get_record('quizaccess_proctoring_user_images', ['user_id' => $studentid]);

    $redirecturl = new moodle_url('/mod/quiz/accessrule/proctoring/upload_image.php', ['id' => $studentid]);

    // If user image is not uploaded, redirect to upload page with a warning.
    if (!$userimagerow) {
        redirect(
            $redirecturl,
            get_string('userimagenotuploaded', 'quizaccess_proctoring'),
            1,
            \core\output\notification::NOTIFY_WARNING
        );
    }

    // Fetch the face image associated with the user's image.
    $userfaceimageurl = '';
    if ($userimagerow) {
        $userfaceimagerow = $DB->get_record(
            'quizaccess_proctoring_face_images',
            ['parentid' => $userimagerow->id, 'parent_type' => 'admin_image']
        );

        if ($userfaceimagerow) {
            $userfaceimageurl = $userfaceimagerow->faceimage;
        }
    }

    return [$userfaceimageurl, $webcamfaceimageurl];
}

/**
 * Compares face images and updates the similarity result in the database.
 *
 * This function compares two face images using a similarity function and evaluates the result
 * against a threshold value specified in the configuration. If the similarity is below the threshold,
 * a warning is logged. The result is then updated in the database.
 *
 * @param string $profileimageurl The URL of the profile image to compare.
 * @param string $targetimage The URL of the target image to compare against.
 * @param int $reportid The ID of the report associated with the image comparison.
 * @param string|null $redirecturl The URL to redirect to if an error occurs (optional).
 *
 * @return void
 */
function quizaccess_proctoring_extracted(
    string $profileimageurl, string $targetimage,
    int $reportid, ?string $redirecturl = null): void {
    // Get the similarity result from the image comparison function.
    $similarityresult = quizaccess_proctoring_check_similarity_bs($profileimageurl, $targetimage, $redirecturl, $reportid);

    // Decode the JSON response from the similarity check.
    $response = json_decode($similarityresult);

    // Fetch the threshold for face matching.
    $threshold = (float) quizaccess_proctoring_get_proctoring_settings('threshold');

    // Initialize similarity variable.
    $similarity = 0;

    // Ensure response is valid and contains the expected data.
    if (isset($response->message) && $response->message === "Forbidden") {
        if (!empty($redirecturl)) {
            redirect(
                $redirecturl,
                get_string('invalid_api', 'quizaccess_proctoring'),
                1,
                \core\output\notification::NOTIFY_ERROR
            );
        } else {
            quizaccess_proctoring_update_match_result($reportid, 0, 101);// 101 for invalid service api.
            return;
        }
    } else if ($response && $response->statusCode == 200 && isset($response->body->distance)) {
        // Check if the distance is within the allowed threshold.
        if ($response->body->distance <= $threshold / 100) {
            $similarity = 100;
        } else {
            // Log a warning if the distance is above threshold.
            quizaccess_proctoring_log_fm_warning($reportid);
        }
    } else {
        // Log a warning if the response is invalid or if no matching data is found.
        quizaccess_proctoring_log_fm_warning($reportid);
    }

    // Update the match result in the database with the calculated similarity.
    quizaccess_proctoring_update_match_result($reportid, $similarity, 2);
}

/**
 * Returns face match similarity.
 *
 * This function sends two images (reference image and target image) to an external API for face comparison
 * and returns the similarity check result. It ensures that the necessary API settings (URL and key) are
 * available, then fetches the images, processes them, and sends a request to the API.
 * If the request succeeds, the API response is returned. Otherwise, an error is logged.
 *
 * @param string $referenceimageurl The URL of the reference image (profile image).
 * @param string $targetimageurl The URL of the target image (webcam image).
 * @param string $redirecturl The URL to redirect to if an error occurs.
 * @param int $reportid The ID of the report associated with the image comparison.
 *
 * @return bool|string The API response as a string, or false on failure.
 */
function quizaccess_proctoring_check_similarity_bs(string $referenceimageurl, string $targetimageurl, $redirecturl, $reportid) {
    global $CFG;

    // Fetch the required API settings.
    $bsapi = quizaccess_proctoring_get_proctoring_settings('bsapi');
    $bsapikey = quizaccess_proctoring_get_proctoring_settings('bs_api_key');

    // Ensure the API URL and key are available.
    if (empty($bsapi) || empty($bsapikey)) {
        mtrace('Error: Missing BS API URL or API key.');
        return false;
    }

    // Load images from the provided URLs and save them temporarily.
    $image1 = basename($referenceimageurl);
    $image2 = basename($targetimageurl);
    $imagepath1 = $CFG->dataroot . '/temp/' . $image1;
    $imagepath2 = $CFG->dataroot . '/temp/' . $image2;

    try {
        file_put_contents($imagepath1, file_get_contents($referenceimageurl));
        file_put_contents($imagepath2, file_get_contents($targetimageurl));
    } catch (Exception $e) {
        mtrace("Error: Unable to save images to temporary directory.");
        return false;
    }

    // Get image data for API request.
    $imagedata1 = file_get_contents($imagepath1);
    $imagedata2 = file_get_contents($imagepath2);

    // Prepare the data for the API request.
    $data = [
        'original_img_response' => base64_encode($imagedata1),
        'face_img_response' => base64_encode($imagedata2),
    ];

    // Convert the data to JSON format.
    $payload = json_encode($data);

    // Initialize Moodle's cURL.
    $curl = new curl();

    // Set cURL options.
    $options = [
        'CURLOPT_TIMEOUT' => 30, // Set timeout.
        'CURLOPT_FOLLOWLOCATION' => true, // Allow redirects.
        'CURLOPT_HTTPHEADER' => [
            'x-api-key: ' . $bsapikey,
            'Content-Type: application/json',
        ],
    ];

    // Execute the POST request.
    $response = $curl->post($bsapi, $payload, $options);

    // Handle cURL errors.
    if ($curl->get_errno()) {
        if (!empty($redirecturl)) {
            redirect(
                $redirecturl,
                get_string('invalid_service_api', 'quizaccess_proctoring'),
                1,
                \core\output\notification::NOTIFY_ERROR
            );
        } else {
            quizaccess_proctoring_update_match_result($reportid, 0, 101); // 101 for invalid service API.
        }

        return false;
    }

    // Clean up the temporary images.
    @unlink($imagepath1);
    @unlink($imagepath2);

    // Return the response from the API.
    return $response;
}


/**
 * Retrieves an authentication token from the BS API.
 *
 * This function sends a request to the BS API using the provided username and password,
 * retrieves an authentication token, and returns it. If any required settings are missing
 * or an error occurs during the request, it returns `false`.
 *
 * @return string|false The token on success or false on failure.
 */
function quizaccess_proctoring_get_token() {

    // Fetch required settings from proctoring settings.
    $bsapi = quizaccess_proctoring_get_proctoring_settings('bsapi') . '/get_token';
    $bsusername = quizaccess_proctoring_get_proctoring_settings('username');
    $bspassword = quizaccess_proctoring_get_proctoring_settings('password');

    // Check if all required settings are available.
    if (empty($bsapi) || empty($bsusername) || empty($bspassword)) {
        mtrace('Error: Missing BS API URL, username, or password.');
        return false; // Return false if any required setting is missing.
    }

    // Prepare the POST data.
    $postdata = [
        'username' => $bsusername,
        'password' => $bspassword,
    ];

    // Initialize Moodle's cURL class.
    $curl = new curl();

    // Set cURL options.
    $options = [
        'CURLOPT_TIMEOUT' => 30, // Timeout after 30 seconds.
        'CURLOPT_FOLLOWLOCATION' => true, // Follow redirects.
        'CURLOPT_HTTPHEADER' => [
            'Content-Type: multipart/form-data',
        ],
    ];

    // Execute the POST request.
    $response = $curl->post($bsapi, $postdata, $options);

    // Check for cURL errors.
    if ($curl->get_errno()) {
        mtrace('cURL Error: ' . $curl->error);
        return false; // Return false on cURL error.
    }

    // Decode the JSON response.
    $tokendata = json_decode($response);

    // Check if the token was received in the response.
    if (!empty($tokendata->token)) {
        return $tokendata->token; // Return the token.
    }

    // Log error if token is not found in the response.
    mtrace('Error: Token not found in the response.');
    return false; // Return false if token is not found.
}

/**
 * Logs a face matching warning for the given report ID.
 *
 * This function checks if a warning already exists for a particular user, course, and quiz.
 * If no warning exists, it inserts a new record into the `quizaccess_proctoring_fm_warnings` table.
 * If the report cannot be found, it logs an error message.
 *
 * @param int $reportid The report ID for which the warning is being logged.
 *
 * @return void
 */
function quizaccess_proctoring_log_fm_warning(int $reportid): void {
    global $DB;

    // Fetch the report data.
    $report = $DB->get_record('quizaccess_proctoring_logs', ['id' => $reportid]);

    // Check if the report exists.
    if ($report) {
        // Extract necessary data.
        $userid = $report->userid;
        $courseid = $report->courseid;
        $quizid = $report->quizid;

        // Check if a warning already exists for this user, course, and quiz.
        $existingwarning = $DB->get_record('quizaccess_proctoring_fm_warnings', [
            'userid' => $userid,
            'courseid' => $courseid,
            'quizid' => $quizid,
        ]);

        // If no warning exists, insert a new record.
        if (!$existingwarning) {
            // Prepare a new warning object.
            $warning = new stdClass();
            $warning->reportid = $reportid;
            $warning->courseid = $courseid;
            $warning->quizid = $quizid;
            $warning->userid = $userid;

            // Insert the new warning record into the database.
            $DB->insert_record('quizaccess_proctoring_fm_warnings', $warning);
        }
    } else {
        // Log a message if the report cannot be found.
        mtrace('Error: Report ID ' . $reportid . ' not found.');
    }
}

/**
 * Saves the face image as a file and returns its URL.
 *
 * This function decodes a base64 string, saves the image as a file in Moodle's file system,
 * and returns a URL to access the file.
 *
 * @param string $data The base64 encoded image data.
 * @param int $userid The ID of the user who uploaded the image.
 * @param stdClass $record The file record that contains metadata.
 * @param context $context The context for the file (usually the course or activity context).
 * @param stored_file_system $fs The file storage system instance.
 * @return moodle_url The URL to access the saved face image.
 */
function quizaccess_proctoring_geturl_of_faceimage(string $data, int $userid, stdClass $record, $context, $fs): moodle_url {
    // Remove any metadata from the base64 string.
    list(, $data) = explode(',', $data);

    // Decode the base64 data into raw binary image data.
    $data = base64_decode($data);

    // Generate a unique filename for the image.
    $filename = 'faceimage-' . $userid . '-' . time() . random_int(1, 1000) . '.png';

    // Set the filename and context ID in the file record.
    $record->filename = $filename;
    $record->contextid = $context->id;
    $record->userid = $userid;

    // Ensure the file is created in Moodle's file storage system.
    try {
        $fs->create_file_from_string($record, $data);
    } catch (Exception $e) {
        // Handle any exceptions during file storage creation.
        throw new moodle_exception('filecreationerror', 'error', '', $e->getMessage());
    }

    // Return the URL to access the stored file.
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
