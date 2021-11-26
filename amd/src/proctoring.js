
var isCameraAllowed = false;

define(['jquery', 'core/ajax', 'core/notification'],
    function($, Ajax, Notification) {
    // console.log('proctoring.js loaded');
    $(function() {
        $('#id_start_quiz').prop("disabled", true);
        $('#id_proctoring').on('change', function() {
            if (this.checked && isCameraAllowed) {
                $('#id_start_quiz').prop("disabled", false);
            } else {
                $('#id_start_quiz').prop("disabled", true);
            }
        });
    });
    /**
     * Function hideButtons
     */
    function hideButtons() {
        $('.mod_quiz-next-nav').prop("disabled", true);
        $('.submitbtns').html('<p class="text text-red red">You need to enable web camera before submitting this quiz!</p>');
    }
    var firstcalldelay = 3000; // 3 seconds after the page load
    var takepicturedelay = 30000; // 30 seconds

    return {


        setup: function(props) {
            // $("body").attr("oncopy","return false;");
            // $("body").attr("oncut","return false;");
            // $("body").attr("onpaste","return false;");
            // $("body").attr("oncontextmenu","return false;");

            // Camshotdelay taken from admin_settings
            takepicturedelay = props.camshotdelay;
            // Skip for summary page
            if (document.getElementById("page-mod-quiz-summary") !== null &&
                document.getElementById("page-mod-quiz-summary").innerHTML.length) {
                return false;
            }
            if (document.getElementById("page-mod-quiz-review") !== null &&
                document.getElementById("page-mod-quiz-review").innerHTML.length) {
                return false;
            }

            var width = props.image_width;
            var height = 0; // This will be computed based on the input stream
            var streaming = false;
            var data = null;

            $('#mod_quiz_navblock').append('<div class="card-body p-3"><h3 class="no text-left">Webcam</h3> <br/>'
             + '<video id="video">Video stream not available.</video><canvas id="canvas" style="display:none;"></canvas>'
             + '<div class="output" style="display:none;">'
             + '<img id="photo" alt="The picture will appear in this box."/></div></div>');

            var video = document.getElementById('video');
            var canvas = document.getElementById('canvas');
            var photo = document.getElementById('photo');

            var clearphoto = function() {
                var context = canvas.getContext('2d');
                context.fillStyle = "#AAA";
                context.fillRect(0, 0, canvas.width, canvas.height);
                data = canvas.toDataURL('image/png');
                photo.setAttribute('src', data);
            };

            var takepicture = function() {
                var context = canvas.getContext('2d');
                if (width && height) {
                    canvas.width = width;
                    canvas.height = height;
                    context.drawImage(video, 0, 0, width, height);
                    data = canvas.toDataURL('image/png');
                    photo.setAttribute('src', data);
                    props.webcampicture = data;

                    var wsfunction = 'quizaccess_proctoring_send_camshot';
                    var params = {
                        'courseid': props.courseid,
                        'screenshotid': props.id,
                        'quizid': props.quizid,
                        'webcampicture': data,
                        'imagetype': 1
                    };

                    var request = {
                        methodname: wsfunction,
                        args: params
                    };

                    Ajax.call([request])[0].done(function(data) {
                        if (data.warnings.length < 1) {
                            // NO; pictureCounter++;
                        } else {
                            if (video) {
                                Notification.addNotification({
                                    message: 'Something went wrong during taking the image.',
                                    type: 'error'
                                });
                            }
                        }
                    }).fail(Notification.exception);
                } else {
                    clearphoto();
                }
            };

            navigator.mediaDevices.getUserMedia({video: true, audio: false})
                .then(function(stream) {
                    video.srcObject = stream;
                    video.play();
                    isCameraAllowed = true;
                    return;
                })
                .catch(function() {
                    hideButtons();
                });

            if (video) {
                video.addEventListener('canplay', function() {
                    if (!streaming) {
                        height = video.videoHeight / (video.videoWidth / width);
                        // Firefox currently has a bug where the height can't be read from
                        // The video, so we will make assumptions if this happens.
                        if (isNaN(height)) {
                            height = width / (4 / 3);
                        }
                        video.setAttribute('width', width);
                        video.setAttribute('height', height);
                        canvas.setAttribute('width', width);
                        canvas.setAttribute('height', height);
                        streaming = true;
                    }
                }, false);

                // Allow to click picture
                video.addEventListener('click', function(ev) {
                    takepicture();
                    ev.preventDefault();
                }, false);
                setTimeout(takepicture, firstcalldelay);
                setInterval(takepicture, takepicturedelay);
            } else {
                hideButtons();
            }
            var cascadeClose;
            $(window).ready(function() {
                cascadeClose = setInterval(CloseOnParentClose, 1000);
            });
            const quizurl = props.quizurl;
            function CloseOnParentClose() {
                //// OLD CODE
                // if (typeof window.opener != 'undefined' && window.opener !== null) {
                //     if (window.opener.closed) {
                //         window.close();
                //     }
                // } else {
                //     window.close();
                // }
                //
                // var parentWindowURL = window.opener.location.href;
                // // console.log("parenturl", parentWindowURL);
                // // console.log("quizurl", quizurl);
                //
                // if(!parentWindowURL.includes(quizurl)){
                //     window.close();
                // }
                // if (parentWindowURL !== quizurl) {
                //     window.close();
                // }
                //
                // var share_state = window.opener.share_state;
                // var window_surface = window.opener.window_surface;
                // // Console.log('parent ss', share_state);
                // // console.log('parent ws', window_surface);
                //
                // if (share_state.value !== "true") {
                //     // Window.close();
                //     // console.log('close window now');
                //     window.close();
                // }
                //
                // if (window_surface.value !== 'monitor') {
                //     // Console.log('close window now');
                //     window.close();
                // }
                /////
                
                console.log('window status checking:');
                if (window.opener != null && !window.opener.closed){
                    console.log('window open')
                }
                else {
                    console.log('window closed');
                    clearInterval(cascadeClose);
                    alert('You need to keep the parent window open');
                    window.close();
                    // window.open('http://www.google.com');
                }

                var parentWindowURL = window.opener.location.href;
                // console.log("parenturl", parentWindowURL);
                // console.log("quizurl", quizurl);

                if(!parentWindowURL.includes(quizurl)){
                    clearInterval(cascadeClose);
                    alert('You need to keep the parent window open');
                    window.close();
                }
                if (parentWindowURL !== quizurl) {
                    clearInterval(cascadeClose);
                    alert('You need to keep the parent window open');
                    window.close();
                }
            }

            
            
            // $("#responseform").submit(function() {
            //     var nextpageel = document.getElementsByName('nextpage');
            //     var nextpagevalue = 0;
            //     if (nextpageel.length > 0) {
            //         nextpagevalue = nextpageel[0].value;
            //     }
            //     if (nextpagevalue === "-1") {
            //         window.opener.screenoff.value = "1";
            //     }
            // });

            return true;
        },
        init: function(props) {
            // $("body").attr("oncopy","return false;");
            // $("body").attr("oncut","return false;");
            // $("body").attr("onpaste","return false;");
            // $("body").attr("oncontextmenu","return false;");

            var isMobile = false; //initiate as false
            // device detection
            if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
                || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
                isMobile = true;
            }
            var width = 320;
            if(isMobile){
                width = 100;
            }
            else{
                width = 320;
            }

            var height = 0; // This will be computed based on the input stream
            var streaming = false;
            var video = null;
            var canvas = null;
            var photo = null;
            var data = null;

            /**
             * Startup
             */
            function startup(props) {
                video = document.getElementById('video');
                canvas = document.getElementById('canvas');
                photo = document.getElementById('photo');

                if (video) {
                    navigator.mediaDevices.getUserMedia({video: true, audio: false})
                        .then(function(stream) {
                            video.srcObject = stream;
                            video.play();
                            isCameraAllowed = true;
                            return;
                        })
                        .catch(function() {
                            Notification.addNotification({
                                message: props.allowcamerawarning,
                                type: 'warning'
                            });
                            hideButtons();
                        });

                    video.addEventListener('canplay', function() {
                        if (!streaming) {
                            height = video.videoHeight / (video.videoWidth / width);
                            // Firefox currently has a bug where the height can't be read from
                            // The video, so we will make assumptions if this happens.
                            if (isNaN(height)) {
                                height = width / (4 / 3);
                            }
                            video.setAttribute('width', width);
                            video.setAttribute('height', height);
                            canvas.setAttribute('width', width);
                            canvas.setAttribute('height', height);
                            streaming = true;
                        }
                    }, false);

                    // Allow to click picture
                    video.addEventListener('click', function(ev) {
                        takepicture();
                        ev.preventDefault();
                    }, false);
                } else {
                    hideButtons();
                }
                clearphoto();
            }

            /**
             * Clearphoto
             */
            function clearphoto() {
                if (isCameraAllowed) {
                    var context = canvas.getContext('2d');
                    context.fillStyle = "#AAA";
                    context.fillRect(0, 0, canvas.width, canvas.height);

                    data = canvas.toDataURL('image/png');
                    photo.setAttribute('src', data);
                } else {
                    hideButtons();
                }
            }

            /**
             * Takepicture
             */
            function takepicture() {
                var context = canvas.getContext('2d');
                if (width && height) {
                    canvas.width = width;
                    canvas.height = height;
                    context.drawImage(video, 0, 0, width, height);
                    data = canvas.toDataURL('image/png');
                    photo.setAttribute('src', data);

                    var wsfunction = 'quizaccess_proctoring_send_camshot';
                    var params = {
                        'courseid': props.courseid,
                        'screenshotid': props.id,
                        'quizid': props.quizid,
                        'webcampicture': data,
                        'imagetype': 1
                    };

                    var request = {
                        methodname: wsfunction,
                        args: params
                    };

                    Ajax.call([request])[0].done(function(data) {
                        if (data.warnings.length < 1) {
                            // Not console.log(data);
                        } else {
                            Notification.addNotification({
                                message: 'Something went wrong during taking screenshot.',
                                type: 'error'
                            });
                        }
                    }).fail(Notification.exception);

                } else {
                    clearphoto();
                }
            }

            /**
             * HideButtons
             */
            function hideButtons() {
                $('.mod_quiz-next-nav').prop("disabled", true);
                $('.submitbtns').html(
                    '<p class="text text-red red">You need to enable web camera before submitting this quiz!</p>');
            }

            startup();

            return data;
        }
    };
});
