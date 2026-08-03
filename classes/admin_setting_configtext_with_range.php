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
 * Admin setting for a text field with numeric range validation.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



/**
 * A text admin setting that validates the value falls within a given numeric range.
 *
 * This is used for settings like webcam delay, image width, and face-match threshold
 * to prevent administrators from saving meaningless values (e.g. negative seconds,
 * negative pixel widths, or percentages above 100).
 */
class quizaccess_proctoring_admin_setting_configtext_with_range extends admin_setting_configtext {
    /** @var int|float|null Minimum allowed value, or null for no lower bound. */
    protected $minvalue;

    /** @var int|float|null Maximum allowed value, or null for no upper bound. */
    protected $maxvalue;

    /**
     * Constructor.
     *
     * @param string $name           Unique setting name.
     * @param string $visiblename    Localised label shown on the settings page.
     * @param string $description    Localised description / help text.
     * @param mixed  $defaultsetting Default value.
     * @param int    $paramtype      PARAM_* constant used by Moodle for cleaning.
     * @param int|float|null $minvalue Minimum allowed value (inclusive), or null.
     * @param int|float|null $maxvalue Maximum allowed value (inclusive), or null.
     */
    public function __construct(
        $name,
        $visiblename,
        $description,
        $defaultsetting,
        $paramtype = PARAM_INT,
        $minvalue = null,
        $maxvalue = null
    ) {
        parent::__construct($name, $visiblename, $description, $defaultsetting, $paramtype);
        $this->minvalue = $minvalue;
        $this->maxvalue = $maxvalue;
    }

    /**
     * Validate the setting value before it is saved.
     *
     * @param string $data The value entered by the administrator.
     * @return true|string True if valid, or an error message string if invalid.
     */
    public function validate($data) {
        // Run the parent validation first (PARAM_INT cleaning, etc.).
        $result = parent::validate($data);
        if ($result !== true) {
            return $result;
        }

        // Allow empty values — they fall back to the default.
        if ($data === '' || $data === null) {
            return true;
        }

        $value = (float) $data;

        if ($this->minvalue !== null && $value < $this->minvalue) {
            return get_string('setting:validation_min', 'quizaccess_proctoring', $this->minvalue);
        }

        if ($this->maxvalue !== null && $value > $this->maxvalue) {
            return get_string('setting:validation_max', 'quizaccess_proctoring', $this->maxvalue);
        }

        return true;
    }
}
