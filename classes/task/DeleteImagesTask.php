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

            // Define batch size for deletions
            $batchsize = 10;

            // Query to fetch files
            $filesql = 'SELECT f.id, f.filename, f.contextid, f.itemid 
                        FROM {files} f
                        WHERE f.component = :component 
                        AND f.filearea = :filearea
                        ORDER BY RAND()
                        LIMIT 10';

            $params = ['component' => 'quizaccess_proctoring', 'filearea' => 'picture'];
            $files = $DB->get_records_sql($filesql, $params);

            if ($files) {
                $fs = get_file_storage();

                foreach ($files as $file) {
                    $fileinfo = [
                        'component' => 'quizaccess_proctoring',
                        'filearea' => 'picture',
                        'itemid' => $file->itemid,
                        'contextid' => $file->contextid,
                        'filepath' => '/',
                        'filename' => $file->filename,
                    ];

                    // Attempt to delete the file
                    $storedfile = $fs->get_file(
                        $fileinfo['contextid'],
                        $fileinfo['component'],
                        $fileinfo['filearea'],
                        $fileinfo['itemid'],
                        $fileinfo['filepath'],
                        $fileinfo['filename']
                    );

                    if ($storedfile) {
                        $storedfile->delete();
                        mtrace("Deleted image: " . $file->filename);
                    } else {
                        mtrace("File not found: " . $file->filename);
                    }
                }

                mtrace("Batch of " . count($files) . " images deleted.");
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
