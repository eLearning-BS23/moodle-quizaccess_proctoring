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

$string['notrequired'] = 'not required';
$string['proctoringrequired_help'] = 'If you enable this option, students will not be able to start an attempt until they have ticked a check-box confirming that they are aware of the policy on webcam.';
$string['proctoringrequiredoption'] = 'must be acknowledged before starting an attempt';
$string['privacy:metadata'] = 'We do not share any personal data with third parties.';
$string['proctoringheader'] = '<strong>To continue with this quiz attempt you must open your webcam, and it will take some of your pictures randomly during the quiz.</strong>';
$string['proctoringlabel'] = 'I agree with the validation process.';
$string['screensharemsg'] = '';
$string['screenhtml'] = '<span><video style="display: none" width="100" id="video-screen" autoplay></video></span><canvas id="canvas-screen" style="display:none;"></canvas><img id="photo-screen" alt="The picture will appear in this box." style="display:none;"/><span class="output-screen" style="display:none;"></span><span id="log-screen" style="display:none;"></span>';
$string['proctoringstatement'] = 'This exam requires webcam access.<br />(Please allow webcam access).';
$string['camhtml'] = '<div class="camera"> <video width="100" id="video">Video stream not available.</video></div> <canvas id="canvas" style="display:none;"> </canvas> <img style="display:none;" id="photo" alt="The screen capture will appear in this box."/>';
$string['pluginname'] = 'Proctoring for Moodle';
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
$string['screenshots'] = 'Screenshots';
$string['picturesusedreport'] = 'There are the pictures captured during the quiz.';
$string['buyproctoringpro'] = 'Get Proctoring Pro';

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
$string['openwebcam'] = 'Allow your webcam to continue';
$string['privacy:quizaccess_proctoring_logs'] = 'QuizAccess Proctoring logs';
$string['privacy:core_files'] = 'QuizAccess Proctoring webcam pictures';
$string['privacy:metadata:core_files'] = 'The Quiz Access stores users picture which has been shot by the webcam during quiz attempt.';

$string['setting:camshotdelay'] = 'The delay between webcam images (seconds)';
$string['setting:camshotdelay_desc'] = 'The given value will be the delay in seconds between each webcam image.';

$string['setting:externalpage'] = 'External Page';
$string['setting:camshotwidth'] = 'The width of the webcam image (pixels)';
$string['setting:camshotwidth_desc'] = 'The given value will be the width of the webcam image. The image height will be scaled to match this.';


$string['setting:fc_method'] = 'Face Match Method';
$string['setting:fc_methoddesc'] = 'Service used to match faces. Options: BS, None.';
$string['setting:bs_api'] = 'BS Service API';
$string['setting:bs_apidesc'] = 'BS service api endpoint.';
$string['setting:bs_apiusername'] = 'BS Service Username';
$string['setting:bs_apiusernamedoc'] = 'BS Service Username';
$string['setting:bs_apipassword'] = 'BS Service Password';
$string['setting:bs_apipassworddesc'] = 'API Password for BS facematch service.';
$string['setting:bs_apifacematchthreshold'] = 'Face Match Threshold (BS)';
$string['setting:bs_bs_apifacematchthresholddesc'] = 'The percentage threshold for the face verification using BS service. (Default: 68%)';
$string['setting:aws_key'] = 'AWS key';
$string['setting:aws_keydesc'] = 'AWS Key for face recognition service.';
$string['setting:aws_secret'] = 'AWS secret';
$string['setting:aws_secretdesc'] = 'AWS secret for face recognition service.';
$string['setting:facematch'] = 'Number of Face Matches Per Quiz';
$string['setting:facematchdesc'] = 'The number of face match checks to be performed during a quiz. (-1 for all)';
$string['setting:fcthreshold'] = 'Face match threshold percentage.';
$string['setting:fcthresholddesc'] = 'Face match threshold percentage.';
$string['setting:bs_api_key'] = 'BS API Key';
$string['setting:bs_api_keydesc'] = 'Enter the API key for the BS face-matching service.';


$string['buttonlabel:deletebutton'] = 'Delete images';
$string['warning:camera allow warning'] = 'Please allow camera access.';
$string['warning:cameraallowwarning'] = 'Please allow camera access.';

$string['settingscontroll:save'] = 'Save Settings';
$string['settingserror:imagewidth'] = 'The width of the camshot image can only be integer';
$string['settingserror:imagedelay'] = 'The delay between camshots can only be integer';
$string['settingserror:formcancelled'] = 'You cancelled formsubmit.';
$string['settings:updatesuccess'] = 'Successfully updated protoring settings.';
$string['settings:deleteallformlabel'] = 'Delete All Camshots.';
$string['settings:deleteallconfirm'] = 'Are you sure you want to delete all camshot images? This action cannot be undone.';
$string['settings:fcheckquizstart'] = 'Face Validation on Quiz Start';
$string['settings:fcheckquizstart_desc'] = 'If enabled, users must validate their Face before they can start the quiz.';
$string['settings:screenshareenable'] = 'Enable screenshare';
$string['settings:screenshareenable_desc'] = 'Enable screenshare [If "yes" user screenshot will be sent with webcam picture].';
$string['setting:adminimagepage'] = 'Users List for Uploading User Image';
$string['setting:userslist'] = 'Upload User Images';

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

$string['users_list'] = 'Users List';
$string['no_permission'] = 'You do not have proper permission to view this page';
$string['cancel_image_upload'] = 'Cancelled image upload';
$string['image_updated'] = 'Image updated';

$string['upload_first_image'] = 'Please upload user image.';
$string['settings:deleteuserimagesuccess'] = 'Successfully deleted user image.';
$string['proctoring_pro_promo'] = "Proctoring Pro Promo";
$string['userlist'] = "Userlist";
$string['reportpage'] = "Proctoring Summary Report";

// Feedback
$string['plugin_description'] = 'The Moodle Proctoring plugin enhances the security of online quizzes by capturing and verifying user identities through webcam images. It is designed to ensure that only authorized users can attempt the quiz, providing a secure and reliable proctoring solution.';
$string['pro_version_text'] = 'Learn more about the Pro version of this plugin here.';
$string['upload_image_info'] = 'Upload images to the system for user verification. This helps ensure the integrity of your online quizzes.';
$string['upload_image_button'] = 'Upload User Images';
$string['setting_camshotdelay'] = 'Camera snapshot delay (seconds)';
$string['setting_camshotdelay_desc'] = 'Set the delay (in seconds) between automatic camera snapshots during the quiz.';
$string['setting_camshotwidth'] = 'Camera snapshot width (pixels)';
$string['setting_camshotwidth_desc'] = 'Set the width (in pixels) for images captured by the camera.';
$string['setting_fc_method'] = 'Face capture method';
$string['setting_fc_methoddesc'] = 'Choose the method to use for face capture during proctoring.';
$string['none'] = 'None';
$string['upload_image_message'] = 'Please upload the user image.';
$string['upload_image_link_text'] = 'Click here to upload the image.';
$string['additional_settings'] = 'Additional Settings';
$string['pro_version_description'] = 'Enhance your online exams with Moodle Proctoring Pro! Catch tab-switching, monitor clipboard activity, use face recognition for real-time monitoring, and access detailed proctoring reports to ensure fair and secure assessments.';

// Delete image.
$string['areyousure_delete_image'] = 'Do you want to delete this image?';
$string['delete'] = 'Delete';
$string['cancel'] = 'Cancel';
$string['info:cameraallow'] = 'Your camera is now in use.';
$string['userimagenotuploaded'] = 'User image is not uploaded.';
$string['uploadimagehere'] = 'Click here to upload the image.';
$string['file_required'] = 'Please upload an image.';
$string['invalid_image'] = 'The uploaded file must be a valid image (JPEG or PNG).';
$string['no_face_detected'] = 'Face not found in the uploaded image. Please upload an image with a visible face.';
$string['upload_image_title'] = 'Upload Image for Face Detection';
$string['provide_image'] = 'Please provide an image to upload.';
$string['file_required'] = 'A file must be uploaded.';
$string['invalid_image'] = 'The uploaded file must be a valid image (JPEG, PNG, or JPG).';
$string['no_face_detected'] = 'No face detected in the uploaded image. Please ensure the image contains a face.';
$string['back'] = 'Back';
$string['image_upload'] = 'Upload Image';
$string['image'] = 'Image';
$string['image_not_uploaded'] = 'The uploaded image does not contain any faces.';
$string['moodle_image_upload'] = 'Moodle Image Upload';
$string['upload_image'] = 'Upload Image';
$string['upload_instructions'] = 'Please upload a clear image in PNG, JPG, or JPEG format. Max file size is 5MB.';
$string['submit'] = 'Submit';
$string['image_help'] = 'The photo must clearly contain a face for the image upload to be accepted.';
$string['action_upload_image'] = 'Action';
$string['nousersfound'] = 'No users found';
$string['settingscontroll:deleteall'] = 'Delete all user tracking records, including images captured during exams.';
$string['settingscontroll:deleteall_link_text'] = 'Click here to delete all records.';
$string['areyousure_delete_all_record'] = 'Are you sure you want to delete all tracking records, including captured images taken during exams?';
$string['settings:deleteallsuccess'] = 'Successfully deleted all records.';
$string['areyousure_delete_record'] = 'Are you sure you want to delete this record?';
