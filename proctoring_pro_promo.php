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
 * Report for the quizaccess_proctoring plugin.
 *
 * @copyright 2020 Brain Station 23
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(__DIR__.'/../../../../config.php');
require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');

$PAGE->set_url(new moodle_url('/mod/quiz/accessrule/proctoring/proctoring_pro_promo.php'));
$PAGE->set_pagelayout('course');
$PAGE->set_title('Proctoring Pro Promo');
// $PAGE->set_heading('Proctoring Pro Promo');

echo $OUTPUT->header();

$featuresimageurl = $OUTPUT->pix_url('proctoring_pro_features', 'quizaccess_proctoring');
$proctoringprologo = $OUTPUT->pix_url('proctoring_pro_logo', 'quizaccess_proctoring');

$template = 'quizaccess_proctoring/proctoring_pro_promo';
$context = [
    'title' => 'Proctoring Pro Promo',
    'features_image_url' => $featuresimageurl,
    'proctoring_pro_logo' => $proctoringprologo,
];

echo $OUTPUT->render_from_template($template, $context);

echo $OUTPUT->footer();