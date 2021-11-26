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
 * Bulk Delete for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/lib/tablelib.php');
require_once(__DIR__ . '/classes/addtional_settings_helper.php');

$cmid = required_param('cmid', PARAM_INT);
$type = required_param('type', PARAM_TEXT);
$id = required_param('id', PARAM_INT);
$context = context_module::instance($cmid, MUST_EXIST);
require_capability('quizaccess/proctoring:deletecamshots', $context);

$params = array('cmid' => $cmid, 'type' => $type, 'id' => $id);
$url = new moodle_url(
'/mod/quiz/accessrule/proctoring/bulkdelete.php',
$params
);

list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');

require_login($course, true, $cm);

$PAGE->set_url($url);
$PAGE->set_title('Proctoring:Bulk Delete');
$PAGE->set_heading('Proctoring Bulk Delete');

$PAGE->navbar->add('Proctoring: Bulk Delete', $url);
$helper = new addtional_settings_helper();
echo $OUTPUT->header();

if ($type == 'course') {
    $camshotdata = $helper->searchbycourseid($id);
    $screenshotdata = $helper->searchssbycourseid($id);

} else if ($type == 'quiz') {
    $camshotdata = $helper->searchbyquizid($id);
    $screenshotdata = $helper->searchssbyquizid($id);
} else {
    echo "invalid type";
}
$rowids = array();
$ssrowids = array();
foreach ($camshotdata as $row) {
    array_push($rowids, $row->id);
}

foreach ($screenshotdata as $row) {
    array_push($ssrowids, $row->id);
}

$rowidstring = implode(',', $rowids);
$ssrowidstring = implode(',', $ssrowids);
$helper->deletelogs($rowidstring);
$helper->deletesslogs($ssrowidstring);


$params = array(
    'cmid' => $cmid
);
$url = new moodle_url(
    '/mod/quiz/accessrule/proctoring/proctoringsummary.php',
    $params
);
redirect($url, get_string('settings:deleteallsuccess', 'quizaccess_proctoring'), -11, 'success');
