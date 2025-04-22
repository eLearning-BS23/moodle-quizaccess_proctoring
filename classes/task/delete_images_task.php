<?php
// This file is part of Moodle - http://moodle.org/.
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

namespace quizaccess_proctoring\task;

use core\task\scheduled_task;
use Exception;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/accessrule/proctoring/lib.php');

/**
 * Scheduled task to delete all data.
 * @package    quizaccess_proctoring
 * @author     Brain station 23 ltd <brainstation-23.com>
 * @copyright  2021 Brain station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delete_images_task extends scheduled_task {
    /**
     * Returns name of task.
     *
     * @return string
     */
    public function get_name() {
        return get_string('task:delete_images', 'quizaccess_proctoring');
    }

    /**
     * Executes the task to delete proctoring logs and associated images.
     *
     * @return void
     */
    public function execute() {
        global $DB;

        try {
            // Select 10 random rows from proctoring logs where deletionprogress = 1.
            $sql = "SELECT id, webcampicture
            FROM {quizaccess_proctoring_logs}
            WHERE deletionprogress = :deletionprogress
            LIMIT 10";

            $params = ['deletionprogress' => 1];
            $records = $DB->get_records_sql($sql, $params);
            if (!empty($records)) {
                $fs = get_file_storage();
                $ids = [];
                foreach ($records as $record) {

                    $this->delete_file($fs, $record->webcampicture, 'quizaccess_proctoring', 'picture');
                    $faceparams = [
                        'parentid'    => $record->id,
                        'parent_type' => 'camshot_image',
                    ];
                    // Fetch the record using Moodle's DML API.
                    $faceimagerecord = $DB->get_record(
                        'quizaccess_proctoring_face_images',
                        $faceparams
                    );

                    if (($faceimagerecord)) {
                        $this->delete_file($fs, $faceimagerecord->faceimage, 'quizaccess_proctoring', 'face_image');
                    } else {
                         mtrace("No face image found for this picture.");
                    }

                     $DB->delete_records('quizaccess_proctoring_face_images',
                         ['parentid' => $record->id, 'parent_type' => 'camshot_image']);
                    $ids[] = $record->id;
                }
                // Delete associated face images from the database after processing all records.
                if (!empty($ids)) {
                    list($insql, $params) = $DB->get_in_or_equal($ids);

                    // Delete the log records from quizaccess_proctoring_logs.
                    $DB->delete_records_select('quizaccess_proctoring_logs', "id $insql", $params);
                    mtrace("Deleted " . count($ids) . " records from quizaccess_proctoring_logs and associated files.");
                }
            } else {
                mtrace("No records found for deletion.");
            }
        } catch (Exception $e) {
            mtrace("An error occurred while deleting images: " . $e->getMessage());
        }
    }

    /**
     * Helper function to delete a file based on its URL and file area.
     *
     * @param object $fs Moodle file storage object.
     * @param string $fileurl The file URL.
     * @param string $component The component name.
     * @param string $filearea The file area (e.g., 'picture' or 'face_image').
     * @return void
     */
    private function delete_file($fs, $fileurl, $component, $filearea) {
        if (!empty($fileurl)) {
            // Extract the relative path from the file URL.
            $fileinfo = parse_url($fileurl, PHP_URL_PATH);
            $fileparts = explode('/', trim($fileinfo, '/'));
            $fileparts = array_reverse($fileparts);
            // Validate the path before attempting deletion.
            if ($fileparts[3] === $component && $fileparts[2] === $filearea) {
                $contextid = $fileparts[4];
                $itemid = $fileparts[1];
                $filename = $fileparts[0];

                // File record details.
                $filedata = [
                    'component' => $component,
                    'filearea' => $filearea,
                    'contextid' => $contextid,
                    'itemid' => $itemid,
                    'filepath' => '/',
                    'filename' => $filename,
                ];

                // Attempt to delete the file.
                $storedfile = $fs->get_file(
                    $filedata['contextid'],
                    $filedata['component'],
                    $filedata['filearea'],
                    $filedata['itemid'],
                    $filedata['filepath'],
                    $filedata['filename']
                );

                if ($storedfile) {
                    $storedfile->delete();
                    mtrace("Deleted file: " .$filearea. " " . $fileurl);
                } else {
                    mtrace("File not found: " . $fileurl);
                }
            } else {
                mtrace("Invalid file path: " . $fileurl);
            }
        } else {
            mtrace("Found empty url.");
        }
    }
}
