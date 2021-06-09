<?php
class addtional_settings_helper {
    public function search(
        $username,
        $email,
        $coursename,
        $quizname
    ){
        global $DB;
        $params = array();
        $whereClauseArray1 = array();
        $whereClauseArray2 = array();

        if($username!==""){
            $namesplit = explode(" ",$username);
            if(count($namesplit) > 1){
                $name_like1 = "(".$DB->sql_like('u.firstname', ':firstnamelike', false).")";
                $name_like2 = "(".$DB->sql_like('u.lastname', ':lastnamelike', false).")";
                array_push($whereClauseArray1,$name_like1);
                array_push($whereClauseArray2,$name_like2);

                $params['firstnamelike'] = $namesplit[0];
                $params['lastnamelike'] = $namesplit[1];
            }
            else{
                $name_like1 = "(".$DB->sql_like('u.firstname', ':firstnamelike', false).")";
                $name_like2 = "(".$DB->sql_like('u.lastname', ':lastnamelike', false).")";
                array_push($whereClauseArray1,$name_like1);
                array_push($whereClauseArray2,$name_like2);

                $params['firstnamelike'] = $username;
                $params['lastnamelike'] = $username;
            }
        }

        if($email!==""){
            if($username!==""){
                $email_like1 = " ( ".$DB->sql_like('u.email', ':emaillike1', false)." ) ";
                $email_like2 = " ( ".$DB->sql_like('u.email', ':emaillike2', false)." ) ";
                array_push($whereClauseArray1,$email_like1);
                array_push($whereClauseArray2,$email_like2);
                $params['emaillike1'] = $email;
                $params['emaillike2'] = $email;
            }
            else{
                $email_like1 = " ( ".$DB->sql_like('u.email', ':emaillike1', false)." ) ";
                array_push($whereClauseArray1,$email_like1);
                $params['emaillike1'] = $email;
            }
        }

        if($coursename!==""){
            if($username!==""){
                $coursename_like1 = " ( ".$DB->sql_like('c.fullname', ':coursenamelike1', false)." ) ";
                $coursename_like2 = " ( ".$DB->sql_like('c.fullname', ':coursenamelike2', false)." ) ";
                array_push($whereClauseArray1,$coursename_like1);
                array_push($whereClauseArray2,$coursename_like2);
                $params['coursenamelike1'] = $coursename;
                $params['coursenamelike2'] = $coursename;
            }
            else{
                $coursename_like1 = " ( ".$DB->sql_like('c.fullname', ':coursenamelike1', false)." ) ";
                array_push($whereClauseArray1,$coursename_like1);
                $params['coursenamelike1'] = $coursename;
            }
        }

        if($quizname!==""){
            if($username!==""){
                $quizname_like1 = " ( ".$DB->sql_like('q.name', ':quiznamelike1', false)." ) ";
                $quizname_like2 = " ( ".$DB->sql_like('q.name', ':quiznamelike2', false)." ) ";
                array_push($whereClauseArray1,$quizname_like1);
                array_push($whereClauseArray2,$quizname_like2);
                $params['quiznamelike1'] = $quizname;
                $params['quiznamelike2'] = $quizname;
            }
            else{
                $quizname_like1 = " ( ".$DB->sql_like('q.name', ':quiznamelike1', false)." ) ";
                array_push($whereClauseArray1,$quizname_like1);
                $params['quiznamelike1'] = $quizname;
            }
        }

        $total_clause_count = count($whereClauseArray1) + count($whereClauseArray2);
        $second_clause_count = count($whereClauseArray2);

        if($total_clause_count>0){
            if($second_clause_count>0){
                $andJoin1 = implode(" AND ",$whereClauseArray1);
                $andJoin2 = implode( " AND ",$whereClauseArray2);
                $whereClause = " (".$andJoin1.") OR (".$andJoin2.") ";
            }
            else{
                $andJoin1 = implode(" AND ",$whereClauseArray1);
                $whereClause = " (".$andJoin1.")";
            }
        }
        else{
            $sqlexecuted = array();
            return $sqlexecuted;
        }

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
            INNER JOIN {quiz} q  ON q.id = cm.instance
            WHERE $whereClause";

        $sqlexecuted = $DB->get_recordset_sql($sql,$params);
        return $sqlexecuted;
    }

    public function searchByCourseID($courseid){
        global $DB;
        $sql = "SELECT *
            from  {quizaccess_proctoring_logs} e 
            WHERE e.courseid = :courseid";
        $params = array();
        $params['courseid'] = $courseid;
        $sqlexecuted = $DB->get_recordset_sql($sql,$params);
        return $sqlexecuted;
    }

    public function searchByQuizID($quizid){
        global $DB;
        $sql = "SELECT *
            from  {quizaccess_proctoring_logs} e 
            WHERE e.quizid = :quizid";
        $params = array();
        $params['quizid'] = $quizid;
        $sqlexecuted = $DB->get_recordset_sql($sql,$params);
        return $sqlexecuted;
    }

    public function getAllData(){
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

    public function deleteLogs($deleteidstring)
    {
        global $DB;
        $deleteids = explode(",", $deleteidstring);
        if (count($deleteids) > 0) {
            /// Get report rows
            list($insql, $inparams) = $DB->get_in_or_equal($deleteids);
            $sql = "SELECT * FROM {quizaccess_proctoring_logs} WHERE id $insql";
            $logs = $DB->get_records_sql($sql, $inparams);
            foreach ($logs as $row) {
                $id = $row->id;
                $fileurl = $row->webcampicture;
                $patharray = explode("/", $fileurl);
                $filename = end($patharray);
//                $DB->set_field('quizaccess_proctoring_logs', 'userid', 0, array('id' => $id));
                $DB->delete_records('quizaccess_proctoring_logs', array('id' => $id));
                $filesql = 'SELECT * FROM {files}
                        WHERE
                        component = "quizaccess_proctoring"
                        AND filearea = "picture"
                        AND filename = :filename';
                $params = array();
                $params["filename"] = $filename;
                $usersfiles = $DB->get_records_sql($filesql, $params);
                foreach($usersfiles as $row){
                    $this->deleteFile($row);
                }
            }
        }
    }

    public function deleteFile($filerow){
        $fs = get_file_storage();
        $fileinfo = array(
                        'component' => 'quizaccess_proctoring',
                        'filearea' => 'picture',     // Usually = table name.
                        'itemid' => $filerow->itemid,               // Usually = ID of row in table.
                        'contextid' => $filerow->contextid, // ID of context.
                        'filepath' => '/',           // any path beginning and ending in /.
                        'filename' => $filerow->filename); // any filename.

        // Get file
        $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
        $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

        // Delete it if it exists
        if ($file) {
            $file->delete();
        }
    }

}