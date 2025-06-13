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
 * Implementaton for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This file must be included within the Moodle framework.
defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/classes/link_generator.php');

// Check if the Moodle version is 4.2 or higher, which introduced updates to the access rule base class.
if (class_exists('\mod_quiz\local\access_rule_base')) {
    // Use class aliases for compatibility with Moodle 4.2 or higher.
    class_alias('\mod_quiz\local\access_rule_base', '\quizaccess_proctoring_parent_class_alias');
    class_alias('\mod_quiz\form\preflight_check_form', '\quizaccess_proctoring_preflight_form_alias');
} else {
    // Include the legacy access rule base class for older Moodle versions.
    require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');
    class_alias('\quiz_access_rule_base', '\quizaccess_proctoring_parent_class_alias');
    class_alias('\mod_quiz_preflight_check_form', '\quizaccess_proctoring_preflight_form_alias');
}

/**
 * Quiz access proctoring class.
 *
 * Extends the parent class to implement custom proctoring behavior.
 */
class quizaccess_proctoring extends quizaccess_proctoring_parent_class_alias {
    /**
     * Determines whether a preflight check is required for the given attempt.
     *
     * @param int $attemptid The ID of the attempt being checked.
     * @return bool True if a preflight check is required, false otherwise.
     */
    public function is_preflight_check_required($attemptid) {
        $script = $this->get_topmost_script();
        $base = basename($script);

        return ($base === 'view.php');
    }

    /**
     * Get the file path of the topmost script in the call stack.
     *
     * @return string The file path of the topmost script.
     * @throws coding_exception If an error occurs while retrieving the script.
     */
    public function get_topmost_script() {
        $backtrace = debug_backtrace(
            defined('DEBUG_BACKTRACE_IGNORE_ARGS') ? DEBUG_BACKTRACE_IGNORE_ARGS : false
        );
        $topframe = array_pop($backtrace);

        return $topframe['file'];
    }

    /**
     * Retrieve course ID, quiz ID, and course module ID from the preflight form.
     *
     * @param quizaccess_proctoring_preflight_form_alias $quizform The preflight form instance.
     * @return array An associative array containing 'courseid', 'quizid', and 'cmid'.
     * @throws coding_exception If an error occurs during processing.
     */
    public function get_courseid_cmid_from_preflight_form(quizaccess_proctoring_preflight_form_alias $quizform) {
        return [
            'courseid' => $this->quiz->course,
            'quizid' => $this->quiz->id,
            'cmid' => $this->quiz->cmid,
        ];
    }


    /**
     * Generate the modal content for the webcam proctoring interface.
     *
     * @param mixed $quizform The quiz form instance.
     * @param mixed $faceidcheck A flag indicating whether face ID check is required.
     * @return string The rendered HTML content for the modal.
     * @throws coding_exception If an error occurs during rendering.
     */
    public function make_modal_content($quizform, $faceidcheck) {
        global $OUTPUT;

        // Prepare data for Mustache template rendering.
        $data = [
            'header' => get_string('openwebcam', 'quizaccess_proctoring'),
            'proctoringstatement' => get_string(
                'proctoringstatement',
                'quizaccess_proctoring'
            ),
            'videonotavailable' => get_string('videonotavailable', 'quizaccess_proctoring'),
            'photoalt' => get_string('photoalttext', 'quizaccess_proctoring'),
        ];

        // Render the content using Mustache template.
        return $OUTPUT->render_from_template('quizaccess_proctoring/cam_modal_content', $data);
    }

    /**
     * Adds preflight check form fields.
     *
     * @param quizaccess_proctoring_preflight_form_alias $quizform The preflight form instance.
     * @param MoodleQuickForm $mform The Moodle form object.
     * @param int $attemptid The quiz attempt ID.
     * @throws coding_exception If an error occurs during processing.
     */
    public function add_preflight_check_form_fields(
        quizaccess_proctoring_preflight_form_alias $quizform,
        MoodleQuickForm $mform,
        $attemptid
    ) {
        global $PAGE, $DB, $USER, $CFG;

        // Retrieve course and module data.
        $coursedata = $this->get_courseid_cmid_from_preflight_form($quizform);

        // Fetch camera shot delay configuration.
        $delaydata = $DB->get_record('config_plugins', [
            'plugin' => 'quizaccess_proctoring',
            'name' => 'autoreconfigurecamshotdelay',
        ]);
        $camshotdelay = !empty($delaydata) ? ((int)$delaydata->value * 1000) : 30000; // Default to 30 seconds if not configured.

        // Fetch face ID check setting.
        $faceidrow = $DB->get_record('config_plugins', [
            'plugin' => 'quizaccess_proctoring',
            'name' => 'fcheckstartchk',
        ]);
        $faceidcheck = $faceidrow->value ?? 0;

        // Fetch image width configuration.
        $imagewidth = get_config('quizaccess_proctoring', 'autoreconfigureimagewidth') ?? '';

        // Prepare data for the JavaScript module.
        $examurl = new moodle_url('/mod/quiz/startattempt.php');
        $record = [
            'id' => 0,
            'courseid' => (int)$coursedata['courseid'],
            'cmid' => (int)$coursedata['cmid'],
            'attemptid' => $attemptid,
            'imagewidth' => $imagewidth,
            'screenshotinterval' => $camshotdelay,
            'examurl' => $examurl->out(false),
        ];

        // Include Face API JS library if required.
        $fcmethod = get_config('quizaccess_proctoring', 'fcmethod');
        $modelurl = null;
        if ($fcmethod === 'BS') {
            $modelurl = $CFG->wwwroot . '/mod/quiz/accessrule/proctoring/thirdpartylibs/models';
            $PAGE->requires->js('/mod/quiz/accessrule/proctoring/amd/build/face-api.min.js', true);
        }
        $PAGE->requires->js_call_amd('quizaccess_proctoring/startAttempt', 'setup', [$record, $modelurl]);

        // Add HTML wrapper for the form.
        $mform->addElement('html', "<div class='quiz-check-form'>");

        // Prepare user profile image URL.
        $profileimageurl = $USER->picture
            ? (new moodle_url("/user/pix.php/{$USER->id}/f1.jpg"))->out(false)
            : '';

        // Render modal content.
        $modalcontent = $this->make_modal_content($quizform, $faceidcheck);
        // Add modal content and action buttons to the form.
        $mform->addElement('html', $modalcontent);

        // Hidden form inputs.
        $hiddenvalue = sprintf(
            '<input type="hidden" id="courseidval" value="%d"/>
            <input type="hidden" id="cmidval" value="%d"/>
            <input type="hidden" id="profileimage" value="%s"/>',
            $coursedata['courseid'],
            $coursedata['cmid'],
            $profileimageurl
        );

        // Prepare action buttons if face validation is enabled.
        $actionbtns = '';
        if ($faceidcheck === '1') {
            $facevalidationlabel = get_string('modal:facevalidation', 'quizaccess_proctoring');
            $pending = get_string('modal:pending', 'quizaccess_proctoring');
            $validateface = get_string('modal:validateface', 'quizaccess_proctoring');
            $actionbtns = sprintf(
                "%s&nbsp;<span id='face_validation_result'>%s</span>
                <button id='fcvalidate' class='btn btn-primary mt-3' style='display: flex;
                                            justify-content: center; align-items: center;'>
                    <div class='proctoring-loadingspinner' id='loading_spinner'></div>%s
                </button>",
                $facevalidationlabel,
                $pending,
                $validateface
            );
        }

        if (!empty($actionbtns)) {
            $mform->addElement('html', "<div class='container'><div class='row'><div class='col'>{$actionbtns}</div></div></div>");
        }

        // Add hidden inputs and proctoring checkbox.
        $mform->addElement('html', $hiddenvalue);
        if ($faceidcheck === '1') {
            $mform->addElement('html', '<div id="form_activate" style="visibility: hidden">');
        }
        $mform->addElement('checkbox', 'proctoring', '', get_string('proctoringlabel', 'quizaccess_proctoring'));
        if ($faceidcheck === '1') {
            $mform->addElement('html', '</div>');
        }

        // Close the form wrapper.
        $mform->addElement('html', '</div>');

        // Add a validation rule for the proctoring checkbox.
        $mform->addRule('proctoring', get_string('youmustagree', 'quizaccess_proctoring'), 'required', null, 'client');
    }

    /**
     * Validate the preflight check.
     *
     * @param array $data Form data submitted by the user.
     * @param array $files Files uploaded during the form submission.
     * @param array $errors Array to hold validation errors.
     * @param int $attemptid The quiz attempt ID.
     * @return array Updated errors array.
     */
    public function validate_preflight_check($data, $files, $errors, $attemptid) {
        // Extend validation from the parent class.
        if (method_exists(get_parent_class($this), 'validate_preflight_check')) {
            $errors = parent::validate_preflight_check($data, $files, $errors, $attemptid);
        }

        // Ensure the proctoring checkbox is checked.
        if (empty($data['proctoring'])) {
            $errors['proctoring'] = get_string('youmustagree', 'quizaccess_proctoring');
        }

        return $errors;
    }

    /**
     * Determine if the access rule should be applied to the quiz.
     *
     * @param quiz $quizobj Quiz object.
     * @param int $timenow Current timestamp.
     * @param bool $canignoretimelimits Flag to check if time limits can be ignored.
     * @return quiz_access_rule_base|null Returns an instance of the rule or null.
     */
    public static function make($quizobj, $timenow, $canignoretimelimits) {
        // Check if proctoring is required for the quiz.
        if (empty($quizobj->get_quiz()->proctoringrequired)) {
            return null;
        }

        return new self($quizobj, $timenow);
    }

    /**
     * Add the proctoring required setting to the quiz settings form.
     *
     * @param mod_quiz_mod_form $quizform The quiz settings form object.
     * @param MoodleQuickForm $mform The Moodle form wrapper.
     */
    public static function add_settings_form_fields($quizform, MoodleQuickForm $mform) {
        // Add the "Proctoring Required" dropdown.
        $mform->addElement(
            'select',
            'proctoringrequired',
            get_string('proctoringrequired', 'quizaccess_proctoring'),
            [
                0 => get_string('notrequired', 'quizaccess_proctoring'),
                1 => get_string('proctoringrequiredoption', 'quizaccess_proctoring'),
            ]
        );

        // Add a help button for the proctoring setting.
        $mform->addHelpButton('proctoringrequired', 'proctoringrequired', 'quizaccess_proctoring');
    }

    /**
     * Save any submitted settings when the quiz settings form is submitted.
     * Called from quiz_after_add_or_update() in lib.php.
     *
     * @param object $quiz Data from the quiz form, including $quiz->id for the quiz being saved.
     * @throws dml_exception
     */
    public static function save_settings($quiz) {
        global $DB;

        // Check if proctoring is required for the quiz.
        if (empty($quiz->proctoringrequired)) {
            // Remove any existing proctoring settings if not required.
            $DB->delete_records('quizaccess_proctoring', ['quizid' => $quiz->id]);
        } else {
            // Add new proctoring setting if it doesn't exist.
            if (!$DB->record_exists('quizaccess_proctoring', ['quizid' => $quiz->id])) {
                $record = (object)[
                    'quizid' => $quiz->id,
                    'proctoringrequired' => 1,
                ];
                $DB->insert_record('quizaccess_proctoring', $record);
            }
        }
    }

    /**
     * Delete any rule-specific settings when the quiz is deleted.
     * Called from quiz_delete_instance() in lib.php.
     *
     * @param object $quiz Data from the database, including $quiz->id for the quiz being deleted.
     * @throws dml_exception
     */
    public static function delete_settings($quiz) {
        global $DB;

        // Remove all proctoring settings related to the quiz.
        $DB->delete_records('quizaccess_proctoring', ['quizid' => $quiz->id]);
    }

    /**
     * Return SQL needed to load settings from all access plugins in one query.
     * This optimizes performance for loading quiz settings.
     *
     * @param int $quizid The ID of the quiz for which settings are being loaded.
     * @return array Contains fields, joins, and params for the SQL query.
     */
    public static function get_settings_sql($quizid) {
        return [
            'proctoringrequired', // Field to select.
            'LEFT JOIN {quizaccess_proctoring} proctoring ON proctoring.quizid = quiz.id', // Join clause.
            [], // No additional parameters.
        ];
    }

    /**
     * Provide information about the restriction to display on the quiz view page.
     *
     * @return array Messages explaining the restriction.
     * @throws coding_exception
     */
    public function description() {
        global $PAGE;

        // Localized strings for user messages.
        $record = (object)[
            'allowcamerawarning' => get_string('warning:cameraallowwarning', 'quizaccess_proctoring'),
            'cameraallow' => get_string('info:cameraallow', 'quizaccess_proctoring'),
        ];

        // Initialize JS for proctoring with the required data.
        $PAGE->requires->js_call_amd('quizaccess_proctoring/proctoring', 'init', [$record]);

        // Messages for the quiz view page.
        $messages = [
            get_string('proctoringheader', 'quizaccess_proctoring'),
            $this->get_download_config_button(),
        ];

        return $messages;
    }

    /**
     * Sets up the attempt (review or summary) page with any special extra
     * properties required by this rule.
     *
     * @param moodle_page $page The page object to initialise.
     *
     * @throws coding_exception
     * @throws dml_exception
     */
    public function setup_attempt_page($page) {
        global $CFG, $DB, $COURSE, $USER;

        // Fetch parameters.
        $cmid = optional_param('cmid', 0, PARAM_INT);
        $attempt = optional_param('attempt', 0, PARAM_INT);
        // Set page properties.
        $page->set_title($this->quizobj->get_course()->shortname . ': ' . $page->title);
        $page->set_popup_notification_allowed(false);
        $page->set_heading($page->title);

        if ($cmid) {
            // Fetch the course module record for the quiz.
            $contextquiz = $DB->get_record('course_modules', ['id' => $cmid]);

            if (!$contextquiz) {
                throw new coding_exception('Invalid course module ID.');
            }

            // Insert a new log entry for the attempt.
            $record = (object)[
                'courseid' => $COURSE->id,
                'quizid' => $contextquiz->id,
                'userid' => $USER->id,
                'webcampicture' => '',
                'status' => $attempt,
                'timemodified' => time(),
            ];
            $record->id = $DB->insert_record('quizaccess_proctoring_logs', $record);

            // Retrieve screenshot delay and image width settings.
            $camshotdelay = (int)get_config('quizaccess_proctoring', 'autoreconfigurecamshotdelay') * 1000 ?: 30000;
            $imagewidth = (int)get_config('quizaccess_proctoring', 'autoreconfigureimagewidth') ?: 230;

            // Add additional data to the record.
            $quizurl = new moodle_url('/mod/quiz/view.php', ['id' => $cmid]);
            $record->camshotdelay = $camshotdelay;
            $record->image_width = $imagewidth;
            $record->quizurl = $quizurl->out();

            // Configure face model URL and include JS.
            $fcmethod = get_config('quizaccess_proctoring', 'fcmethod');
            $modelurl = ($fcmethod === "BS")
                ? $CFG->wwwroot . '/mod/quiz/accessrule/proctoring/thirdpartylibs/models'
                : null;

            if ($modelurl) {
                $page->requires->js('/mod/quiz/accessrule/proctoring/amd/build/face-api.min.js', true);
            }

            // Initialise the proctoring setup with JavaScript.
            $page->requires->js_call_amd('quizaccess_proctoring/proctoring', 'setup', [$record, $modelurl]);
        }
    }

    /**
     * Get a button to view the Proctoring report.
     *
     * @return string A link to view the report, or an empty string if the user lacks capability.
     *
     * @throws coding_exception
     */
    private function get_download_config_button(): string {
        global $OUTPUT, $USER;

        // Get the context for the module.
        $context = context_module::instance($this->quiz->cmid, MUST_EXIST);

        // Check if the user has the required capability to view the report.
        if (has_capability('quizaccess/proctoring:viewreport', $context, $USER->id)) {
            // Generate the link for the proctoring report.
            $httplink = \quizaccess_proctoring\link_generator::get_link(
                $this->quiz->course,
                $this->quiz->cmid,
                false,
                is_https()
            );

            // Return a single button linking to the report.
            return $OUTPUT->single_button($httplink, get_string('picturesreport', 'quizaccess_proctoring'), 'get');
        }

        // Return an empty string if the user lacks the required capability.
        return '';
    }
}
