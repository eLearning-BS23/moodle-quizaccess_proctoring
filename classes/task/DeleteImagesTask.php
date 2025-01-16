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

class DeleteImagesTask extends scheduled_task {
    public function get_name() {
        return get_string('task:delete_images', 'quizaccess_proctoring');
    }

    public function execute() {
        global $DB, $CFG;

        try {
            // Check if the deletion process has been initiated
            $deletion_in_progress = $DB->get_field('config_plugins', 'value', [
                'plugin' => 'quizaccess_proctoring',
                'name' => 'deletion_in_progress'
            ]);

            if (!$deletion_in_progress) {
                mtrace('No deletion process initiated.');
                return;
            }

            // Select 10 random rows where deletionprogress = 1
            $records = $DB->get_records_sql(
                "SELECT id 
                FROM {quizaccess_proctoring_logs} 
                WHERE deletionprogress = :progress 
                ORDER BY RAND() 
                LIMIT 10", 
                ['progress' => 1]
            );

            if (!empty($records)) {
                $fs = get_file_storage(); // Moodle's file storage API

                foreach ($records as $record) {
                    $fileurl = $record->webcampicture;

                    if (!empty($fileurl)) {
                        // Extract the relative path from the file URL
                        $fileinfo = parse_url($fileurl, PHP_URL_PATH); // Example: /pluginfeedback/pluginfile.php/16/quizaccess_proctoring/picture/14/webcam-14-2-2-173701055926.png
                        $fileparts = explode('/', trim($fileinfo, '/'));

                        // Ensure the path is valid before attempting deletion
                        if (count($fileparts) >= 6 && $fileparts[2] === 'quizaccess_proctoring' && $fileparts[3] === 'picture') {
                            $contextid = $fileparts[1];
                            $itemid = $fileparts[4];
                            $filename = $fileparts[5];

                            // File record details
                            $filedata = [
                                'component' => 'quizaccess_proctoring',
                                'filearea' => 'picture',
                                'contextid' => $contextid,
                                'itemid' => $itemid,
                                'filepath' => '/',
                                'filename' => $filename,
                            ];

                            // Attempt to delete the file
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
                                mtrace("Deleted file: " . $filename);
                            } else {
                                mtrace("File not found: " . $filename);
                            }
                        } else {
                            mtrace("Invalid file path: " . $fileurl);
                        }
                    }
                }

                // Collect the IDs of the records to delete
                $ids = array_keys($records);

                // Delete associated entries in mdl_quizaccess_proctoring_face_images
                list($insql, $params) = $DB->get_in_or_equal($ids);
                $DB->delete_records_select('quizaccess_proctoring_face_images', "parentid $insql", $params);
                mtrace("Deleted associated records from mdl_quizaccess_proctoring_face_images.");

                // Delete the database records from quizaccess_proctoring_logs
                $DB->delete_records_select('quizaccess_proctoring_logs', "id $insql", $params);
                mtrace("Deleted " . count($ids) . " records from quizaccess_proctoring_logs and associated files.");
            } else {
                // No more images to delete, stop the deletion process
                mtrace("No more images to delete. Deletion process completed.");
                $DB->set_field('config_plugins', 'value', 0, [
                    'plugin' => 'quizaccess_proctoring',
                    'name' => 'deletion_in_progress'
                ]);
            }
        } catch (Exception $e) {
            mtrace("An error occurred: " . $e->getMessage());
        }
    }
}
