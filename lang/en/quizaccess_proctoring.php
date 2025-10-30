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
$string['accessdenied'] = 'Access Denied';
$string['action_upload_image'] = 'Action';
$string['actions'] = 'Actions';
$string['additional_settings'] = 'General settings';
$string['analyzbtn'] = 'Analyze';
$string['analyzbtnconfirm'] = 'Click the Analyze button for face match of the user.';
$string['analyzimage'] = 'Analyze images';
$string['areyousure_delete_all_course_record'] = 'Are you sure you want to delete all images and records of students that were captured during the exams for <b>this course?</b>';
$string['areyousure_delete_all_record'] = 'Are you sure you want to delete all images of students that were captured during the exams?';
$string['areyousure_delete_image'] = 'Do you want to delete this image?';
$string['areyousure_delete_record'] = 'Are you sure you want to delete this record?';
$string['back'] = 'Back';
$string['cancel_image_upload'] = 'Cancelled image upload';
$string['confirmdeletioncourse'] = 'Are you sure you want to delete this course pictures?';
$string['confirmdeletionquiz'] = 'Are you sure you want to delete the pictures of this quiz?';
$string['course_proctoring_summary'] = 'Course Report';
$string['dateverified'] = 'Date and time';
$string['delete'] = 'Delete';
$string['delete_images_task'] = 'Delete images task';
$string['delete_images_task_desc'] = 'Delete all proctoring images';
$string['deleteallcourse'] = 'Delete course images';
$string['deletequizdata'] = 'Delete quiz images';
$string['email']  = 'Email address';
$string['enable_web_camera_before_submitting'] = 'You need to enable web camera before submitting this quiz!';
$string['eprotroringreports'] = 'Proctoring report for: ';
$string['eprotroringreportsdesc'] = 'In this report you will find all the images of the students which are taken during the exam. Now you can validate their identity, like their profile picture and webcam images.';
$string['error_face_not_found'] = 'Face not found in the image. Please contact the administrator.';
$string['error_invalid_report'] = 'Invalid report data. Please try again.';
$string['examdata'] = 'No data is available for this exam session. Please check the exam setup or monitoring configurations.';
$string['execute_facematch_task'] = 'Execute face match task';
$string['facefound'] = 'Face found in the uploaded image.';
$string['facematch'] = 'Face match successful. The student identity is verified.';
$string['facematched'] = 'Face matched.';
$string['facematchs'] = 'All images have been successfully analyzed. Please review them to verify the face match.';
$string['facenotfound'] = 'Face not found in the uploaded image.';
$string['facenotfoundoncam'] = 'Face not found. Try changing your camera to a better lighting. Thanks.';
$string['facenotmatched'] = 'Face not matched.';
$string['foundtext'] = 'Found';
$string['identity_mismatch_label'] = 'Identity Mismatch';
$string['image'] = 'Upload Image';
$string['image_not_uploaded'] = 'The uploaded image does not contain any faces.';
$string['image_updated'] = 'Image updated';
$string['image_upload'] = 'Upload image';
$string['info:cameraallow'] = 'Your camera is now in use.';
$string['initiate_facematch_task'] = 'Initiate face match task';
$string['initiate_facematch_task_desc'] = 'Initiates a face match task to compare images for proctoring verification.';
$string['invalid_api'] = 'The provided BS API key is invalid.';
$string['invalid_facematch_method'] = 'Invalid face match method in settings. Please provide valid "BS" API credentials for the face match method.';
$string['invalid_service_api'] = 'The provided BS service API is invalid.';
$string['invalidapi'] = 'BS API key is invalid. Please contact to the admin.';
$string['invalidsesskey'] = 'Invalid session key. Please try again.';
$string['invalidtype'] = 'The provided type is invalid.';
$string['mainsettingspagebtn'] = 'Proctoring settings';
$string['modal:facevalidation'] = 'Face validated:';
$string['modal:pending'] = 'Pending';
$string['modal:validateface'] = 'Validate face recognition';
$string['name'] = 'Student name';
$string['no_permission'] = 'You do not have proper permission to view this page';
$string['nodata'] = 'No data found for the given criteria.';
$string['none'] = 'None';
$string['nopermission'] = 'You do not have permission to perform this action.';
$string['notenrolled'] = 'You are not enrolled in this course or do not have the required permissions.';
$string['notfoundtext'] = 'Not Found';
$string['notpermissionreport'] = 'Proctoring reports are disabled for you.';
$string['notrequired'] = 'Not required';
$string['nousersfound'] = 'No users found';
$string['numberofimages'] = 'Number of images';
$string['openwebcam'] = 'Allow your webcam to continue';
$string['photoalttext'] = 'The screen capture will appear in this box.';
$string['photonotuploaded'] = 'Photo not uploaded. Please contact to the admin.';
$string['picturesreport'] = 'View proctoring report';
$string['picturesusedreport'] = 'These are the pictures captured during the quiz.';
$string['plugin_description'] = 'The Moodle Proctoring plugin enhances the security of online quizzes by capturing and verifying user identities through webcam images. It is designed to ensure that only authorized users can attempt the quiz, providing a secure and reliable proctoring solution.';
$string['pluginname'] = 'Proctoring for Moodle';
$string['privacy:core_files'] = 'QuizAccess Proctoring webcam pictures';
$string['privacy:metadata'] = 'We do not share any personal data with third parties.';
$string['privacy:metadata:core_files'] = 'The Quiz Access stores users picture which has been shot by the webcam during quiz attempt.';
$string['privacy:metadata:courseid'] = 'The ID of the course that uses proctoring.';
$string['privacy:metadata:quizaccess_proctoring_logs'] = 'Moodle Quiz access Proctoring logs table that stores user\'s picture.';
$string['privacy:metadata:quizid'] = 'The ID of the quiz that uses proctoring.';
$string['privacy:metadata:status'] = 'The status of the proctoring.';
$string['privacy:metadata:userid'] = 'The ID of the user who took the quiz.';
$string['privacy:metadata:webcampicture'] = 'The name of the picture that has been taken by the proctoring.';
$string['pro_version_description'] = 'Enhance your online exams with Moodle Proctoring Pro! Catch tab-switching, monitor clipboard activity, use face recognition for real-time monitoring, and access detailed proctoring reports to ensure fair and secure assessments.';
$string['pro_version_text'] = 'Learn more about the Pro version of this plugin here.';
$string['pro_version_title_text'] = 'Get Proctoring Pro.';
$string['proctoring:analyzeimages'] = 'Proctoring analyze images';
$string['proctoring:deletecamshots'] = 'Delete images from proctoring logs.';
$string['proctoring:getcamshots'] = 'Proctoring get webcam images';
$string['proctoring:sendcamshot'] = 'Proctoring send webcam photo';
$string['proctoring:viewreport'] = 'Proctoring view report';
$string['proctoring_pro_promo'] = 'Proctoring Pro promo';
$string['proctoring_pro_promo:admin'] = 'Detailed admin reports';
$string['proctoring_pro_promo:adminlist1'] = 'Provides a detailed view of all participants\' proctored logs.';
$string['proctoring_pro_promo:adminlist2'] = 'Allows downloading a comprehensive PDF report.';
$string['proctoring_pro_promo:detectcopypaste'] = 'Copy-paste forgery detection';
$string['proctoring_pro_promo:detectcopypastelist1'] = 'Detects any copy and paste actions during the quiz attempt.';
$string['proctoring_pro_promo:detectcopypastelist2'] = 'Logs each attempt to copy or paste text.';
$string['proctoring_pro_promo:email'] = 'Email support';
$string['proctoring_pro_promo:emailsupport'] = 'Receive direct email support from our team.';
$string['proctoring_pro_promo:emailsupportlist1'] = 'Get 24/7 email support for any queries or issues.';
$string['proctoring_pro_promo:feature'] = 'Features of Proctoring Pro';
$string['proctoring_pro_promo:featurelist1'] = 'Compatible with face recognition service (AWS).';
$string['proctoring_pro_promo:featurelist2'] = 'Detect if webcam was enabled for entire time of attempt.';
$string['proctoring_pro_promo:featurelist3'] = 'Detect if user has moved to any other application/tab.';
$string['proctoring_pro_promo:featurelist4'] = 'Detect if user has resized the browser window.';
$string['proctoring_pro_promo:featurelist5'] = 'Detect if copy and paste occurred during the attempt.';
$string['proctoring_pro_promo:featurelist6'] = 'Detect if user has pressed F12 key.';
$string['proctoring_pro_promo:featurelist7'] = 'Detailed admin report of every event log and webcam images.';
$string['proctoring_pro_promo:featurelist8'] = 'Admin summary report of all users.';
$string['proctoring_pro_promo:featurelist9'] = 'Email support/bug fixes';
$string['proctoring_pro_promo:header'] = 'Secure your online exams with Proctoring Pro cutting-edge technology for unbeatable monitoring';
$string['proctoring_pro_promo:learnmore'] = 'Learn more';
$string['proctoring_pro_promo:mail'] = 'Contact us at';
$string['proctoring_pro_promo:namefree'] = 'Proctoring (Free)';
$string['proctoring_pro_promo:namepro'] = 'Proctoring Pro';
$string['proctoring_pro_promo:pdfgenerator'] = 'PDF report generation';
$string['proctoring_pro_promo:pdfgeneratordesc'] = 'Generates a detailed PDF report for each user, containing all logged events.';
$string['proctoring_pro_promo:profeature'] = 'What\'s new in Proctoring Pro 2.0';
$string['proctoring_pro_promo:profeaturebulkphotoupload'] = 'Bulk photo upload';
$string['proctoring_pro_promo:profeaturebulkphotouploaddesc'] = 'Allows admins to upload images for multiple users at once via a zip file or upload individual images.';
$string['proctoring_pro_promo:profeaturehphotofillter'] = 'Photo filtering';
$string['proctoring_pro_promo:profeaturehphotofillterdesc'] = 'Admins can filter users based on whether their photo is uploaded or if the user\'s face is missing from the captured images.';
$string['proctoring_pro_promo:screenmonitoring'] = 'Screen size monitoring';
$string['proctoring_pro_promo:screenmonitoringlist1'] = 'Detects any changes in screen size during the quiz attempt.';
$string['proctoring_pro_promo:screenmonitoringlist2'] = 'Logs each instance when the user resizes the quiz window.';
$string['proctoring_pro_promo:subheader'] = 'Get the Proctoring Pro plugin now.';
$string['proctoring_pro_promo:suscipiousevent'] = 'Other suspicious events';
$string['proctoring_pro_promo:suscipiouseventlist1'] = 'Detects if the F12 key is pressed during the exam.';
$string['proctoring_pro_promo:suscipiouseventlist2'] = 'Logs each instance when the user presses F12 while attempting the quiz.';
$string['proctoring_pro_promo:tabmonitoring'] = 'Focus tab monitoring';
$string['proctoring_pro_promo:tabmonitoringlist1'] = 'Detects if the user switches to another window or tab.';
$string['proctoring_pro_promo:tabmonitoringlist2'] = 'Logs every instance when the user moves away from the exam tab or window.';
$string['proctoring_pro_promo:webcam'] = 'Webcam detection';
$string['proctoring_pro_promo:webcamlist1'] = 'Detects whether the webcam remained enabled throughout the entire exam attempt.';
$string['proctoring_pro_promo:webcamlist2'] = 'Logs any instances when the webcam is disabled.';
$string['proctoring_pro_promo_heading'] = 'Proctoring Pro promo';
$string['proctoring_report'] = 'Proctoring report';
$string['proctoringheader'] = '<strong>To continue with this quiz attempt you must open your webcam, and it will take some of your pictures randomly during the quiz.</strong>';
$string['proctoringlabel'] = 'I agree with the validation process.';
$string['proctoringrequired'] = 'Webcam identity validation';
$string['proctoringrequired_help'] = 'Enabling proctoring requires students to be monitored using webcam and screen recording during the quiz attempt.';
$string['proctoringrequiredoption'] = 'Enable webcam capture by Proctoring';
$string['proctoringstatement'] = 'This exam requires webcam access.<br />(Please allow webcam access).';
$string['provide_image'] = 'Please provide an image to upload.';
$string['quizaccess_proctoring'] = 'Quizaccess Proctoring';
$string['quiztitle'] = 'Quiz Title';
$string['reportpage'] = 'Course Proctoring Summary';
$string['setting:adminimagedescription'] = 'These images will be used as base images for face verification. Please ensure each image contains a clearly visible face.';
$string['setting:adminimagepage'] = 'Proctoring User List';

$string['setting:bs_api'] = 'BS service API';
$string['setting:bs_api_key'] = 'BS API key';
$string['setting:bs_api_keydesc'] = 'Enter the API key for the BS face-matching service.';
$string['setting:bs_apidesc'] = 'BS service API endpoint.';
$string['setting:bs_apifacematchthreshold'] = 'Face match threshold (BS)';
$string['setting:bs_bs_apifacematchthresholddesc'] = 'The percentage threshold for the face verification using BS service. (Default: 68%)';
$string['setting:camshotdelay'] = 'The delay between webcam images (seconds)';
$string['setting:camshotdelay_desc'] = 'The given value will be the delay in seconds between each webcam image.';
$string['setting:camshotwidth'] = 'The width of the webcam image (pixels)';
$string['setting:camshotwidth_desc'] = 'The given value will be the width of the webcam image. The image height will be scaled to match this.';
$string['setting:facematch'] = 'Number of face matches per quiz';
$string['setting:facematchdesc'] = 'Number of face match checks. Use 0 or less to check all snapshots.';
$string['setting:fc_method'] = 'Face match method';
$string['setting:fc_methoddesc'] = 'Service used to match faces. Options: BS, None.';
$string['setting:fcthreshold'] = 'Face match threshold percentage';
$string['setting:fcthresholddesc'] = 'Face match threshold percentage';
$string['setting:uploaduserimages'] = 'Upload base image for users';
$string['setting:userslist'] = 'Upload user images';
$string['settings:deleteallsuccess'] = 'Successfully deleted all records.';
$string['settings:deleteuserimagesuccess'] = 'Successfully deleted user image.';
$string['settings:fcheckquizstart'] = 'Face validation on quiz start';
$string['settings:fcheckquizstart_desc'] = 'If enabled, users must validate their face before they can start the quiz.';

$string['settingscontroll:deleteall'] = 'Delete all record that captured during the exams';
$string['settingscontroll:deleteallcourseimage'] = 'Delete all images and records of students that were captured during the exams for <b>this course</b>.';
$string['settingscontroll:deletealldescription'] = 'This will permanently delete all captured images and proctoring related data. This action cannot be undone.';

$string['settingscontroll:deletealllinktext'] = 'Delete all records';
$string['status'] = 'Validation status';
$string['studentreport'] = 'Student report';
$string['submit'] = 'Submit';
$string['summarypagedesc'] = 'In this report you will find the summary of proctoring report for this course and its quizzes. You can delete all the data related to quiz and course. It will delete image file as well as logs.';
$string['task:delete_images'] = 'Delete images task';
$string['timemodified'] = 'Last modified';
$string['upload_first_image'] = 'Please upload user image.';
$string['upload_image'] = 'Upload image';
$string['upload_image_heading'] = 'Upload user image';
$string['upload_image_info'] = 'Upload images to the system for user verification. This helps ensure the integrity of your online quizzes.';
$string['upload_image_link_text'] = 'Click here to Upload user images.';
$string['upload_image_message'] = 'Proctoring needs user images to authenticate their identity.';
$string['upload_image_title'] = 'Upload image for face detection';
$string['uploadimagehere'] = 'Click here to upload the image.';
$string['user'] = 'Users';
$string['user_image_not_uploaded'] = 'User image is not uploaded. Please upload the image.';
$string['user_image_not_uploaded_teacher'] = 'User image is not uploaded. Please contact with administrator to upload the image.';
$string['userimagenotuploaded'] = 'User image is not uploaded.';
$string['userlist'] = 'User list';
$string['username'] = 'User Name';
$string['users_list'] = 'Proctoring for Moodle Users list';
$string['users_list_info_description'] = 'This page lists all users who require a base image for proctoring.
                                        These images will be used for face-matching during quizzes to ensure authentication and prevent impersonation.
                                        If an image is not uploaded, the user may not be properly verified during proctored exams. To get more features like customized filtering, searching, and uploading many images at once, ';
$string['videonotavailable'] = 'Video stream not available.';
$string['viewimages'] = 'View images';
$string['warning:cameraallowwarning'] = 'Please allow camera access.';
$string['warninglabel'] = 'Warnings';
$string['webcam'] = 'Webcam';
$string['webcampicture'] = 'Captured pictures';
$string['wrong_during_taking_image'] = 'Something went wrong during taking the image.';
$string['wrong_during_taking_screenshot'] = 'Something went wrong during taking screenshot.';
$string['youmustagree'] = 'You must agree to validate your identity before continuing.';
