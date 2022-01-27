define(['jquery', 'core/ajax', 'core/notification'],
    function($, Ajax, Notification) {
        return {
            setup: function(props) {
                console.log(props.examurl);
                var submitbtn = document.getElementById('id_submitbutton');
                
                $("#id_submitbutton").css("display", "none");
                var quizwindow;
                var startbtn = $('<button disabled class="btn btn-primary" id="id_start_quiz">Start Quiz</button>').click(function () {
                    var sesskey = document.getElementsByName("sesskey")[0].value;
                    var url = props.examurl+'?cmid='+props.cmid+'&sesskey='+sesskey;
                    console.log('url',url);
                    event.preventDefault();
                    // alert('hi');
                    quizwindow = window.open(url, '_blank');
                });

                $( "#id_submitbutton" ).after(startbtn);
                
                var enablesharescreen = props.enablescreenshare;
                if(enablesharescreen == 1){
                    const screenShotInterval = setInterval(takeScreenshot, props.screenshotinterval);
                    window.share_state = document.getElementById('share_state');
                    window.window_surface = document.getElementById('window_surface');
                    window.screenoff = document.getElementById('screen_off_flag');

                    const videoElem = document.getElementById("video-screen");
                    const logElem = document.getElementById("log-screen");
                    var displayMediaOptions = {
                        video: {
                            cursor: "always"
                        },
                        audio: false
                    };

                    $("#share_screen_btn").click(function() {
                        event.preventDefault();
                        startCapture();
                        $("#form_activate").css("visibility", "visible");
                    });

                    async function startCapture() {
                        logElem.innerHTML = "";
                        try {
                            videoElem.srcObject = await navigator.mediaDevices.getDisplayMedia(displayMediaOptions);
                            updateWindowStatus();
                        } catch (err) {
                            let errString = err.toString();
                            if (errString == "NotAllowedError: Permission denied") {
                                alert("Please share entire screen.");
                                return false;
                            }
                        }
                    }

                    $(window).on("beforeunload", function() {
                        quizwindow.close();
                    })
                    
                    window.addEventListener('locationchange', function(){
                        console.log('location changed!');
                        quizwindow.close();
                    })

                    var updateWindowStatus = function() {
                        if (videoElem.srcObject !== null) {
                            const videoTrack = videoElem.srcObject.getVideoTracks()[0];
                            const currentStream = videoElem.srcObject;
                            const active = currentStream.active;
                            const settings = videoTrack.getSettings();
                            const displaySurface = settings.displaySurface;
                            document.getElementById('window_surface').value = displaySurface;
                            document.getElementById('display_surface').innerHTML = displaySurface;
                            document.getElementById('share_screen_status').innerHTML = active;
                            document.getElementById('share_state').value = active;
                            var screenoff = document.getElementById('screen_off_flag').value;
                            
                            console.log(document.getElementById('window_surface'));
                            if(displaySurface !== 'monitor'){
                                // window close 
                                quizwindow.close();
                                console.log('quiz window closed');
                            }
                            
                            if(!active){
                                quizwindow.close();
                            }
                        }
                    };

                    var takeScreenshot = function() {
                        var screenoff = document.getElementById('screen_off_flag').value;
                        if (videoElem.srcObject !== null) {
                            const videoTrack = videoElem.srcObject.getVideoTracks()[0];
                            const currentStream = videoElem.srcObject;
                            const active = currentStream.active;
                            const settings = videoTrack.getSettings();
                            const displaySurface = settings.displaySurface;

                            if (screenoff === "0") {
                                if (!active) {
                                    alert("Sorry !! You need to restart the attempt as you have stopped the screenshare.");
                                    document.getElementById('display_surface').innerHTML = displaySurface;
                                    document.getElementById('share_screen_status').innerHTML = 'Disabled';
                                    clearInterval(screenShotInterval);
                                    quizwindow.close();
                                    return false;
                                }
                                console.log(displaySurface);

                                if (displaySurface !== "monitor") {
                                    alert("Sorry !! You need to share entire screen.");
                                    document.getElementById('display_surface').innerHTML = displaySurface;
                                    document.getElementById('share_screen_status').innerHTML = 'Disabled';
                                    clearInterval(screenShotInterval);
                                    quizwindow.close();
                                    return false;
                                }

                            }
                            // Capture Screen
                            const video_screen = document.getElementById('video-screen');
                            const canvas_screen = document.getElementById('canvas-screen');
                            const screen_context = canvas_screen.getContext('2d');
                            // Var photo_screen = document.getElementById('photo_screen');
                            canvas_screen.width = screen.width;
                            canvas_screen.height = screen.height;
                            screen_context.drawImage(video_screen, 0, 0, screen.width, screen.height);
                            const screen_data = canvas_screen.toDataURL('image/png');
                            // API Call
                            var wsfunction = 'quizaccess_proctoring_send_camshot';
                            var params = {
                                'courseid': props.courseid,
                                'screenshotid': props.id,
                                'quizid': props.cmid,
                                'webcampicture': screen_data,
                                'imagetype': 2
                            };

                            var request = {
                                methodname: wsfunction,
                                args: params
                            };

                            if (screenoff === "0") {
                                Ajax.call([request])[0].done(function(data) {
                                    if (data.warnings.length > 1){
                                        if (video_screen) {
                                            Notification.addNotification({
                                                message: 'Something went wrong during taking the image.',
                                                type: 'error'
                                            });
                                            clearInterval(screenShotInterval);
                                        }
                                    }
                                }).fail(Notification.exception);
                            }
                        }
                    };

                    setInterval(updateWindowStatus, 1000);
                }

                $("#fcvalidate").click(function(event) {
                    event.preventDefault();
                    const photo = document.getElementById('photo');
                    const canvas = document.getElementById('canvas');
                    const video = document.getElementById('video');
                    const context = canvas.getContext('2d');
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    var data = canvas.toDataURL('image/png');
                    photo.setAttribute('src', data);

                    const courseid = document.getElementById('courseidval').value;
                    const cmid = document.getElementById('cmidval').value;
                    const profileimage = document.getElementById('profileimage').value;

                    const wsfunction = 'quizaccess_proctoring_validate_face';
                    const params = {
                        'courseid': courseid,
                        'cmid': cmid,
                        'profileimage': profileimage,
                        'webcampicture': data,
                    };

                    const request = {
                        methodname: wsfunction,
                        args: params
                    };
                    document.getElementById('loading_spinner').style.display = 'block';
                    Ajax.call([request])[0].done(function(data) {
                        if (data.warnings.length < 1) {
                            document.getElementById('loading_spinner').style.display = 'none';
                            var status = data.status;
                            if (status === 'success') {
                                $("#video").css("border", "10px solid green");
                                $("#face_validation_result").html('<span style="color: green">True</span>');
                                document.getElementById("fcvalidate").style.display = "none";
                                if(enablesharescreen === 1){
                                    document.getElementById("share_screen_btn").style.display = "block";
                                }
                                else{
                                    $("#form_activate").css("visibility", "visible");
                                }
                            } else {
                                $("#video").css("border", "10px solid red");
                                $("#face_validation_result").html('<span style="color: red">False</span>');
                            }
                        } else {
                            document.getElementById('loading_spinner').style.display = 'none';
                            if (video) {
                                Notification.addNotification({
                                    message: 'Something went wrong during taking the image.',
                                    type: 'error'
                                });
                            }
                        }
                    }).fail(Notification.exception);

                });

                return true;
            }
        };
    });
