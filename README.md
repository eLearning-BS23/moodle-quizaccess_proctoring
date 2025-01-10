# Moodle Proctoring

The Moodle Proctoring plugin is a Quiz Access plugin designed to capture a user's picture via webcam to identify the individual attempting the Moodle quiz. It automatically captures images from the user's webcam at 30-second intervals (or any configurable time gap) and stores them as PNG files. Admins can analyze these images after the exam for verification purposes using the BS Face Matching API.

This plugin enables the capture of random images via webcam while the student or user is attempting a quiz.


## Features
- Automatically captures user/student images via a webcam during a quiz.
- Prevents quiz access if the user does not allow camera permissions.
- Admins can view reports to identify suspicious activities.
- Works seamlessly with the existing Question Bank and Quiz modules.
- Stores images in Moodledata as compact PNG files.
- Provides options to delete images individually or in bulk.
- Includes proctoring log reports with advanced search capabilities.
- Allows admins to upload base images for user face recognition.
- Supports face validation before quiz attempts.
- Configurable image resolution and capture interval.
- Face Recognition service(BS). [This feature validates the user image with a profile image. You can use Brain Station Face Recognition service. Please contact us(elearning@brainstation-23.com) if you want to obtain API Key for Brain Station face recognition service]
  
## Installation

### Install by downloading the ZIP file
- Install by downloading the ZIP file from Moodle plugins directory
- Download zip file from GitHub
- Unzip the zip file in /path/to/moodle/mod/quiz/accessrule/proctoring folder or upload the zip file in the install plugins options from site administration : Site Administration -> Plugins -> Install Plugins -> Upload zip file
- In your Moodle site (as admin), Visit site administration to finish the installation.


### Install using git clone

Go to Moodle Project `root/mod/quiz/accessrule/` directory and clone code by using following commands:

```
git clone https://github.com/eLearning-BS23/moodle-quizaccess_proctoring.git proctoring
```

### Install from Moodle Plugin directory

You can install this plugin directly from [Moodle plugins directory](https://moodle.org/plugins/quizaccess_proctoring). 

## Configuration

After installing the plugin, you can enable the plugin by configuring the quiz settings: 
- Go to your quiz setting (Edit Quiz): 
- Change the ‘Extra restrictions on attempts’ to ‘Enable webcam capture by Proctoring’

<img width="622" alt="1  Proctoring allow" src="https://github.com/user-attachments/assets/253deb17-5046-4946-9fb5-ece4f98e633e">


## Settings

To update the plugin settings, navigate to plugin settings: 
 `Site Administration->Plugins->Proctoring`
- Go to Site Administrations plugins section. 
- Select Proctoring from the activity module section to configure your plugin settings

### Upload User Images
Use the `Click here to upload the images` option to add user images for verification by matching faces.

### Delete All Tracking Records
Use the `Click here to delete all records` option to remove all tracking records, including images captured during exams.

<img width="960" alt="Upload user image & delete record settings" src="https://github.com/user-attachments/assets/03c8bbe4-6494-4d94-8ecf-2200654086e1">

Admins can upload all user images from the following table:
(Note: Admins cannot upload entries that do not contain any images.)

<img width="960" alt="Users list" src="https://github.com/user-attachments/assets/9cd9bbbb-8e4a-47d0-96c8-b2e83008d304">

## Additional Settings

### Camshot Interval
Admins can adjust the camshot interval and camshot resolution from here.
<image width="960" alt="camshot interval and resolutions" src="https://github.com/user-attachments/assets/5aed7aa4-457a-43e0-9034-bf5fa169fae9">


### Select Face Match Method

Select one of the face match method (BS) from the following settings: 

<img width="960" alt="Face match method settings" src="https://github.com/user-attachments/assets/734558fb-1f1c-4a3f-81bd-ce68605ab802">


### BS Service API Settings

When using BS facematch, the BS service API, BS API Key has to be entered.

<img width="960" alt="BS Service API Settings" src="https://github.com/user-attachments/assets/d83e527f-a37f-4f36-a779-ecdbf32fb08a">

If you need the BS service API, API key for trial, please contact here: `elearning@brainstation-23.com`.


### Validate Face on Quiz Start

You can enable face validation before attempting the quiz. Users will not be able to attempt the quiz if the face doesn’t match with the image uploaded by admin. 
<img width="960" alt="Face validation settings" src="https://github.com/user-attachments/assets/5e80eaed-bb92-4510-84e1-45b0d2dd1abd">

This Modal will pop up before attempting the quiz if face validation is disabled.
<img width="622" alt="Scheduler task" src="https://github.com/user-attachments/assets/ae6ac75e-001a-4eb3-8d71-0e51c8b1dc8d">

If Face validation is enabled then this modal will pop up before attempting the quiz.
<img width="622" alt="Face validation modal" src="https://github.com/user-attachments/assets/09b68f3f-d70a-44bf-82b2-080560df73cc">

### Face match Scheduler Task
Images of attempted quizzes can be analyzed by an automatic scheduled task. This can be enabled from the following settings.
<img width="960" alt="Scheduler task" src="https://github.com/user-attachments/assets/a6adee74-ec7a-4d8b-b760-ec677868ae90">

## Attempting the quiz
During attempting the quiz, the quiz page will look like this:
<img width="960" alt="3  Quiz" src="https://github.com/user-attachments/assets/d5cacca1-14e5-46fd-95a0-70723b1560cb">

## Proctoring Report

Admins can view the proctoring report:
<img width="960" alt="5  Proctoring summary" src="https://github.com/user-attachments/assets/bae1b68c-f845-4d54-b135-af78a926895f">

Admins can view individual proctoring reports and analyze the images using BS Face Matching API:
<img width="960" alt="6  Proctoring individual report" src="https://github.com/user-attachments/assets/0e8b6519-338f-4fc5-b963-0ea3d89c6935">

## Proctoring Summary
Admin can view the details record of a course. They can delete a specific quiz record or the entire course record.
<img width="960" alt="6  Proctoring individual report" src="https://github.com/user-attachments/assets/382f857b-200f-421f-b409-195f2c3cd730">

## Browser compatibility of proctoring plugin
Proctoring plugin uses the getUserMedia() API. So, the browser compatibility will be similar to the browser compatibility of getUserMedia() API.
<img width="960"  src="https://user-images.githubusercontent.com/72008371/195811733-c7776700-4fd3-410f-b82b-bfb94ba08618.png">




## FAQ’s:

1. How can I upload a user image? 
  
   >  From the settings of the proctoring plugin, there is an option for uploading user images.

2. Why does the analyze image button give a red mark for all the images? 
   
    > Check whether the credentials for the face match methods are correct and the user’s image is uploaded by the admin. 
3. Can the students upload their own images? 
    
    > No, only admins can access. 

4. Where can I get the BS Service API credentials? 
    
    > Please contact here: elearning@brainstation-23.com for a trial key.

5. Is the screenshot feature available? 
    
    > No, it is removed because of browser limitation

6. How can I report an issue regarding this plugin? 
    
    > Please raise an issue in this link: https://github.com/eLearning-BS23/moodle-quizaccess_proctoring/issues
7. Why is my moodle stuck while validating the face?
    
    > Please check whether the credentials for the face match methods are correct
8.  Why can’t I upload some of the user images? 
    
    > Every user image needs to have a face that can be detect. Please make sure the image is bright enough and there is no multiple face in that image. Otherwise, it can’t be uploaded.
9. As a student, why can’t I validate my face before starting a quiz? 
    > Student’s image must be uploaded by an admin in the moodle to validate their face before an attempt. 
10. What does the yellow mark around the image mean? 
    

    > Case 1: Please check whether the user image is uploaded in moodle. 
     
    > Case 2: Images captured with previous version of proctoring plugin can’t be analyzed by the current version of proctoring plugin because it lacks some meta data.


## License

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.
