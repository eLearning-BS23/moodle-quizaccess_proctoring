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
 * Events for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23 <moodle@brainstation-23.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_proctoring\event;

use core\event\base;
use quizaccess_proctoring\screenshot;
use context_system;

defined('MOODLE_INTERNAL') || die();


class screenshot_updated extends base
{

    public static function create_strict(screenshot $screenshot, context_system $context) : base {
        global $USER, $COURSE;
        $tid = $screenshot->get('id');

        return self::create([
            'courseid' => $COURSE->id,
            'userid' => $USER->id,
            'objectid' => $tid,
            'context' => $context,
        ]);
    }

    protected function init()
    {
        $this->data['objecttable'] = 'quizaccess_proctoring_logs';
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }

    public static function get_name()
    {
        return get_string('event:screenshotupdated', 'quizaccess_proctoring');
    }

    public function get_description()
    {
        return array('db' => 'quizaccess_proctoring_logs', 'restore' => 'quizaccess_proctoring_logs');
    }

    public function get_url()
    {
        return new \moodle_url('/mod/quiz/review.php', array('attempt' => $this->objectid));
    }

    public static function get_objectid_mapping() : array {
        return array('db' => 'quizaccess_proctoring_logs', 'restore' => 'quizaccess_proctoring_logs');
    }

    public static function get_other_mapping()
    {
        return [];
    }
}
