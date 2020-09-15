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
 * Strings for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23 <moodle@brainstation-23.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

$string['proctoringrequired_help'] = 'If you enable this option, students will not be able to start an attempt until they have ticked a check-box confirming that they are aware of the policy on webcam.';
$string['proctoringrequiredoption'] = 'must be acknowledged before starting an attempt';
$string['notrequired'] = 'not required';
$string['privacy:metadata'] = 'We do not share any personal data with third parties.';
$string['proctoringheader'] = 'To continue with this attempt you must validate your identity using your webcam.';
$string['proctoringlabel'] = 'I agree with the validation process.';
$string['proctoringwbcamrequired'] = 'Students must validate their identity with the webcam';
$string['proctoringstatement'] = 'This exam requires webcam validation process. You must allow the webcam and it will be compared with your picture <br />(Please allow your web browser to access your camera).';
$string['camhtml'] = '<div class="camera"> <video id="video">Video stream not available.</video></div> <canvas id="canvas" style="display:none;"> </canvas> <img style="display:none;" id="photo" alt="The screen capture will appear in this box."/>';
$string['pluginname'] = 'Moodle Proctoring';
$string['youmustagree'] = 'You must agree to validate your identity before continue.';
$string['errorvalidation'] = 'Your identity was not validated and you can not continue';
$string['step1snap'] = 'Snap';
$string['step2validate'] = 'Validate';
$string['identityvalidated'] = 'SUCCESS: Your identity was validated';
$string['proctoringrequired'] = 'Webcam identity validation';
$string['reportlink'] = 'Webcam identity validation reports';
$string['notpermissionreport'] = 'Identity validation reports are disabled for you.';
$string['eprotroringreports'] = 'Identity validation report for: ';
$string['eprotroringreportsdesc'] = 'In this report you will find all the validation attempts of the students and their status. Also, you will be able to check both pictures used by students to validate their identity, like their profile picture and webcam photo.';
$string['status'] = 'Validation status';
$string['dateverified'] = 'Date and time';
$string['actions'] = 'Actions';
$string['statusyes'] = 'Is the same person';
$string['statusno'] = 'Is not the same person';
$string['picturesreport'] = 'View complete report';
$string['webcamphoto'] = 'Webcam photo capture';
$string['webcampicture'] = 'webcampicture';
$string['quizaccess_proctoring'] = 'Moodle Proctoring';
$string['picturesusedreport'] = 'There are the pictures used by the student to validate his/her identity';
$string['setting:proctoringreconfigureproctoring'] = 'Auto-configure Proctoring';
$string['setting:proctoringreconfigureproctoring_desc'] = 'If enabled, users who navigate to the quiz take webcam pictures';
$string['setting:autoreconfigureproctoring_desc'] = 'If enabled, users who navigate to the quiz take webcam pictures';
$string['setting:autoreconfigureproctoring'] = 'Auto-configure Proctoring';
$string['event:takescreenshot'] = 'Taken a screenshot';
$string['event:screenshotcreated'] = 'A new screenshot was created';
$string['event:screenshotupdated'] = 'Screenshot was updated';
