# Moodle Proctoring

Moodle proctoring plugin is a quizaccess plugin to capture a user's picture via webcam to identify who is attempting the Moodle quiz. It will capture the picture of the user's webcam automatically every 30 seconds and store it as a PNG image. Admins can analyze the image of students after exams for verification using BS Face Matching API.

This plugin will help you to capture random pictures via webcam when the student/user is attempting the Quiz. 


## Features
- Capture user/student images via web camera
- Can't access quiz if the user does not allow the camera
- Admin report and check any suspicious activity
- Will work with existing Question Bank and Quiz
- Images are stored in Moodledata as a small png image
- Image can be deleted individually or in bulks
- Proctoring log report with search facilities
- Configurable image size and capture interval
- Face Recognition service(BS). [This feature validates the user image with a profile image. You can use Brainstation Face Recognition service. Please contact us(elearning@brainstation-23.com) if you want to obtain API Key for brainstation face recognition service]

## Instatllation

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
- Change the ‘Extra restrictions on attempts’ to ‘must be acknowledged before starting an attempt’

<img width="622" alt="1  Proctoring allow" src="https://user-images.githubusercontent.com/72008371/195803225-5db50398-1fea-48f2-9c52-a80558ac4aec.PNG">


## Settings

To update the plugin settings, navigate to plugin settings: 
 `Site Administration->Plugins->Proctoring`
- Go to Site Administrations plugins section. 
- Select Proctoring from the activity module section to configure your plugin settings

### Upload User Images
Use the `Upload User Images` option to add images of users for verification by matching the face

<img width="960" alt="Upload user image settings" src="https://github.com/eLearning-BS23/moodle-quizaccess_proctoring/assets/72008371/7144f850-78cf-44fc-8dd0-bd4261ae84a1">

Admins can upload all the users images from the following table: 

<img width="960" alt="Users list" src="https://user-images.githubusercontent.com/72008371/195804321-0836c4e1-3f34-46f6-9a03-ab216c1ce485.png">

**There must be a face in the uploaded image by the admin.**

### Select Face Match Method

Select one of the face match method (BS) from the following settings: 

<img width="960" alt="Face match method settings" src="https://github.com/eLearning-BS23/moodle-quizaccess_proctoring/assets/72008371/84756465-69df-44c4-ae7e-16e53395e6cd">

### BS Service API Settings

When using BS facematch, the BS service API, BS API Key has to be entered.

<img width="960" alt="BS Service API Settings" src="https://github.com/eLearning-BS23/moodle-quizaccess_proctoring/assets/72008371/b8e007f1-c687-470b-af5b-f361d883d3bd">

If you need the BS service API, API key for trial, please contact here: `elearning@brainstation-23.com`.

## Additional Settings
### Validate Face on Quiz Start

You can enable face validation before attempting the quiz. Users will not be able to attempt the quiz if the face doesn’t match with the image uploaded by admin. 

<img width="960" alt="Face validation settings" src="https://user-images.githubusercontent.com/72008371/195809923-4c384fa0-8c5b-4366-ba62-2a650df74971.png">

Face validation modal will pop up before attempting the quiz.

<img width="622" alt="Face validation modal" src="https://github.com/eLearning-BS23/moodle-quizaccess_proctoring/assets/72008371/a1fae900-12e2-4b79-aaaf-8fcbdebf81b7" >

### Face match Scheduler Task

Images of attempted quizzes can be analyzed by an automatic scheduled task. This can be enabled from the following settings.

<img width="960" alt="Scheduler task" src="https://user-images.githubusercontent.com/72008371/195810528-6e3f8d1b-0176-4e23-8b39-024365331f66.png" >

## Allowing webcam access before attempting the quiz

Student will be asked to allow access to their webcam for the exam before attempting the quiz:

<img width="450" alt="2  Attempt Quiz" src="https://user-images.githubusercontent.com/72008371/195811001-868242a6-2bb6-46ad-9479-8dbaba9060ef.PNG">

## Attempting the quiz

During attempting the quiz, the quiz page will look like this:

<img width="960" alt="3  Quiz" src="https://user-images.githubusercontent.com/72008371/180333254-781f97d8-9f08-4b70-b905-5cac3f577045.PNG">

## Proctoring Report

Admins can view the proctoring report:

<img width="960" alt="5  Proctoring summary" src="https://user-images.githubusercontent.com/72008371/180333525-d14d1bb5-698d-46e0-952f-8aea227a4d70.PNG">

Admins can view individual proctoring reports and analyze the images using BS Face Matching API:
<img width="960" alt="6  Proctoring individual report" src="https://github.com/eLearning-BS23/moodle-quizaccess_proctoring/assets/72008371/7516a8be-933f-4e7c-855d-50273faeb876">

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
