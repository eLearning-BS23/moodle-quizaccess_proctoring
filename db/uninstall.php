<?php
/**
 * Code that is executed before the tables and data are dropped during the plugin uninstallation.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function xmldb_quizaccessrule_proctoring_uninstall() {
    global $DB;

    // Clean up database records
    $DB->delete_records('quizaccess_proctoring');
    $DB->delete_records('quizaccess_proctoring_logs');
    $DB->delete_records('quizaccess_proctoring_facematch_task');
    $DB->delete_records('quizaccess_proctoring_fm_warnings');
    $DB->delete_records('quizaccess_proctoring_user_images');
    $DB->delete_records('quizaccess_proctoring_face_images');
    $DB->delete_records('config_plugins', ['plugin' => 'quizaccessrule_proctoring']);

    $pluginDir = __DIR__;
    if (is_writable($pluginDir)) {
        if (remove_directory($pluginDir)) {
            error_log('Plugin directory removed successfully.');
        } else {
            error_log('Failed to remove plugin directory.');
        }
    } else {
        error_log('Directory not writable: ' . $pluginDir);
    }

    return true;
}

/**
 * Helper function to recursively remove a directory and its contents.
 */
function remove_directory($dir) {
    if (!is_dir($dir)) {
        error_log('Not a directory: ' . $dir);
        return false;
    }

    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            if (!remove_directory($path)) {
                return false;
            }
        } else {
            if (!unlink($path)) {
                error_log('Failed to remove file: ' . $path);
                return false;
            }
        }
    }

    return rmdir($dir);
}
