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

namespace quizaccess_proctoring;

/**
 * Additional Settings Helper for the quizaccess_proctoring plugin.
 *
 * This class provides helper functions related to additional settings
 * for the `quizaccess_proctoring` plugin. It includes methods for managing
 * plugin settings and configurations specific to proctoring functionality.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class additional_settings_helper {
    /**
     * Searches for a specific user's proctoring log based on provided filters.
     *
     * This function constructs a dynamic SQL query to search for proctoring logs
     * based on the provided username, email, course name, and quiz name.
     *
     * @param string $username The username of the user to search for.
     * @param string $email The email of the user to search for.
     * @param string $coursename The name of the course to filter the results.
     * @param string $quizname The name of the quiz to filter the results.
     * @return moodle_recordset A recordset of matching proctoring logs.
     * @throws dml_exception If a database query fails.
     */
    public function search(string $username, string $email, string $coursename, string $quizname) {
        global $DB;
        $params = [];
        $whereclausearray1 = [];
        $whereclausearray2 = [];
        // UserName.
        $usernamequeryparts = $this->usernamequerypart($username);
        $usernameparams = $usernamequeryparts["params"];
        $usernamewhereclause1 = $usernamequeryparts["whereclausearray1"];
        $usernamewhereclause2 = $usernamequeryparts["whereclausearray2"];
        $params = array_merge($params, $usernameparams);
        $whereclausearray1 = array_merge($whereclausearray1, $usernamewhereclause1);
        $whereclausearray2 = array_merge($whereclausearray2, $usernamewhereclause2);
        // Email.
        $emailqueryparts = $this->emailquerypart($email, $username);
        $emailparams = $emailqueryparts["params"];
        $emailwhereclause1 = $emailqueryparts["whereclausearray1"];
        $emailwhereclause2 = $emailqueryparts["whereclausearray2"];
        $params = array_merge($params, $emailparams);
        $whereclausearray1 = array_merge($whereclausearray1, $emailwhereclause1);
        $whereclausearray2 = array_merge($whereclausearray2, $emailwhereclause2);
        // Coursename.
        $coursenamequeryparts = $this->coursenamequerypart($coursename, $username);
        $coursenameparams = $coursenamequeryparts["params"];
        $coursenamewhereclause1 = $coursenamequeryparts["whereclausearray1"];
        $coursenamewhereclause2 = $coursenamequeryparts["whereclausearray2"];
        $params = array_merge($params, $coursenameparams);
        $whereclausearray1 = array_merge($whereclausearray1, $coursenamewhereclause1);
        $whereclausearray2 = array_merge($whereclausearray2, $coursenamewhereclause2);
        // Quizname.
        $quiznamequeryparts = $this->quiznamequerypart($quizname, $username);
        $quiznameparams = $quiznamequeryparts["params"];
        $quiznamewhereclause1 = $quiznamequeryparts["whereclausearray1"];
        $quiznamewhereclause2 = $quiznamequeryparts["whereclausearray2"];
        $params = array_merge($params, $quiznameparams);
        $whereclausearray1 = array_merge($whereclausearray1, $quiznamewhereclause1);
        $whereclausearray2 = array_merge($whereclausearray2, $quiznamewhereclause2);
        $totalclausecount = count($whereclausearray1) + count($whereclausearray2);
        $secondclausecount = count($whereclausearray2);

        if ($totalclausecount > 0) {
            $andjoin1 = implode(" AND ", $whereclausearray1);
            if ($secondclausecount > 0) {
                $andjoin2 = implode(" AND ", $whereclausearray2);
                $whereclause = " (".$andjoin1.") OR (".$andjoin2.") ";
            } else {
                $whereclause = " (".$andjoin1.")";
            }
        } else {
            return [];
        }

        $sql = "SELECT"
            ." e.id as reportid, "
            ." e.userid as studentid, "
            ." e.webcampicture as webcampicture, "
            ." e.status as status, "
            ." e.quizid as quizid, "
            ." e.courseid as courseid, "
            ." e.timemodified as timemodified, "
            ." u.firstname as firstname, "
            ." u.lastname as lastname, "
            ." u.email as email, "
            ." c.fullname as coursename, "
            ." q.name as quizname "
            ." from  {quizaccess_proctoring_logs} e "
            ." INNER JOIN {user} u  ON u.id = e.userid "
            ." INNER JOIN {course} c  ON c.id = e.courseid "
            ." INNER JOIN {course_modules} cm  ON cm.id = e.quizid "
            ." INNER JOIN {quiz} q  ON q.id = cm.instance "
            ." WHERE $whereclause ";

        return $DB->get_recordset_sql($sql, $params);
    }
    /**
     * Generates query parts for filtering by username.
     *
     * This function constructs SQL conditions to filter user records
     * based on the given username. If the username consists of two parts
     * (e.g., first name and last name), it will be split accordingly.
     * The search uses SQL LIKE conditions for flexible matching.
     *
     * @param string $username The username to search for.
     * @return array An associative array containing:
     *               - 'params' (array): Query parameters for named placeholders.
     *               - 'whereclausearray1' (array): SQL conditions for first name.
     *               - 'whereclausearray2' (array): SQL conditions for last name.
     */
    public function usernamequerypart($username) {
        global $DB;
        $params = [];
        $whereclausearray1 = [];
        $whereclausearray2 = [];

        if ($username !== "") {
            $namesplit = explode(" ", $username);
            $namelike1 = "(".$DB->sql_like('u.firstname', ':firstnamelike', false).")";
            $namelike2 = "(".$DB->sql_like('u.lastname', ':lastnamelike', false).")";
            $whereclausearray1[] = $namelike1;
            $whereclausearray2[] = $namelike2;
            if (count($namesplit) > 1) {

                $params['firstnamelike'] = $namesplit[0];
                $params['lastnamelike'] = $namesplit[1];
            } else {

                $params['firstnamelike'] = $username;
                $params['lastnamelike'] = $username;
            }
        }
        $queryparts = [];
        $queryparts["params"] = $params;
        $queryparts["whereclausearray1"] = $whereclausearray1;
        $queryparts["whereclausearray2"] = $whereclausearray2;
        return $queryparts;
    }

    /**
     * Generates query parts for filtering by email.
     *
     * This function constructs SQL conditions to filter user records
     * based on the given email. If a username is also provided,
     * an additional condition is applied to refine the search.
     * The search uses SQL LIKE conditions for flexible matching.
     *
     * @param string $email The email to search for.
     * @param string $username The username to check alongside the email.
     * @return array An associative array containing:
     *               - 'params' (array): Query parameters for named placeholders.
     *               - 'whereclausearray1' (array): SQL conditions for the email.
     *               - 'whereclausearray2' (array): Additional SQL conditions if a username is provided.
     */
    public function emailquerypart($email, $username) {
        global $DB;
        $params = [];
        $whereclausearray1 = [];
        $whereclausearray2 = [];

        if ($email !== "") {
            $emaillike1 = " ( ".$DB->sql_like('u.email', ':emaillike1', false)." ) ";
            if ($username !== "") {
                $emaillike2 = " ( ".$DB->sql_like('u.email', ':emaillike2', false)." ) ";
                $whereclausearray1[] = $emaillike1;
                $whereclausearray2[] = $emaillike2;
                $params['emaillike1'] = $email;
                $params['emaillike2'] = $email;
            } else {
                $whereclausearray1[] = $emaillike1;
                $params['emaillike1'] = $email;
            }
        }

        $queryparts = [];
        $queryparts["params"] = $params;
        $queryparts["whereclausearray1"] = $whereclausearray1;
        $queryparts["whereclausearray2"] = $whereclausearray2;

        return $queryparts;
    }

    /**
     * Generates query parts for filtering by course name.
     *
     * This function constructs SQL conditions to filter course records
     * based on the given course name. If a username is also provided,
     * an additional condition is applied to refine the search.
     * The search uses SQL LIKE conditions for flexible matching.
     *
     * @param string $coursename The course name to search for.
     * @param string $username The username to check alongside the course name.
     * @return array An associative array containing:
     *               - 'params' (array): Query parameters for named placeholders.
     *               - 'whereclausearray1' (array): SQL conditions for the course name.
     *               - 'whereclausearray2' (array): Additional SQL conditions if a username is provided.
     */
    public function coursenamequerypart($coursename, $username) {
        global $DB;
        $params = [];
        $whereclausearray1 = [];
        $whereclausearray2 = [];

        if ($coursename !== "") {
            $coursenamelike1 = " ( ".$DB->sql_like('c.fullname', ':coursenamelike1', false)." ) ";
            if ($username !== "") {
                $coursenamelike2 = " ( ".$DB->sql_like('c.fullname', ':coursenamelike2', false)." ) ";
                $whereclausearray1[] = $coursenamelike1;
                $whereclausearray2[] = $coursenamelike2;
                $params['coursenamelike1'] = $coursename;
                $params['coursenamelike2'] = $coursename;
            } else {
                $whereclausearray1[] = $coursenamelike1;
                $params['coursenamelike1'] = $coursename;
            }
        }

        $queryparts = [];
        $queryparts["params"] = $params;
        $queryparts["whereclausearray1"] = $whereclausearray1;
        $queryparts["whereclausearray2"] = $whereclausearray2;

        return $queryparts;
    }

    /**
     * Generates query parts for filtering by quiz name.
     *
     * This function constructs SQL conditions to filter quiz records
     * based on the given quiz name. If a username is also provided,
     * an additional condition is applied to refine the search.
     * The search uses SQL LIKE conditions for flexible matching.
     *
     * @param string $quizname The name of the quiz to search for.
     * @param string $username The username to check alongside the quiz name.
     * @return array An associative array containing:
     *               - 'params' (array): Query parameters for named placeholders.
     *               - 'whereclausearray1' (array): SQL conditions for the quiz name.
     *               - 'whereclausearray2' (array): Additional SQL conditions if a username is provided.
     */
    public function quiznamequerypart($quizname, $username) {
        global $DB;
        $params = [];
        $whereclausearray1 = [];
        $whereclausearray2 = [];

        if ($quizname !== "") {
            $quiznamelike1 = " ( ".$DB->sql_like('q.name', ':quiznamelike1', false)." ) ";
            if ($username !== "") {
                $quiznamelike2 = " ( ".$DB->sql_like('q.name', ':quiznamelike2', false)." ) ";
                $whereclausearray1[] = $quiznamelike1;
                $whereclausearray2[] = $quiznamelike2;
                $params['quiznamelike1'] = "%{$quizname}%";
                $params['quiznamelike2'] = "%{$quizname}%";
            } else {
                $whereclausearray1[] = $quiznamelike1;
                $params['quiznamelike1'] = "%{$quizname}%";
            }
        }

        $queryparts = [];
        $queryparts["params"] = $params;
        $queryparts["whereclausearray1"] = $whereclausearray1;
        $queryparts["whereclausearray2"] = $whereclausearray2;

        return $queryparts;
    }
    /**
     * Search for proctoring logs by course ID.
     *
     * This function retrieves all records from the proctoring logs table
     * for a given course based on the provided course ID.
     *
     * @param int $courseid The ID of the course for which to search proctoring logs.
     * @return array An array of proctoring log records for the specified course.
     */
    public function searchbycourseid($courseid) {
        global $DB;
        // Define the conditions for the query.
        $conditions = ['courseid' => $courseid];
        // Fetch the recordset based on the conditions.
        $recordset = $DB->get_recordset('quizaccess_proctoring_logs', $conditions);
        return $recordset;
    }

    /**
     * Searches for records in the quiz proctoring logs by quiz ID.
     *
     * This function retrieves all records from the `quizaccess_proctoring_logs` table
     * where the quiz ID matches the provided parameter.
     *
     * @param int $quizid The ID of the quiz to search for.
     * @return array A array containing the matching logs.
     */
    public function searchbyquizid($quizid) {
        global $DB;
        // Define the conditions for the query.
        $conditions = ['quizid' => $quizid];
        // Fetch the recordset based on the conditions.
        $recordset = $DB->get_recordset('quizaccess_proctoring_logs', $conditions);
        return $recordset;
    }

    /**
     * Retrieve all proctoring log data with student, course, and quiz details.
     *
     * This function retrieves detailed information from the proctoring logs table,
     * along with associated user (student), course, and quiz details.
     * It returns a list of proctoring logs with various attributes such as student name,
     * course name, quiz name, status, and webcam picture.
     *
     * @return array An array of objects containing the proctoring log data,
     *               including student, course, and quiz details.
     */
    public function getalldata() {
        global $DB;
        $sql = "SELECT
        e.id as reportid,
        e.userid as studentid,
        e.webcampicture as webcampicture,
        e.status as status,
        e.quizid as quizid,
        e.courseid as courseid,
        e.timemodified as timemodified,
        u.firstname as firstname,
        u.lastname as lastname,
        u.email as email,
        c.fullname as coursename,
        q.name as quizname
        from  {quizaccess_proctoring_logs} e
        INNER JOIN {user} u  ON u.id = e.userid
        INNER JOIN {course} c  ON c.id = e.courseid
        INNER JOIN {course_modules} cm  ON cm.id = e.quizid
        INNER JOIN {quiz} q  ON q.id = cm.instance";

        // Prepare data.
        return $DB->get_recordset_sql($sql);
    }

    /**
     * Deletes proctoring logs and associated files.
     *
     * This function removes proctoring log entries specified by the provided `deleteidstring`.
     * It also deletes associated warning records from the `quizaccess_proctoring_fm_warnings` table
     * and removes corresponding webcam pictures from the Moodle file storage.
     *
     * @param string $deleteidstring A comma-separated list of log IDs to delete.
     * @return void
     */
    public function deletelogs($deleteidstring) {
        global $DB;
        $deleteids = explode(",", $deleteidstring);
        if (count($deleteids) > 0) {
            $logs = $DB->get_records_list('quizaccess_proctoring_logs', 'id', $deleteids);
            foreach ($logs as $row) {
                $id = $row->id;
                $fileurl = $row->webcampicture;
                $patharray = explode("/", $fileurl);
                $filename = end($patharray);

                $DB->delete_records('quizaccess_proctoring_fm_warnings', ['reportid' => $id]);
                $DB->delete_records('quizaccess_proctoring_logs', ['id' => $id]);

                $select = "component = :component AND filearea = :filearea AND filename = :filename";
                $params = [
                    'component' => 'quizaccess_proctoring',
                    'filearea' => 'picture',
                    'filename' => $filename,
                ];
                $usersfiles = $DB->get_records_select('files', $select, $params);

                foreach ($usersfiles as $row) {
                    $this->deletefile($row);
                }
            }
        }
    }

    /**
     * Delete a file from the file storage.
     *
     * This function deletes the specified file from Moodle's file storage system.
     * The file is identified by the provided file row information, which contains
     * details about the file's location and context in the system.
     *
     * @param object $filerow The file row object containing details about the file to delete.
     * @return void
     */
    public function deletefile($filerow) {
        $fs = get_file_storage();
        $fileinfo = [
                        'component' => 'quizaccess_proctoring',
                        'filearea' => 'picture',     // Usually = table name.
                        'itemid' => $filerow->itemid,               // Usually = ID of row in table.
                        'contextid' => $filerow->contextid, // ID of context.
                        'filepath' => '/',           // Any path beginning and ending in /.
                        'filename' => $filerow->filename,
                    ]; // Any filename.

        // Get file.
        $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
        $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

        // Delete it if it exists.
        $file->delete();
    }
}
