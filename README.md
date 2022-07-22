# Moodle Proctoring

Moodle proctoring is a quizaccess plugin to capture the user's picture via webcam to identify who is attempting the Moodle Quiz. It will capture the picture automatically every 30 seconds and store it as a PNG image. 

This plugin will help you to capture random pictures via webcam when the student/user is attempting the Quiz. 

Before starting the quiz, it will ask for camera permission. By accepting the permission you will be able to see your picture and you can continue to answer the questions. It will act as a video recording service like everything is capturing so the user will not try to do anything suspicious during the exam.

<img width="960" alt="3  Quiz" src="https://user-images.githubusercontent.com/72008371/180333254-781f97d8-9f08-4b70-b905-5cac3f577045.PNG">


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
- Facerecognition service(AWS/BS). [This feature validates the user image with profile image. You can use either Amazon Rekognition or Brainstation Facerecognition service. Please contact us(tahmina@brainstation-23.com) if you want to obtain API Key for brainstation face recognition service]


## Configuration

You can install this plugin from [Moodle plugins directory](https://moodle.org/plugins/quizaccess_proctoring) or can download from [Github](http://github.com/eLearning-BS23/moodle-quizaccess_proctoring).

> After installing the plugin, you can use the plugin by following:


- Go to you quiz setting (Edit Quiz): 
- Change the *Extra restrictions on attempts* to **must be acknowledged before starting an attempt**
- Done!
```
  Dashboard->My courses->Your Course Name->Lesson->Quiz Name->Edit settings
```
<img width="695" alt="4  Proctoring settings" src="https://user-images.githubusercontent.com/72008371/180333437-8913a05c-0a0c-47fc-a360-2f2f340404ac.PNG">

> Now you can attempt your quiz like this:
<img width="450" alt="2  Attempt Quiz" src="https://user-images.githubusercontent.com/72008371/180333492-9a0623b2-7576-469f-9524-436213927533.PNG">

> You can check the report from Admin Site:
<img width="955" alt="5  Proctoring summary" src="https://user-images.githubusercontent.com/72008371/180333525-d14d1bb5-698d-46e0-952f-8aea227a4d70.PNG">



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
