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
 * Capability tool settings form.
 *
 * Do no include this file, it is automatically loaded by the class loader!
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir.'/formslib.php');
require_login();

/**
 * Class tool_capability_settings_form
 *
 * The settings form for the comparison of roles/capabilities.
 *
 * @copyright  2013 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_proctoring_settings_form extends moodleform {

    /**
     * The form definition.
     */
    public function definition() {
        $form = $this->_form;
        $imagewidth = $this->_customdata['imagewidth'];
        $delay = $this->_customdata['delay'];
        // Set the form ID.

        $form->addElement('text', 'imagewidth',
            get_string('setting:camshotwidth', 'quizaccess_proctoring')); // Add elements to your form.
        $form->setType('imagewidth', PARAM_NOTAGS);                   // Set type of element.
        $form->setDefault('imagewidth', $imagewidth);

        $form->addElement('text', 'delay',
            get_string('setting:camshotdelay', 'quizaccess_proctoring')); // Add elements to your form.
        $form->setType('delay', PARAM_NOTAGS);                   // Set type of element.
        $form->setDefault('delay', $delay);

        $buttonarray = array();
        $attributes1 = ["id" => "savebtn"];
        $attributes2 = ["id" => "deletebtn"];
        $buttonarray[] = $form->createElement('submit', 'submitvalue',
            get_string('settingscontroll:save', 'quizaccess_proctoring'), $attributes1);
        $buttonarray[] = $form->createElement('submit', 'submitvalue',
            get_string('settingscontroll:deleteall', 'quizaccess_proctoring'), $attributes2);
        $buttonarray[] = $form->createElement('cancel');
        $form->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }

}
