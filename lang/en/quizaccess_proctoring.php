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
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

$string['proctoringrequired_help'] = 'If you enable this option, students will not be able to start an attempt until they have ticked a check-box confirming that they are aware of the policy on webcam.';
$string['proctoringrequiredoption'] = 'must be acknowledged before starting an attempt';
$string['notrequired'] = 'not required';
$string['privacy:metadata'] = 'We do not share any personal data with third parties.';
$string['proctoringheader'] = '<strong>To continue with this quiz attempt you must open your webcam, and it will take some of your pictures randomly during the quiz.</strong>';
$string['proctoringlabel'] = 'I agree with the validation process.';
$string['proctoringstatement'] = 'This exam requires screen share and webcam access.<br/>(Please allow your web browser to access your camera).';
$string['camhtml'] = '<span class="camera"> <video width="100" id="video">Video stream not available.</video></span> <canvas id="canvas" style="display:none;"> </canvas> <img style="display:none;" id="photo" alt="The screen capture will appear in this box."/>';
$string['screensharemsg'] = '';
$string['screenhtml'] = '<span><video style="display: none" width="100" id="video-screen" autoplay></video></span><canvas id="canvas-screen" style="display:none;"></canvas><img id="photo-screen" alt="The picture will appear in this box." style="display:none;"/><span class="output-screen" style="display:none;"></span><span id="log-screen" style="display:none;"></span>';
$string['proctoringstatement'] = 'This exam requires screenshare and webcam access.<br />(Please share screen & allow webcam access).';
$string['camhtml'] = '<div class="camera"> <video width="100" id="video">Video stream not available.</video></div> <canvas id="canvas" style="display:none;"> </canvas> <img style="display:none;" id="photo" alt="The screen capture will appear in this box."/>';
$string['pluginname'] = 'Proctoring';
$string['quizaccess_proctoring'] = 'Quizaccess Proctoring';
$string['youmustagree'] = 'You must agree to validate your identity before continue.';
$string['proctoringrequired'] = 'Webcam identity validation';
$string['notpermissionreport'] = 'Proctoring reports are disabled for you.';
$string['eprotroringreports'] = 'Proctoring report for: ';
$string['eprotroringreportsdesc'] = 'In this report you will find all the images of the students which are taken during the exam. Now you can validate their identity, like their profile picture and webcam photos.';
$string['summarypagedesc'] = 'In this report you will find the summary of proctoring report for course and quizzes. You can delete all the data related to quiz and course. It will delete image file as well as logs.';
$string['status'] = 'Validation status';
$string['dateverified'] = 'Date and time';
$string['warninglabel'] = 'Warnings';
$string['actions'] = 'Actions';
$string['picturesreport'] = 'View proctoring report';
$string['webcampicture'] = 'webcampicture';
$string['screenshots'] = 'Screenshots';
$string['picturesusedreport'] = 'There are the pictures captured during the quiz.';
$string['setting:proctoringreconfigureproctoring'] = 'Auto-configure Proctoring';
$string['setting:proctoringreconfigureproctoring_desc'] = 'If enabled, users who navigate to the quiz take webcam pictures';


$string['event:takescreenshot'] = 'Taken a screenshot';
$string['event:screenshotcreated'] = 'A new screenshot was created';
$string['event:screenshotupdated'] = 'Screenshot was updated';


$string['privacy:metadata:courseid'] = 'The ID of the course that use proctoring.';
$string['privacy:metadata:quizid'] = 'The ID of the Quiz that use proctoring.';
$string['privacy:metadata:webcampicture'] = 'The name of picture that has been taken by the proctoring.';
$string['privacy:metadata:status'] = 'The Status of the proctoring.';
$string['timemodified'] = 'Last modified';
$string['privacy:metadata:quizaccess_proctoring_logs'] = 'Moodle Quiz access Proctoring logs table that store user\'s picture.';

$string['proctoring:sendcamshot'] = 'Proctoring send webcam photo';
$string['proctoring:getcamshots'] = 'Proctoring get webcam photos';
$string['proctoring:viewreport'] = 'Proctoring view report';
$string['name'] = 'Student Name';
$string['webcampicture'] = 'Captured Pictures';
$string['openwebcam'] = 'Allow your webcam & share screen to continue';
$string['privacy:quizaccess_proctoring_logs'] = 'QuizAccess Proctoring logs';
$string['privacy:core_files'] = 'QuizAccess Proctoring webcam pictures';
$string['privacy:metadata:core_files'] = 'The Quiz Access stores users picture which has been shot by the webcam during quiz attempt.';

$string['setting:camshotdelay'] = 'The delay between camshots in seconds.';
$string['setting:camshotdelay_desc'] = 'Given value will be the delay in seconds between each camshot';

$string['setting:externalpage'] = 'External Page';
$string['setting:camshotwidth'] = 'The width of the camshot image in pixel.';
$string['setting:camshotwidth_desc'] = 'Given value will be the width of the camshot. The image height will be scaled to that';

$string['setting:fc_method'] = 'Facematch Method(BS/AWS)';
$string['setting:fc_methoddesc'] = 'Service to match faces.(Values: AWS/BS).';
$string['setting:bs_api'] = 'BS Service API';
$string['setting:bs_apidesc'] = 'BS service api endpoint.';
$string['setting:bs_apitoken'] = 'BS Token';
$string['setting:bs_apitokendesc'] = 'API token for BS facematch service.';
$string['setting:aws_key'] = 'AWS key';
$string['setting:aws_keydesc'] = 'AWS Key for face rocognition service.';
$string['setting:aws_secret'] = 'AWS secret';
$string['setting:aws_secretdesc'] = 'AWS secret for face rognition service.';
$string['setting:facematch'] = 'Number of facematch per quiz';
$string['setting:facematchdesc'] = 'Number of facematch per quiz. (-1 for check all)';
$string['setting:fcthreshold'] = 'Face match threshold percentage.';
$string['setting:fcthresholddesc'] = 'Face match threshold percentage.';

$string['buttonlabel:deletebutton'] = 'Delete images';
$string['warning:camera allow warning'] = 'Please allow camera access.';
$string['warning:cameraallowwarning'] = 'Please allow camera access.';

$string['settingscontroll:save'] = 'Save Settings';
$string['settingscontroll:deleteall'] = 'Purge All Proctoring Data';
$string['settingserror:imagewidth'] = 'The width of the camshot image can only be integer';
$string['settingserror:imagedelay'] = 'The delay between camshots can only be integer';
$string['settingserror:formcancelled'] = 'You cancelled formsubmit.';
$string['settings:updatesuccess'] = 'Successfully updated protoring settings.';
$string['settings:deleteallsuccess'] = 'Successfully deleted all images.';
$string['settings:deleteallformlabel'] = 'Clicking the Delete All Images button will delete all images of all quizes.';
$string['settings:deleteallconfirm'] = 'Do you really want to DELETE ALL IMAGES of ALL QUIZES.';
$string['settings:fcheckquizstart'] = 'Face ID validate on quiz start';
$string['settings:fcheckquizstart_desc'] = 'Face ID validate on quiz start[If "yes" user has to validate face id to start quiz].';
$string['settings:screenshareenable'] = 'Enable screenshare';
$string['settings:screenshareenable_desc'] = 'Enable screenshare [If "yes" user screenshot will be sent with webcam picture].';


$string['settings:enablescreenshot'] = 'Enable screenshot for quizes.';
$string['settings:enablescreenshot_desc'] = 'Enable screenshot for quizes.';

$string['reportidheader'] = 'Log ID';
$string['coursenameheader'] = 'Course Name';
$string['quiznameheader'] = 'Quiz Name';
$string['mainsettingspagebtn'] = 'Proctoring Settings';
$string['additionalsettingspagetitle'] = 'All proctoring logs';

$string['execute_facematch_task'] = 'Execute facematch task';
$string['initiate_facematch_task'] = 'Initiate facematch task';

$string['modal:sharescreenstate'] = 'Share Screen State:';
$string['modal:displaysurface'] = 'Display Surface:';
$string['modal:facevalidation'] = 'Face Validated:';
$string['modal:sharescreenbtn'] = 'share screen';
$string['modal:disabled'] = 'Disabled';
$string['modal:pending'] = 'Pending';
$string['modal:validateface'] = 'Validate Face Recognition';
