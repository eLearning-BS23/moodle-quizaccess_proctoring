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

/*
 * Lib for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
defined('MOODLE_INTERNAL') || die();
const F_1_JPG = '/f1.jpg';
const GENERIC_SELECT_STATMENT = ' SELECT e.id as reportid, e.userid as studentid, e.webcampicture as webcampicture,
 e.status as status, ';

const COMMON_SELECT = ' e.timemodified as timemodified, u.firstname as firstname, u.lastname as lastname,
u.email as email from {quizaccess_proctoring_logs} e INNER JOIN {user} u  ON u.id = e.userid ';

const TEMP = '/temp/';

const TIMEMODIFIED_AS_TIMEMODIFIED =
' e.timemodified as timemodified, u.firstname as firstname, u.lastname as lastname, u.email as email ';
const FROM_QUIZACCESS_PROCTORING_LOGS_INNER_JOIN_USERS =
' from {quizaccess_proctoring_logs} e INNER JOIN {user} u  ON u.id = e.userid ';
const USER_PIX_PHP = '/user/pix.php/';
require_once(__DIR__ . '/vendor/autoload.php');

$token = "";

use Aws\Rekognition\RekognitionClient;

/**
 * Serve the files.
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param context $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 *
 * @return bool false if the file not found, just send the file otherwise and do not return anything
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

function quizaccess_proctoring_get_image_url($userid) {
    $context = context_system::instance();

    $fs = get_file_storage();
    if ($files = $fs->get_area_files($context->id, 'quizaccess_proctoring', 'user_photo')) {

        foreach ($files as $file) {
            if ($userid == $file->get_itemid() && $file->get_filename() != '.') {
                // Build the File URL. Long process! But extremely accurate.
                $fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename(), true);
                // Display the image
                $download_url = $fileurl->get_port() ? $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path() . ':' . $fileurl->get_port() : $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path();
                return $download_url;
            }
        }
    }
    return false;
}

function quizaccess_proctoring_get_image_file($userid) {
    $context = context_system::instance();

    $fs = get_file_storage();
    if ($files = $fs->get_area_files($context->id, 'quizaccess_proctoring', 'user_photo')) {

        foreach ($files as $file) {
            if ($userid == $file->get_itemid() && $file->get_filename() != '.') {
                // Return the image file
                return $file;
            }
        }
    }
    return false;
}

/**
 * Updates match result.
 *
 * @param int $rowid the reportid
 * @param string $matchresult similarity
 *
 * @return array similaritycheck
 */
function update_match_result($rowid, $matchresult, $awsflag) {
    global $DB;
    $score = (int)$matchresult;
    $updatesql = "UPDATE {quizaccess_proctoring_logs} SET awsflag = '$awsflag', awsscore = '$score' WHERE id='$rowid'";
    $DB->execute($updatesql);
}

/**
 * Returns face match similarity.
 *
 * @param string $referenceimageurl the courseid
 * @param string $targetimageurl the course module id
 *
 * @return array similaritycheck
 */
function check_similarity_aws($referenceimageurl, $targetimageurl) {

    $awskey = get_proctoring_settings('awskey');
    $awssecret = get_proctoring_settings('awssecret');
    $threshhold = (int)get_proctoring_settings('awsfcthreshold');

    $credentials = new Aws\Credentials\Credentials($awskey, $awssecret);
    $rekognitionclient = RekognitionClient::factory([
        'region' => 'us-east-1',
        'version' => 'latest',
        'credentials' => $credentials,
    ]);

    try {
        $comparefaceresult = $rekognitionclient->compareFaces([
            'SimilarityThreshold' => $threshhold,
            'SourceImage' => [
                'Bytes' => file_get_contents($referenceimageurl),
            ],
            'TargetImage' => [
                'Bytes' => file_get_contents($targetimageurl),
            ],
        ]);

        return $comparefaceresult['FaceMatches'];
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Execute facerecognition task.
 *
 * @return bool false if no record found
 */
function execute_fm_task() {
    global $DB;
    // Get 5 task.
    $sql = 'SELECT * FROM {proctoring_facematch_task} LIMIT 5';
    $data = $DB->get_recordset_sql($sql);
    $facematchmethod = get_proctoring_settings('fcmethod');
    foreach ($data as $row) {
        $rowid = $row->id;
        $reportid = $row->reportid;
        $refimageurl = $row->refimageurl;
        $targetimageurl = $row->targetimageurl;
        if ($facematchmethod == 'AWS') {
            // Get Match result.
            get_match_result($refimageurl, $targetimageurl, $reportid);
            // Delete from task table.
            $DB->delete_records('proctoring_facematch_task', ['id' => $rowid]);
        } else if ($facematchmethod == 'BS') {
            $token = get_token();
            list($userfaceimageurl, $webcamfaceimageurl) = get_face_images($reportid);
            // extracted($refimageurl, $targetimageurl, $reportid, $token);

            extracted($userfaceimageurl, $webcamfaceimageurl, $reportid, $token);
            // Delete from task table.
            $DB->delete_records('proctoring_facematch_task', ['id' => $rowid]);
        } else {
            echo 'Invalid fc method<br/>';
        }
    }
}

/**
 * @param $refimageurl
 * @param $targetimageurl
 * @param $reportid
 */
function get_match_result($refimageurl, $targetimageurl, $reportid): array {
    $similarityresult = check_similarity_aws($refimageurl, $targetimageurl);

    // Log AWS API Call.
    $apiresponse = json_encode($similarityresult);
    log_aws_api_call($reportid, $apiresponse);

    // Update Match result.
    if (!empty($similarityresult)) {
        if (isset($similarityresult[0]['Similarity'])) {
            $similarity = $similarityresult[0]['Similarity'];
        } else {
            $similarity = 0;
            log_fm_warning($reportid);
        }
    } else {
        $similarity = 0;
        log_fm_warning($reportid);
    }
    update_match_result($reportid, $similarity, 2);

    return [$similarityresult, $similarity];
}

/**
 * Execute facerecognition task.
 *
 * @return bool false if no record found
 */
function log_facematch_task() {
    global $DB;
    $sql = 'SELECT DISTINCT courseid,quizid,userid FROM {quizaccess_proctoring_logs}  WHERE awsflag = 0';
    $data = $DB->get_recordset_sql($sql);
    foreach ($data as $row) {
        $courseid = $row->courseid;
        $quizid = $row->quizid;
        $userid = $row->userid;
        log_specific_quiz($courseid, $quizid, $userid);
    }

    echo 'Log success';
}

/**
 * Log for analysis.
 *
 * @param int $courseid the courseid
 * @param int $cmid the course module id
 * @param int $studentid the context
 *
 * @return bool false if no record found
 */
function log_specific_quiz($courseid, $cmid, $studentid) {
    global $DB;
    // Get user profile image.
    $user = core_user::get_user($studentid);
    $profileimageurl = '';
    // if ($user->picture) {
    //     $profileimageurl = new moodle_url(USER_PIX_PHP . $user->id . F_1_JPG);
    // }
    $profileimageurl = quizaccess_proctoring_get_image_url($studentid);

    // Update all as attempted.
    $updatesql = 'UPDATE {quizaccess_proctoring_logs}'
        . 'SET awsflag = 1'
        . "WHERE courseid = '$courseid' AND quizid = '$cmid' AND userid = '$studentid'";
    $DB->execute($updatesql);

    // Check random.
    $limit = 5;
    $awschecknumber = get_proctoring_settings('awschecknumber');
    if ($awschecknumber != '') {
        $limit = (int)$awschecknumber;
    }

    if ($limit == -1) {
        $sql = GENERIC_SELECT_STATMENT . COMMON_SELECT
            . " WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' AND u.id = '$studentid' AND e.webcampicture != '' ";
    } else if ($limit > 0) {
        $sql = GENERIC_SELECT_STATMENT
            . TIMEMODIFIED_AS_TIMEMODIFIED
            . FROM_QUIZACCESS_PROCTORING_LOGS_INNER_JOIN_USERS
            . " WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' AND u.id = '$studentid' AND e.webcampicture != '' "
            . ' ORDER BY RAND() '
            . " LIMIT $limit ";
    } else {
        $sql = GENERIC_SELECT_STATMENT
            . TIMEMODIFIED_AS_TIMEMODIFIED
            . FROM_QUIZACCESS_PROCTORING_LOGS_INNER_JOIN_USERS
            . " WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' AND u.id = '$studentid' AND e.webcampicture != ''";
    }

    $sqlexecuted = $DB->get_recordset_sql($sql);

    foreach ($sqlexecuted as $row) {
        $reportid = $row->reportid;
        $snapshot = $row->webcampicture;
        echo $snapshot;
        if ($snapshot != '') {
            $inserttaskrow = new stdClass();
            //$inserttaskrow->refimageurl = $profileimageurl->__toString();
            $inserttaskrow->refimageurl = $profileimageurl;
            $inserttaskrow->targetimageurl = $snapshot;
            $inserttaskrow->reportid = $reportid;
            $inserttaskrow->timemodified = time();
            $DB->insert_record('proctoring_facematch_task', $inserttaskrow);
        }
    }

    return true;
}

/**
 * Analyze specific Quiz images.
 *
 * @param int $courseid the courseid
 * @param int $cmid the course module id
 * @param int $studentid the context
 *
 * @return bool false if no record found
 */
function aws_analyze_specific_quiz($courseid, $cmid, $studentid) {
    global $DB;
    // Get user profile image.
    $user = core_user::get_user($studentid);
    $profileimageurl = '';
    // if ($user->picture) {
    //     $profileimageurl = new moodle_url(USER_PIX_PHP . $user->id . F_1_JPG);
    // }

    $profileimageurl = quizaccess_proctoring_get_image_url($studentid);
    // Update all as attempted.
    $updatesql = ' UPDATE {quizaccess_proctoring_logs} '
        . ' SET awsflag = 1 '
        . " WHERE courseid = '$courseid' AND quizid = '$cmid' AND userid = '$studentid' AND awsflag = 0 ";
    $DB->execute($updatesql);

    // Check random.
    $limit = 5;
    $awschecknumber = get_proctoring_settings('awschecknumber');
    if ($awschecknumber != '') {
        $limit = (int)$awschecknumber;
    }

    if ($limit == -1) {
        $sql = GENERIC_SELECT_STATMENT
            . TIMEMODIFIED_AS_TIMEMODIFIED
            . FROM_QUIZACCESS_PROCTORING_LOGS_INNER_JOIN_USERS
            . " WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' AND u.id = '$studentid' AND e.webcampicture != '' ";
    } else if ($limit > 0) {
        $sql = GENERIC_SELECT_STATMENT
            . TIMEMODIFIED_AS_TIMEMODIFIED
            . FROM_QUIZACCESS_PROCTORING_LOGS_INNER_JOIN_USERS
            . " WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' AND u.id = '$studentid' AND e.webcampicture != '' "
            . ' ORDER BY RAND() '
            . " LIMIT $limit";
    } else {
        $sql = GENERIC_SELECT_STATMENT
            . TIMEMODIFIED_AS_TIMEMODIFIED
            . FROM_QUIZACCESS_PROCTORING_LOGS_INNER_JOIN_USERS
            . " WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' AND u.id = '$studentid' AND e.webcampicture != ''";
    }

    $sqlexecuted = $DB->get_recordset_sql($sql);

    foreach ($sqlexecuted as $row) {
        $reportid = $row->reportid;
        //$refimageurl = $profileimageurl->__toString();
        $refimageurl = $profileimageurl;
        $targetimageurl = $row->webcampicture;
        get_match_result($refimageurl, $targetimageurl, $reportid);
    }

    return true;
}

/**
 * Analyze specific Quiz images.
 *
 * @param int $courseid the courseid
 * @param int $cmid the course module id
 * @param int $studentid the context
 *
 * @return bool false if no record found
 */
function bs_analyze_specific_quiz($courseid, $cmid, $studentid, $redirecturl) {
    global $DB;
    // Get user profile image.
    $user = core_user::get_user($studentid);
    $profileimageurl = '';
    // if ($user->picture) {
    //     $profileimageurl = new moodle_url(USER_PIX_PHP . $user->id . F_1_JPG);
    // }
    $profileimageurl = quizaccess_proctoring_get_image_url($studentid);
    // Update all as attempted.
    $updatesql = 'UPDATE {quizaccess_proctoring_logs}'
        . ' SET awsflag = 1 '
        . " WHERE courseid = '$courseid' AND quizid = '$cmid' AND userid = '$studentid' AND awsflag = 0";
    $DB->execute($updatesql);

    // Check random.
    $limit = 5;
    $awschecknumber = get_proctoring_settings('awschecknumber');
    if ($awschecknumber != '') {
        $limit = (int)$awschecknumber;
    }

    $sql = "SELECT e.id as reportid, e.userid as studentid, e.webcampicture as webcampicture, e.status as status,
        e.timemodified as timemodified, u.firstname as firstname, u.lastname as lastname, u.email as email
        from {quizaccess_proctoring_logs} e INNER JOIN {user} u  ON u.id = e.userid
        WHERE e.courseid = '$courseid' AND e.quizid = '$cmid' AND u.id = '$studentid' AND e.webcampicture != ''";

    if ($limit > 0) {
        $sql .= " ORDER BY RAND() LIMIT $limit";
    }

    $sqlexecuted = $DB->get_recordset_sql($sql);

    $token = get_token();
    foreach ($sqlexecuted as $row) {
        $reportid = $row->reportid;
        // $refimageurl = $profileimageurl->__toString();
        $refimageurl = $profileimageurl;
        $targetimageurl = $row->webcampicture;

        list($userfaceimageurl, $webcamfaceimageurl) = get_face_images($reportid);

        if(!$userfaceimageurl || !$webcamfaceimageurl) {
            // redirect($redirecturl, "Error encountered while analyzing some images. Please contact with Admin",
            // 1,
            // \core\output\notification::NOTIFY_ERROR);
            // return;
            // Update face match result.
            log_fm_warning($reportid);
            $awsflag = 3;
            // Set $awsflag = 3 when face not found for admin / webcam image. 
            update_match_result($reportid, 0, $awsflag);
            
            continue;

        }
        extracted($userfaceimageurl, $webcamfaceimageurl, $reportid, $token);
    }

    return true;
}

/**
 * Get proctoring settings values.
 *
 * @param string $settingtype the courseid
 *
 * @return string
 */
function get_proctoring_settings($settingtype) {
    $value = '';
    global $DB;
    $settingssql = "SELECT * FROM {config_plugins}
            WHERE {config_plugins}.plugin = 'quizaccess_proctoring' AND {config_plugins}.name = '$settingtype'";
    $settingsdata = $DB->get_records_sql($settingssql);
    if (count($settingsdata) > 0) {
        foreach ($settingsdata as $row) {
            $value = $row->value;
        }
    }

    return $value;
}

/**
 * Analyze specific image.
 *
 * @param int $reportid the context
 *
 * @return bool false if no record found
 */
function aws_analyze_specific_image($reportid) {
    global $DB;
    $reportsql = 'SELECT id,courseid,quizid,userid,webcampicture FROM {quizaccess_proctoring_logs} WHERE id=:id';
    $reportdata = $DB->get_record_sql($reportsql, ['id' => $reportid]);

    if ($reportdata) {
        $studentid = $reportdata->userid;
        $courseid = $reportdata->courseid;
        $cmid = $reportdata->quizid;
        $targetimage = $reportdata->webcampicture;

        // Get user profile image.
        $user = core_user::get_user($studentid);
        $profileimageurl = '';
        // if ($user->picture) {
        //     $profileimageurl = new moodle_url(USER_PIX_PHP . $user->id . F_1_JPG);
        // }
        $profileimageurl = quizaccess_proctoring_get_image_url($studentid);
        // Update all as attempted.
        $updatesql = "UPDATE {quizaccess_proctoring_logs}
                SET awsflag = 1
                WHERE courseid = '$courseid' AND quizid = '$cmid' AND userid = '$studentid' AND awsflag = 0";
        $DB->execute($updatesql);

        get_match_result($profileimageurl, $targetimage, $reportid);
    }

    return true;
}

/**
 * Analyze specific image.
 *
 * @param int $reportid the context
 *
 * @return bool false if no record found
 */
function bs_analyze_specific_image($reportid, $redirecturl) {
    global $DB;
    $reportsql = 'SELECT id,courseid,quizid,userid,webcampicture FROM {quizaccess_proctoring_logs} WHERE id=:id';
    $reportdata = $DB->get_record_sql($reportsql, ['id' => $reportid]);

    if ($reportdata) {
        $studentid = $reportdata->userid;
        $courseid = $reportdata->courseid;
        $cmid = $reportdata->quizid;

        list($userfaceimageurl, $webcamfaceimageurl) = get_face_images($reportid);
        if(!$userfaceimageurl || !$webcamfaceimageurl) {
            // Update face match result.
            log_fm_warning($reportid);
            $awsflag = 3;
            // Set $awsflag = 3 when face not found for admin / webcam image. 
            update_match_result($reportid, 0, $awsflag);
            redirect($redirecturl, "Error encountered while analyzing the image. Please contact with Admin",
            1,
            \core\output\notification::NOTIFY_ERROR);
            return;
        }

        // Update all as attempted.
        $updatesql = "UPDATE {quizaccess_proctoring_logs}
                SET awsflag = 1
                WHERE courseid = '$courseid' AND quizid = '$cmid' AND userid = '$studentid' AND awsflag = 0";
        $DB->execute($updatesql);
        $token = get_token();
        //extracted($profileimageurl, $targetimage, $reportid, $token);
        extracted($userfaceimageurl, $webcamfaceimageurl, $reportid, $token);
    }

    return true;
}

/**
 * Analyze specific image from validate face without redirect.
 *
 * @param int $reportid the context
 *
 * @return bool false if no record found
 */
function bs_analyze_specific_image_from_validate($reportid) {
    global $DB;
    $reportsql = 'SELECT id,courseid,quizid,userid,webcampicture FROM {quizaccess_proctoring_logs} WHERE id=:id';
    $reportdata = $DB->get_record_sql($reportsql, ['id' => $reportid]);

    if ($reportdata) {
        $studentid = $reportdata->userid;
        $courseid = $reportdata->courseid;
        $cmid = $reportdata->quizid;

        list($userfaceimageurl, $webcamfaceimageurl) = get_face_images($reportid);
        if(!$userfaceimageurl || !$webcamfaceimageurl) {
            // Update face match result.
            log_fm_warning($reportid);
            $awsflag = 3;
            // Set $awsflag = 3 when face not found for admin / webcam image. 
            update_match_result($reportid, 0, $awsflag);
            return;
        }

        // Update all as attempted.
        $updatesql = "UPDATE {quizaccess_proctoring_logs}
                SET awsflag = 1
                WHERE courseid = '$courseid' AND quizid = '$cmid' AND userid = '$studentid' AND awsflag = 0";
        $DB->execute($updatesql);
        $token = get_token();
        //extracted($profileimageurl, $targetimage, $reportid, $token);
        extracted($userfaceimageurl, $webcamfaceimageurl, $reportid, $token);
    }

    return true;
}

function get_face_images($reportid) {
    global $DB;
    $reportdata = $DB->get_record('quizaccess_proctoring_logs', array('id' => $reportid));
    $studentid = $reportdata->userid;
    $webcamfaceimage = $DB->get_record('proctoring_face_images', array('parentid' => $reportid, 'parent_type' => 'camshot_image'));
    $webcamfaceimageurl = "";
    if($webcamfaceimage) {
        $webcamfaceimageurl = $webcamfaceimage->faceimage;
    }
    $userimagerow = $DB->get_record('proctoring_user_images', array('user_id' => $studentid));
    $userfaceimageurl = "";
    if($userimagerow) {
        $userfaceimagerow = $DB->get_record('proctoring_face_images', array('parentid' => $userimagerow->id, 'parent_type' => 'admin_image'));
        if($userfaceimagerow) {
            $userfaceimageurl = $userfaceimagerow->faceimage;
        }
    }
    return [$userfaceimageurl, $webcamfaceimageurl];
}

/**
 * @param  $profileimageurl
 * @param $targetimage
 */
function extracted($profileimageurl, $targetimage, int $reportid, $token): void {
    $similarityresult = check_similarity_bs($profileimageurl, $targetimage, $token);
    $response = json_decode($similarityresult);
    
    $threshold = get_proctoring_settings('threshold');
    // Update Match result.
    if (isset($response->distance)) {
        if ($response->distance <= $threshold/100) {
            $similarity = 100;
        } else {
            $similarity = 0;
            log_fm_warning($reportid);
        }
    } else {
        $similarity = 0;
        log_fm_warning($reportid);
    }
    update_match_result($reportid, $similarity, 2);
}

/**
 * Analyze specific image.
 *
 * @param int $reportid the context
 *
 * @return bool false if no record found
 */
function log_aws_api_call($reportid, $apiresponse) {
    global $DB;
    $log = new stdClass();
    $log->reportid = $reportid;
    $log->apiresponse = $apiresponse;
    $log->timecreated = time();

    return $DB->insert_record('aws_api_log', $log);
}

/**
 * Returns face match similarity.
 *
 * @param string $referenceimageurl the courseid
 * @param string $targetimageurl the course module id
 *
 * @return bool|string similaritycheck
 */
function check_similarity_bs($referenceimageurl, $targetimageurl, $token) {
    global $CFG;
    $bsapi = get_proctoring_settings('bsapi') . '/verify_form';
    //$bsapi = get_proctoring_settings('bsapi') . '/verify';
    $threshold = get_proctoring_settings('threshold');
    
    // Load File.
    $image1 = basename($referenceimageurl);
    file_put_contents($CFG->dataroot . TEMP . $image1, file_get_contents($referenceimageurl));
    $image2 = basename($targetimageurl);
    file_put_contents($CFG->dataroot . TEMP . $image2, file_get_contents($targetimageurl));

    // Check similarity.
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $bsapi,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token,
        ),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        // CURLOPT_POSTFIELDS => ['original_img' => new CURLFILE($CFG->dataroot . TEMP . $image1),
        //     'face_img' => new CURLFILE($CFG->dataroot . TEMP . $image2), 'threshold' => $threshold],
        CURLOPT_POSTFIELDS => ['original_img' => new CURLFILE($CFG->dataroot . TEMP . $image1),
            'face_img' => new CURLFILE($CFG->dataroot . TEMP . $image2)],
    ]);
    $response = curl_exec($curl);

    curl_close($curl);

    // Clear File.
    unlink($CFG->dataroot . TEMP . $image1);
    unlink($CFG->dataroot . TEMP . $image2);

    return $response;
}

/**
 * Get token from Facematching API
 * @return mixed
 */
function get_token() {

    $bsapi = get_proctoring_settings('bsapi') . '/get_token';
    $bsusername = get_proctoring_settings('username');
    $bspassword = get_proctoring_settings('password');
    // Check similarity.
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $bsapi,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: multipart/form-data'
        ),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => ['username' => $bsusername,
            'password' => $bspassword],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    $tokenData = json_decode($response);
    $token = $tokenData->token;
    return $token;
}

/**
 * Log fm warnings.
 *
 * @param string $reportid the reportid
 *
 * @return void
 */
function log_fm_warning($reportid) {
    global $DB;
    $reportsql = 'SELECT * FROM {quizaccess_proctoring_logs} WHERE id=:id';
    $reportdata = $DB->get_record_sql($reportsql, ['id' => $reportid]);

    if ($reportdata) {
        $userid = $reportdata->userid;
        $courseid = $reportdata->courseid;
        $quizid = $reportdata->quizid;
        $warningsql = 'SELECT * FROM {proctoring_fm_warnings} WHERE userid = :userid AND courseid = :courseid AND quizid = :quizid';

        $params = [];
        $params['userid'] = $userid;
        $params['courseid'] = $courseid;
        $params['quizid'] = $quizid;

        // Check availability.
        $warnings = $DB->get_record_sql($warningsql, $params);

        // If does not exists.
        if (!$warnings) {
            $warning = new stdClass();
            $warning->reportid = $reportid;
            $warning->courseid = $courseid;
            $warning->quizid = $quizid;
            $warning->userid = $userid;
            $DB->insert_record('proctoring_fm_warnings', $warning);
        }
    }
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
function quizaccess_proctoring_geturl_of_faceimage(string $data, int $userid, stdClass $record, $context, $fs) {
    list(, $data) = explode(',', $data);
    $data = base64_decode($data);
    $filename = 'faceimage-' . $userid . '-' . time() . random_int(1, 1000) . '.png';

    $record->filename = $filename;
    $record->contextid = $context->id;
    $record->userid = $userid;

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


