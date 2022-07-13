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
 * Additional Settings Helper for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class AdditionalSettingsHelper {
    const Q_NAME = 'q.name';
    const AND1 = " AND ";

    /**
     * Search for specific user proctoring log.
     *
     * @param string $username The username of a user.
     * @param string $email The email of the user.
     * @param string $coursename The coursename.
     * @param string $quizname The quizname for the specific course.
     * @return array
     */
    public function search(string $username, string $email, string $coursename, string $quizname) {
        global $DB;
        $params = array();
        $whereclausearray1 = array();
        $whereclausearray2 = array();
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
            $andjoin1 = implode(self::AND1, $whereclausearray1);
            if ($secondclausecount > 0) {
                $andjoin2 = implode( self::AND1, $whereclausearray2);
                $whereclause = " (".$andjoin1.") OR (".$andjoin2.") ";
            } else {
                $whereclause = " (".$andjoin1.")";
            }
        } else {

            return array();
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
    /** Make query string from params
     *
     * @param $username
     * @return array
     *
     */
    public function usernamequerypart ($username) {
        global $DB;
        $params = array();
        $whereclausearray1 = array();
        $whereclausearray2 = array();

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
        $queryparts = array();
        $queryparts["params"] = $params;
        $queryparts["whereclausearray1"] = $whereclausearray1;
        $queryparts["whereclausearray2"] = $whereclausearray2;
        return $queryparts;
    }

    /** Make query string from params
     *
     * @param $email
     * @return array
     *
     */
    public function emailquerypart ($email, $username) {
        global $DB;
        $params = array();
        $whereclausearray1 = array();
        $whereclausearray2 = array();

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

        $queryparts = array();
        $queryparts["params"] = $params;
        $queryparts["whereclausearray1"] = $whereclausearray1;
        $queryparts["whereclausearray2"] = $whereclausearray2;

        return $queryparts;
    }

    /** Make query string from params
     *
     * @param $username
     * @return array
     *
     */
    public function coursenamequerypart ($coursename, $username) {
        global $DB;
        $params = array();
        $whereclausearray1 = array();
        $whereclausearray2 = array();

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

        $queryparts = array();
        $queryparts["params"] = $params;
        $queryparts["whereclausearray1"] = $whereclausearray1;
        $queryparts["whereclausearray2"] = $whereclausearray2;

        return $queryparts;
    }

    /** Make query string from params
     *
     * @param $username
     * @return array
     *
     */
    public function quiznamequerypart ($quizname, $username) {
        global $DB;
        $params = array();
        $whereclausearray1 = array();
        $whereclausearray2 = array();

        if ($quizname !== "") {
            $quiznamelike1 = " ( ".$DB->sql_like(self::Q_NAME, ':quiznamelike1', false)." ) ";
            if ($username !== "") {
                $quiznamelike2 = " ( ".$DB->sql_like(self::Q_NAME, ':quiznamelike2', false)." ) ";
                $whereclausearray1[] = $quiznamelike1;
                $whereclausearray2[] = $quiznamelike2;
                $params['quiznamelike1'] = $quizname;
                $params['quiznamelike2'] = $quizname;
            } else {
                $whereclausearray1[] = $quiznamelike1;
                $params['quiznamelike1'] = $quizname;
            }
        }

        $queryparts = array();
        $queryparts["params"] = $params;
        $queryparts["whereclausearray1"] = $whereclausearray1;
        $queryparts["whereclausearray2"] = $whereclausearray2;

        return $queryparts;
    }
    /**
     * search by course id.
     *
     * @param int $courseid The id of the course.
     * @return array
     */
    public function searchbycourseid ($courseid) {
        global $DB;
        $sql = "SELECT *
            from  {quizaccess_proctoring_logs} e
            WHERE e.courseid = :courseid";
        $params = array();
        $params['courseid'] = $courseid;
        return $DB->get_recordset_sql($sql, $params);
    }

    /**
     * search by quiz id.
     *
     * @param int $quizid The id of the quiz.
     * @return array
     */
    public function searchbyquizid ($quizid) {
        global $DB;
        $sql = "SELECT *
            from  {quizaccess_proctoring_logs} e
            WHERE e.quizid = :quizid";
        $params = array();
        $params['quizid'] = $quizid;
        return $DB->get_recordset_sql($sql, $params);
    }

    /**
     * Get all data.
     *
     *
     * @return array
     */
    public function getalldata () {
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
     * Delete logs
     *
     * @param string $deleteidstring The id of the quiz.
     * @return void
     */
    public function deletelogs ($deleteidstring) {
        global $DB;
        $deleteids = explode(",", $deleteidstring);
        if (count($deleteids) > 0) {
            // Get report rows.
            list($insql, $inparams) = $DB->get_in_or_equal($deleteids);
            $sql = "SELECT * FROM {quizaccess_proctoring_logs} WHERE id $insql";
            $logs = $DB->get_records_sql($sql, $inparams);
            foreach ($logs as $row) {
                $id = $row->id;
                $fileurl = $row->webcampicture;
                $patharray = explode("/", $fileurl);
                $filename = end($patharray);

                $DB->delete_records('proctoring_fm_warnings', array('reportid' => $id));
                $DB->delete_records('quizaccess_proctoring_logs', array('id' => $id));

                $filesql = "SELECT * FROM {files}
                        WHERE
                        component = 'quizaccess_proctoring'
                        AND filearea = 'picture'
                        AND filename = :filename";
                $params = array();
                $params["filename"] = $filename;
                $usersfiles = $DB->get_records_sql($filesql, $params);
                foreach ($usersfiles as $row) {
                    $this->deletefile($row);
                }
            }
        }
    }

    /**
     * Delete file.
     *
     * @param string $filerow The id of the quiz.
     * @return void
     */
    public function deletefile ($filerow) {
        $fs = get_file_storage();
        $fileinfo = array(
                        'component' => 'quizaccess_proctoring',
                        'filearea' => 'picture',     // Usually = table name.
                        'itemid' => $filerow->itemid,               // Usually = ID of row in table.
                        'contextid' => $filerow->contextid, // ID of context.
                        'filepath' => '/',           // Any path beginning and ending in /.
                        'filename' => $filerow->filename); // Any filename.

        // Get file.
        $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
        $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

        // Delete it if it exists.
        $file->delete();
    }
}
