define(['jquery', 'core/ajax', 'core/notification'],
    function($, Ajax, Notification) {
        return {
            setup: function(props) {
                // $("body").attr("oncopy","return false;");
                // $("body").attr("oncut","return false;");
                // $("body").attr("onpaste","return false;");
                // $("body").attr("oncontextmenu","return false;");
                // console.log(props);
                console.log(props.examurl);
                var submitbtn = document.getElementById('id_submitbutton');
                
                $("#id_submitbutton").css("display", "none");
                var quizwindow;
                var startbtn = $('<button disabled class="btn btn-primary" id="id_start_quiz">Start Quiz</button>').click(function () {
                    // var url = props.examurl+'?attempt='+props.attemptid+'&cmid='+props.cmid;
                    
                    var sesskey = document.getElementsByName("sesskey")[0].value;
                    var url = props.examurl+'?cmid='+props.cmid+'&sesskey='+sesskey;
                    console.log('url',url);
                    event.preventDefault();
                    // alert('hi');
                    quizwindow = window.open(url, '_blank');
                });

                // var quizlink = "<a href='http://www.google.com' target='_blank' class='btn btn-primary'>Start Quiz</a>";
                $( "#id_submitbutton" ).after(startbtn);
                
                var enablesharescreen = props.enablescreenshare;
                if(enablesharescreen == 1){
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
                        // Console.log('screen sharing clicked');
                        startCapture();
                        $("#form_activate").css("visibility", "visible");
                        // Options for getDisplayMedia()

                    });

                    async function startCapture() {
                        logElem.innerHTML = "";
                        try {
                            // Console.log("vid found success");
                            videoElem.srcObject = await navigator.mediaDevices.getDisplayMedia(displayMediaOptions);
                            dumpOptionsInfo();
                            updateWindowStatus();
                        } catch (err) {
                            // Console.log("Error: " + err.toString());
                            let errString = err.toString();
                            if (errString == "NotAllowedError: Permission denied") {
                                alert("Please share entire screen.");
                                return false;
                            }
                        }
                    }

                    function dumpOptionsInfo() {
                        // Const videoTrack = videoElem.srcObject.getVideoTracks()[0];

                        // Console.info("Track settings:");
                        // console.info(JSON.stringify(videoTrack.getSettings(), null, 2));
                        // console.info("Track constraints:");
                        // console.info(JSON.stringify(videoTrack.getConstraints(), null, 2));
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
                            // Console.log(videoElem);
                            const videoTrack = videoElem.srcObject.getVideoTracks()[0];
                            var currentStream = videoElem.srcObject;
                            var active = currentStream.active;
                            var settings = videoTrack.getSettings();
                            var displaySurface = settings.displaySurface;
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
                            
                            // if (screenoff == "1") {
                            //     videoTrack.stop();
                            //     quizwindow.close();
                            //     console.log('quiz window closed');
                            //     clearInterval(windowState);
                            //     // location.reload();
                            // }
                        }
                    };

                    var takeScreenshot = function() {
                        var screenoff = document.getElementById('screen_off_flag').value;
                        if (videoElem.srcObject !== null) {
                            // Console.log(videoElem);
                            const videoTrack = videoElem.srcObject.getVideoTracks()[0];
                            var currentStream = videoElem.srcObject;
                            var active = currentStream.active;
                            // Console.log(active);

                            var settings = videoTrack.getSettings();
                            var displaySurface = settings.displaySurface;

                            if (screenoff == "0") {
                                if (!active) {
                                    alert("Sorry !! You need to restart the attempt as you have stopped the screenshare.");
                                    document.getElementById('display_surface').innerHTML = displaySurface;
                                    document.getElementById('share_screen_status').innerHTML = 'Disabled';
                                    clearInterval(screenShotInterval);
                                    // window.close();
                                    quizwindow.close();
                                    return false;
                                }
                                console.log(displaySurface);

                                if (displaySurface !== "monitor") {
                                    // console.log(displaySurface);
                                    alert("Sorry !! You need to share entire screen.");
                                    document.getElementById('display_surface').innerHTML = displaySurface;
                                    document.getElementById('share_screen_status').innerHTML = 'Disabled';
                                    clearInterval(screenShotInterval);
                                    // window.close();
                                    quizwindow.close();
                                    return false;
                                }

                            }
                            // Console.log(displaySurface);
                            // console.log(quizurl);

                            // Capture Screen
                            var video_screen = document.getElementById('video-screen');
                            var canvas_screen = document.getElementById('canvas-screen');
                            var screen_context = canvas_screen.getContext('2d');
                            // Var photo_screen = document.getElementById('photo_screen');
                            canvas_screen.width = screen.width;
                            canvas_screen.height = screen.height;
                            screen_context.drawImage(video_screen, 0, 0, screen.width, screen.height);
                            var screen_data = canvas_screen.toDataURL('image/png');
                            // Photo_screen.setAttribute('src', screen_data);
                            // console.log(screen_data);

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

                            // Console.log('params', params);
                            if (screenoff == "0") {
                                Ajax.call([request])[0].done(function(data) {
                                    if (data.warnings.length < 1) {
                                        // NO; pictureCounter++;
                                    } else {
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

                    var screenShotInterval = setInterval(takeScreenshot, props.screenshotinterval);
                    var windowState = setInterval(updateWindowStatus, 1000);
                }

                $("#fcvalidate").click(function() {
                    event.preventDefault();
                    // Console.log('validate face clicked');
                    var photo = document.getElementById('photo');
                    var canvas = document.getElementById('canvas');
                    var video = document.getElementById('video');
                    var context = canvas.getContext('2d');
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    var data = canvas.toDataURL('image/png');
                    photo.setAttribute('src', data);

                    var courseid = document.getElementById('courseidval').value;
                    var cmid = document.getElementById('cmidval').value;
                    var profileimage = document.getElementById('profileimage').value;

                    var wsfunction = 'quizaccess_proctoring_validate_face';
                    var params = {
                        'courseid': courseid,
                        'cmid': cmid,
                        'profileimage': profileimage,
                        'webcampicture': data,
                    };

                    var request = {
                        methodname: wsfunction,
                        args: params
                    };
                    document.getElementById('loading_spinner').style.display = 'block';
                    Ajax.call([request])[0].done(function(data) {
                        if (data.warnings.length < 1) {
                            document.getElementById('loading_spinner').style.display = 'none';
                            // NO; pictureCounter++;
                            // console.log('api response', data);
                            var status = data.status;
                            if (status == 'success') {
                                $("#video").css("border", "10px solid green");
                                $("#face_validation_result").html('<span style="color: green">True</span>');
                                // Document.getElementById("validate_form").style.display = "none";
                                document.getElementById("fcvalidate").style.display = "none";
                                // console.log(enablesharescreen);
                                if(enablesharescreen == 1){
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
