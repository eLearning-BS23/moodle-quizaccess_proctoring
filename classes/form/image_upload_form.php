<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Image upload form for the quizaccess_proctoring plugin.
 *
 * This class defines the form structure for uploading images in the quiz proctoring access rule.
 *
 * @package   quizaccess_proctoring
 * @copyright 2024 Brain Station 23
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace quizaccess_proctoring\form;

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/formslib.php");
/**
 * Form class for uploading user images in quizaccess_proctoring.
 *
 * @package   quizaccess_proctoring
 * @copyright 2024 Brain Station 23
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class image_upload_form extends \moodleform {
    /**
     * Defines the form elements for image upload.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form; // Moodle form instance.

        // Section header.
        $mform->addElement('header', 'username', get_string('username', 'quizaccess_proctoring'));

        // Hidden user ID field.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        // Hidden face image data field.
        $mform->addElement('hidden', 'face_image');
        $mform->setType('face_image', PARAM_RAW);

        // Hidden context ID field.
        $mform->addElement('hidden', 'context_id');
        $mform->setType('context_id', PARAM_INT);

        // File upload manager for image selection.
        $mform->addElement(
            'filemanager',
            'user_photo',
            get_string('image', 'quizaccess_proctoring'),
            null,
            [
                'subdirs' => 0,
                'maxfiles' => 1,
                'accepted_types' => ['png', 'jpg', 'jpeg'],
            ]
        );
        // Add hidden sesskey field.
        $mform->addElement('hidden', 'sesskey', sesskey());
        $mform->setType('sesskey', PARAM_RAW);

        // Add validation rule for required image upload.
        $mform->addRule('user_photo', get_string('provide_image', 'quizaccess_proctoring'), 'required');

        // Add submit and cancel buttons.
        $this->add_action_buttons();
    }

    /**
     * Custom validation for the image upload form.
     *
     * @param array $data Submitted form data.
     * @param array $files Uploaded files.
     * @return array Validation errors (empty if none).
     */
    public function validation($data, $files) {
        return [];
    }
}
