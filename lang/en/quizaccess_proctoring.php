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

$string['action_upload_image'] = 'Action';
$string['actions'] = 'Actions';
$string['additional_settings'] = 'Additional Settings';
$string['areyousure_delete_all_record'] = 'Are you sure you want to delete all tracking records, including captured images taken during exams?';
$string['areyousure_delete_image'] = 'Do you want to delete this image?';
$string['areyousure_delete_record'] = 'Are you sure you want to delete this record?';

$string['back'] = 'Back';
$string['buyproctoringpro'] = 'Get Proctoring Pro';

$string['cancel_image_upload'] = 'Cancelled image upload';
$string['confirmdeletioncourse'] = 'Are you sure you want to delete this course pictures?';
$string['confirmdeletionquiz'] = 'Are you sure you want to delete this quiz pictures?';
$string['coursenamequizname'] = 'Course Name / Quiz Name';

$string['dateverified'] = 'Date and time';
$string['delete'] = 'Delete';
$string['deleteallcourse'] = 'Delete Course Images';
$string['deletequizdata'] = 'Delete Quiz Images';

$string['email']  = 'Email Address';
$string['eprotroringreports'] = 'Proctoring report for: ';
$string['eprotroringreportsdesc'] = 'In this report you will find all the images of the students which are taken during the exam. Now you can validate their identity, like their profile picture and webcam photos.';
$string['error_face_not_found'] = 'Face not found in the image. Please contact the administrator.';
$string['error_invalid_report'] = 'Invalid report data. Please try again.';
$string['examdata'] = 'No data is available for this exam session.Please check the exam setup or monitoring configurations.';
$string['execute_facematch_task'] = 'Execute facematch task';

$string['image'] = 'Image';
$string['image_not_uploaded'] = 'The uploaded image does not contain any faces.';
$string['image_updated'] = 'Image updated';
$string['image_upload'] = 'Upload Image';
$string['info:cameraallow'] = 'Your camera is now in use.';
$string['invalidtype'] = 'The provided type is invalid.';

$string['mainsettingspagebtn'] = 'Proctoring Settings';
$string['modal:facevalidation'] = 'Face Validated:';
$string['modal:pending'] = 'Pending';
$string['modal:validateface'] = 'Validate Face Recognition';

$string['name'] = 'Student Name';
$string['no_permission'] = 'You do not have proper permission to view this page';
$string['nodata'] = 'No data found for the given criteria.';
$string['none'] = 'None';
$string['nopermission'] = 'You do not have permission to perform this action.';
$string['notpermissionreport'] = 'Proctoring reports are disabled for you.';
$string['notrequired'] = 'not required';
$string['nousersfound'] = 'No users found';
$string['numberofimages'] = 'Number of Images';

$string['openwebcam'] = 'Allow your webcam to continue';

$string['photoalttext'] = 'The screen capture will appear in this box.';
$string['picturesreport'] = 'View proctoring report';
$string['picturesusedreport'] = 'There are the pictures captured during the quiz.';
$string['plugin_description'] = 'The Moodle Proctoring plugin enhances the security of online quizzes by capturing and verifying user identities through webcam images. It is designed to ensure that only authorized users can attempt the quiz, providing a secure and reliable proctoring solution.';
$string['pluginname'] = 'Proctoring for Moodle';
$string['privacy:core_files'] = 'QuizAccess Proctoring webcam pictures';
$string['privacy:metadata'] = 'We do not share any personal data with third parties.';
$string['privacy:metadata:core_files'] = 'The Quiz Access stores users picture which has been shot by the webcam during quiz attempt.';
$string['privacy:metadata:courseid'] = 'The ID of the course that use proctoring.';
$string['privacy:metadata:quizaccess_proctoring_logs'] = 'Moodle Quiz access Proctoring logs table that store user\'s picture.';
$string['privacy:metadata:quizid'] = 'The ID of the Quiz that use proctoring.';
$string['privacy:metadata:status'] = 'The Status of the proctoring.';
$string['privacy:metadata:webcampicture'] = 'The name of picture that has been taken by the proctoring.';
$string['pro_version_description'] = 'Enhance your online exams with Moodle Proctoring Pro! Catch tab-switching, monitor clipboard activity, use face recognition for real-time monitoring, and access detailed proctoring reports to ensure fair and secure assessments.';
$string['pro_version_text'] = 'Learn more about the Pro version of this plugin here.';
$string['proctoring:deletecamshots'] = 'Delete images from proctoring logs.';
$string['proctoring:getcamshots'] = 'Proctoring get webcam photos';
$string['proctoring:sendcamshot'] = 'Proctoring send webcam photo';
$string['proctoring:viewreport'] = 'Proctoring view report';
$string['proctoring_pro_promo'] = "Proctoring Pro Promo";
$string['proctoring_report'] = 'Proctoring Report';
$string['proctoring_summary_report'] = 'Proctoring Summary Report';
$string['proctoringheader'] = '<strong>To continue with this quiz attempt you must open your webcam, and it will take some of your pictures randomly during the quiz.</strong>';
$string['proctoringlabel'] = 'I agree with the validation process.';
$string['proctoringrequired'] = 'Webcam identity validation';
$string['proctoringrequiredoption'] = 'must be acknowledged before starting an attempt';
$string['proctoringstatement'] = 'This exam requires webcam access.<br />(Please allow webcam access).';
$string['provide_image'] = 'Please provide an image to upload.';

$string['quizaccess_proctoring'] = 'Quizaccess Proctoring';

$string['reportpage'] = "Proctoring Summary Report";

$string['setting:bs_api'] = 'BS Service API';
$string['setting:bs_api_key'] = 'BS API Key';
$string['setting:bs_api_keydesc'] = 'Enter the API key for the BS face-matching service.';
$string['setting:bs_apidesc'] = 'BS service api endpoint.';
$string['setting:bs_apifacematchthreshold'] = 'Face Match Threshold (BS)';
$string['setting:bs_bs_apifacematchthresholddesc'] = 'The percentage threshold for the face verification using BS service. (Default: 68%)';
$string['setting:camshotdelay'] = 'The delay between webcam images (seconds)';
$string['setting:camshotdelay_desc'] = 'The given value will be the delay in seconds between each webcam image.';
$string['setting:camshotwidth'] = 'The width of the webcam image (pixels)';
$string['setting:camshotwidth_desc'] = 'The given value will be the width of the webcam image. The image height will be scaled to match this.';
$string['setting:facematch'] = 'Number of Face Matches Per Quiz';
$string['setting:facematchdesc'] = 'The number of face match checks to be performed during a quiz. (-1 for all)';
$string['setting:fc_method'] = 'Face Match Method';
$string['setting:fc_methoddesc'] = 'Service used to match faces. Options: BS, None.';
$string['setting:fcthreshold'] = 'Face match threshold percentage.';
$string['setting:fcthresholddesc'] = 'Face match threshold percentage.';
$string['settings:deleteallsuccess'] = 'Successfully deleted all records.';
$string['settings:deleteuserimagesuccess'] = 'Successfully deleted user image.';
$string['settings:fcheckquizstart'] = 'Face Validation on Quiz Start';
$string['settings:fcheckquizstart_desc'] = 'If enabled, users must validate their Face before they can start the quiz.';
$string['settingscontroll:deleteall'] = 'Delete all user tracking records, including images captured during exams.';
$string['settingscontroll:deleteall_link_text'] = 'Click here to delete all records.';
$string['status'] = 'Validation status';
$string['submit'] = 'Submit';
$string['summarypagedesc'] = 'In this report you will find the summary of proctoring report for course and quizzes. You can delete all the data related to quiz and course. It will delete image file as well as logs.';

$string['timemodified'] = 'Last modified';

$string['upload_first_image'] = 'Please upload user image.';
$string['upload_image'] = 'Upload Image';
$string['upload_image_info'] = 'Upload images to the system for user verification. This helps ensure the integrity of your online quizzes.';
$string['upload_image_link_text'] = 'Click here to upload the image.';
$string['upload_image_message'] = 'Please upload the user image.';
$string['upload_image_title'] = 'Upload Image for Face Detection';
$string['uploadimagehere'] = 'Click here to upload the image.';
$string['user'] = 'Users';
$string['user_image_not_uploaded'] = 'User image is not uploaded. Please upload the image.';
$string['userimagenotuploaded'] = 'User image is not uploaded.';
$string['userlist'] = "Userlist";
$string['users_list'] = 'Users List';

$string['videonotavailable'] = 'Video stream not available.';

$string['warning:cameraallowwarning'] = 'Please allow camera access.';
$string['warninglabel'] = 'Warnings';
$string['webcampicture'] = 'Captured Pictures';

$string['youmustagree'] = 'You must agree to validate your identity before continue.';

