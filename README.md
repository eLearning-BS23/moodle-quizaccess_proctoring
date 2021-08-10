# Moodle Proctoring

Moodle proctoring is a quizaccess plugin to capture the user's picture via webcam to identify who is attempting the Moodle Quiz. It will capture the picture automatically every 30 seconds and store it as a PNG image. It also captures the screenshot during the quiz. 

This plugin will help you to capture random pictures via webcam & as well as screenshot when the student/user is attempting the Quiz. 

Before starting the quiz, it will ask for camera permission & screenshare permission. By accepting the permission you will be able to see your picture and you can continue to answer the questions. It will act as a video recording service like everything is capturing so the user will not try to do anything suspicious during the exam.

<p align="center">
<img src="https://imgur.com/OpW0BVz.png">
</p>


## Features
- Capture user/student images via web camera
- Can't access quiz if the user does not allow the camera
- Admin report and check any suspicious activity
- Will work with existing Question Bank and Quiz
- Webservice API for external call
- Images are stored in Moodledata as a small png image
- Image can be deleted individually or in bulk
- Proctoring log report with search facilities
- Configurable image size and capture interval
- Capture screenshot during quiz
- Facerecognition service(AWS/BS). [This feature validates the user image with profile image. You can use either Amazon Rekognition or Brainstation Facerecognition service. Please contact us if you want to obtain API Key for brainstation face recognition service]


## Configuration

You can install this plugin from [Moodle plugins directory](https://moodle.org/plugins) or can download from [Github](https://github.com/AnowarCST/moodle-quizaccess_proctoring).

> After installing the plugin, you can use the plugin by following:


- Go to you quiz setting (Edit Quiz): 
- Change the *Extra restrictions on attempts* to **must be acknowledged before starting an attempt**
- Done!
```
  Dashboard->My courses->Your Course Name->Lesson->Quiz Name->Edit settings
```
<p align="center">
<img src="https://i.imgur.com/rwTYQ9M.png" width="80%">
</p>

> Now you can attempt your quiz like this:
<p align="center">
<img src="https://imgur.com/Zef3eqn.png" width="40%">
</p>

> You can check the report from Admin Site:
<p align="center">
<img src="https://imgur.com/QJ7yVTL.png">
</p>



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
