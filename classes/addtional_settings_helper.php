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

class addtional_settings_helper {
    /**
     * Search for specific user proctoring log.
     *
     * @param string $username The username of a user.
     * @param string $email The email of the user.
     * @param string $coursename The coursename.
     * @param string $quizname The quizname for the specific course.
     * @return array
     */
    public function search(
        $username,
        $email,
        $coursename,
        $quizname
    ) {
        global $DB;
        $params = array();
        $whereclausearray1 = array();
        $whereclausearray2 = array();

        if ($username !== "") {
            $namesplit = explode(" ", $username);
            if (count($namesplit) > 1) {
                $namelike1 = "(".$DB->sql_like('u.firstname', ':firstnamelike', false).")";
                $namelike2 = "(".$DB->sql_like('u.lastname', ':lastnamelike', false).")";
                array_push($whereclausearray1, $namelike1);
                array_push($whereclausearray2, $namelike2);

                $params['firstnamelike'] = $namesplit[0];
                $params['lastnamelike'] = $namesplit[1];
            } else {
                $namelike1 = "(".$DB->sql_like('u.firstname', ':firstnamelike', false).")";
                $namelike2 = "(".$DB->sql_like('u.lastname', ':lastnamelike', false).")";
                array_push($whereclausearray1, $namelike1);
                array_push($whereclausearray2, $namelike2);

                $params['firstnamelike'] = $username;
                $params['lastnamelike'] = $username;
            }
        }

        if ($email !== "") {
            if ($username !== "") {
                $emaillike1 = " ( ".$DB->sql_like('u.email', ':emaillike1', false)." ) ";
                $emaillike2 = " ( ".$DB->sql_like('u.email', ':emaillike2', false)." ) ";
                array_push($whereclausearray1, $emaillike1);
                array_push($whereclausearray2, $emaillike2);
                $params['emaillike1'] = $email;
                $params['emaillike2'] = $email;
            } else {
                $emaillike1 = " ( ".$DB->sql_like('u.email', ':emaillike1', false)." ) ";
                array_push($whereclausearray1, $emaillike1);
                $params['emaillike1'] = $email;
            }
        }

        if ($coursename !== "") {
            if ($username !== "") {
                $coursenamelike1 = " ( ".$DB->sql_like('c.fullname', ':coursenamelike1', false)." ) ";
                $coursenamelike2 = " ( ".$DB->sql_like('c.fullname', ':coursenamelike2', false)." ) ";
                array_push($whereclausearray1, $coursenamelike1);
                array_push($whereclausearray2, $coursenamelike2);
                $params['coursenamelike1'] = $coursename;
                $params['coursenamelike2'] = $coursename;
            } else {
                $coursenamelike1 = " ( ".$DB->sql_like('c.fullname', ':coursenamelike1', false)." ) ";
                array_push($whereclausearray1, $coursenamelike1);
                $params['coursenamelike1'] = $coursename;
            }
        }

        if ($quizname !== "") {
            if ($username !== "") {
                $quiznamelike1 = " ( ".$DB->sql_like('q.name', ':quiznamelike1', false)." ) ";
                $quiznamelike2 = " ( ".$DB->sql_like('q.name', ':quiznamelike2', false)." ) ";
                array_push($whereclausearray1, $quiznamelike1);
                array_push($whereclausearray2, $quiznamelike2);
                $params['quiznamelike1'] = $quizname;
                $params['quiznamelike2'] = $quizname;
            } else {
                $quiznamelike1 = " ( ".$DB->sql_like('q.name', ':quiznamelike1', false)." ) ";
                array_push($whereclausearray1, $quiznamelike1);
                $params['quiznamelike1'] = $quizname;
            }
        }

        $totalclausecount = count($whereclausearray1) + count($whereclausearray2);
        $secondclausecount = count($whereclausearray2);

        if ($totalclausecount > 0) {
            if ($secondclausecount > 0) {
                $andjoin1 = implode(" AND ", $whereclausearray1);
                $andjoin2 = implode( " AND ", $whereclausearray2);
                $whereclause = " (".$andjoin1.") OR (".$andjoin2.") ";
            } else {
                $andjoin1 = implode(" AND ", $whereclausearray1);
                $whereclause = " (".$andjoin1.")";
            }
        } else {
            $sqlexecuted = array();
            return $sqlexecuted;
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

        $sqlexecuted = $DB->get_recordset_sql($sql, $params);
        return $sqlexecuted;
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
        $sqlexecuted = $DB->get_recordset_sql($sql, $params);
        return $sqlexecuted;
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
        $sqlexecuted = $DB->get_recordset_sql($sql, $params);
        return $sqlexecuted;
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
        $sqlexecuted = $DB->get_recordset_sql($sql);
        return $sqlexecuted;
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
        if ($file) {
            $file->delete();
        }
    }

    /**
     * search by course id.
     *
     * @param int $courseid The id of the course.
     * @return array
     */
    public function searchssbycourseid ($courseid) {
        global $DB;
        $sql = "SELECT *
            from  {proctoring_screenshot_logs} e
            WHERE e.courseid = :courseid";
        $params = array();
        $params['courseid'] = $courseid;
        $sqlexecuted = $DB->get_recordset_sql($sql, $params);
        return $sqlexecuted;
    }

    /**
     * search by quiz id.
     *
     * @param int $quizid The id of the quiz.
     * @return array
     */
    public function searchssbyquizid ($quizid) {
        global $DB;
        $sql = "SELECT *
            from  {proctoring_screenshot_logs} e
            WHERE e.quizid = :quizid";
        $params = array();
        $params['quizid'] = $quizid;
        $sqlexecuted = $DB->get_recordset_sql($sql, $params);
        return $sqlexecuted;
    }


    /**
     * Delete logs
     *
     * @param string $deleteidstring The id of the quiz.
     * @return void
     */
    public function deletesslogs ($deleteidstring) {
        global $DB;
        $deleteids = explode(",", $deleteidstring);
        if (count($deleteids) > 0) {
            // Get report rows.
            list($insql, $inparams) = $DB->get_in_or_equal($deleteids);
            $sql = "SELECT * FROM {proctoring_screenshot_logs} WHERE id $insql";
            $logs = $DB->get_records_sql($sql, $inparams);
            foreach ($logs as $row) {
                $id = $row->id;
                $fileurl = $row->screenshot;
                $patharray = explode("/", $fileurl);
                $filename = end($patharray);

                $DB->delete_records('proctoring_screenshot_logs', array('id' => $id));
                $filesql = 'SELECT * FROM {files}
                        WHERE
                        component = "quizaccess_proctoring"
                        AND filearea = "picture"
                        AND filename = :filename';
                $params = array();
                $params["filename"] = $filename;
                $usersfiles = $DB->get_records_sql($filesql, $params);
                foreach ($usersfiles as $row) {
                    $this->deletefile($row);
                }
            }
        }
    }

}
