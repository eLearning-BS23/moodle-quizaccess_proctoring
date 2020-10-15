
var isCameraAllowed = false;

define(['jquery', 'core/ajax', 'core/notification'],
    function($, Ajax, Notification) {

    $(function() {
        $('#id_submitbutton').prop("disabled", true);
        $('#id_proctoring').on('change', function() {
            if (this.checked && isCameraAllowed) {
                $('#id_submitbutton').prop("disabled", false);
            } else {
                $('#id_submitbutton').prop("disabled", true);
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

            // Skip for summary page
            if (document.getElementById("page-mod-quiz-summary") !== null &&
                document.getElementById("page-mod-quiz-summary").innerHTML.length) {
                return false;
            }
            if (document.getElementById("page-mod-quiz-review") !== null &&
                document.getElementById("page-mod-quiz-review").innerHTML.length) {
                return false;
            }

            var width = 230; // We will scale the photo width to this
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
            return true;
        },
        init: function(props) {
            var width = 320; // We will scale the photo width to this
            var height = 0; // This will be computed based on the input stream
            var streaming = false;
            var video = null;
            var canvas = null;
            var photo = null;
            var data = null;

            /**
             * Startup
             */
            function startup() {
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
                                message: 'Please allow camera access in your browser.',
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
