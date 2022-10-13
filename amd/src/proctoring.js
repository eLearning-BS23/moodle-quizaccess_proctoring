// @SuppressWarnings("javascript:S4144");
let isCameraAllowed = false;

define(['jquery', 'core/ajax', 'core/notification'],
    function ($, Ajax, Notification) {
        $('#id_submitbutton').prop("disabled", true);
        $(function () {
            $('#id_submitbutton').prop("disabled", true);
            $('#id_proctoring').on('change', function () {
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

        const showNotification = (message, type) => {
            removeNotifications();
            Notification.addNotification({
                message, 
                type
            });
        }

        const removeNotifications = () => {
            const alertElements = document.getElementsByClassName('alert');
            if(alertElements.length > 0) {
                alertElements.forEach(alertDiv => {
                    console.log(alertDiv);
                    alertDiv.style.display = 'none';
                });
            }
        }
        
        let firstcalldelay = 3000; // 3 seconds after the page load
        let takepicturedelay = 30000; // 30 seconds
        // Function to draw image from the box data.
        const extractFaceFromBox = async (imageRef, box, croppedImage) => {
            const regionsToExtract = [
                // eslint-disable-next-line no-undef
                new faceapi.Rect(box.x, box.y, box.width, box.height)
            ];
            // eslint-disable-next-line no-undef
            let faceImages = await faceapi.extractFaces(imageRef, regionsToExtract);

            if (faceImages.length === 0) {
                // eslint-disable-next-line no-console
                console.log('Face not found');
            } else {
                // eslint-disable-next-line no-console
                faceImages.forEach((cnv) => {
                    croppedImage.src = cnv.toDataURL();
                });
                // console.log(croppedImage.src);
            }
        };
        const detectface = async (input, croppedImage) => {
            // eslint-disable-next-line no-undef
            const output = await faceapi.detectAllFaces(input);
            if (output.length === 0) {
                // eslint-disable-next-line no-console
                //console.log("No face found");
            } else {
                // eslint-disable-next-line no-console
                //console.log("Face found");
                let detections = output[0].box;
                await extractFaceFromBox(input, detections, croppedImage);
            }
        };
        return {
            async setup(props, modelurl) {
                // eslint-disable-next-line babel/no-unused-expressions,no-undef,promise/catch-or-return
                await faceapi.nets.ssdMobilenetv1.loadFromUri(modelurl);
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

                const width = props.image_width;
                let height = 0; // This will be computed based on the input stream
                let streaming = false;
                let data = null;

                $('#mod_quiz_navblock').append('<div class="card-body p-3"><h3 class="no text-left">Webcam</h3> <br/>'
                    + '<video id="video">Video stream not available.</video>'
                    + '<img id="cropimg" src="" alt=""/><canvas id="canvas" style="display:none;"></canvas>'
                    + '<div class="output" style="display:none;">'
                    + '<img id="photo" alt="The picture will appear in this box."/></div></div>');
                // eslint-disable-next-line promise/catch-or-return

                // eslint-disable-next-line promise/always-return
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const photo = document.getElementById('photo');

                const clearphoto = () => {
                    const context = canvas.getContext('2d');
                    context.fillStyle = "#AAA";
                    context.fillRect(0, 0, canvas.width, canvas.height);
                    data = canvas.toDataURL('image/png');
                    photo.setAttribute('src', data);
                };

                const takepicture = async () => {
                    const context = canvas.getContext('2d');
                    if (width && height) {
                        canvas.width = width;
                        canvas.height = height;
                        context.drawImage(video, 0, 0, width, height);
                        data = canvas.toDataURL('image/png');
                        photo.setAttribute('src', data);
                        props.webcampicture = data;
                        // eslint-disable-next-line no-console,promise/catch-or-return
                        let croppedImage = $('#cropimg');
                        await detectface(photo, croppedImage);
                        
                        console.log(croppedImage.src);

                        let faceFound;
                        let faceImage;
                        if(croppedImage.src) {
                            console.log("Face found");
                            removeNotifications();
                            faceFound = 1;
                            faceImage = croppedImage.src;
                        } else {
                            console.log("Face not found");
                            showNotification('Face not found. Try changing your camera to a better lighting. Thanks.', 'error');
                            faceFound = 0;
                            faceImage = "";
                        }
                        // eslint-disable-next-line promise/catch-or-return
                        var wsfunction = 'quizaccess_proctoring_send_camshot';
                        var params = {
                            'courseid': props.courseid,
                            'screenshotid': props.id,
                            'quizid': props.quizid,
                            'webcampicture': data,
                            'imagetype': 1,
                            'parenttype': 'camshot_image',
                            'faceimage': faceImage,
                            'facefound': faceFound,
                        };
                        var request = {
                            methodname: wsfunction,
                            args: params
                        };

                        Ajax.call([request])[0].done(function (res) {
                            if (res.warnings.length < 1) {
                                // NO
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
                    // eslint-disable-next-line promise/always-return
                    .then(function (stream) {
                        video.srcObject = stream;
                        video.play();
                        isCameraAllowed = true;
                    })
                    .catch(function () {
                        hideButtons();
                    });

                if (video) {
                    video.addEventListener('canplay', function () {
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
                    video.addEventListener('click', async function (ev) {
                        await takepicture();
                        ev.preventDefault();
                    }, false);
                    setTimeout(takepicture, firstcalldelay);
                    setInterval(takepicture, takepicturedelay);
                } else {
                    hideButtons();
                }

                return true;
            },
            async init(props) {
                let height = 0; // This will be computed based on the input stream
                let streaming = false;
                let video = null;
                let canvas = null;
                let photo = null;
                let data = null;
                const width = props.image_width;

                /**
                 * Startup
                 */
                async function startup() {
                    video = document.getElementById('video');
                    canvas = document.getElementById('canvas');
                    photo = document.getElementById('photo');

                    if (video) {
                        navigator.mediaDevices.getUserMedia({video: true, audio: false})
                            // eslint-disable-next-line promise/always-return
                            .then(function (stream) {
                                video.srcObject = stream;
                                video.play();
                                isCameraAllowed = true;
                            })
                            .catch(function () {
                                Notification.addNotification({
                                    message: props.allowcamerawarning,
                                    type: 'warning'
                                });
                                hideButtons();
                            });

                        video.addEventListener('canplay', function () {
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
                        video.addEventListener('click', async function (ev) {
                            await takepicture();
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
                async function takepicture() {
                    var context = canvas.getContext('2d');
                    if (width && height) {
                        $(document).trigger("screenshoottaken");
                        canvas.width = width;
                        canvas.height = height;
                        context.drawImage(video, 0, 0, width, height);
                        data = canvas.toDataURL('image/png');
                        photo.setAttribute('src', data);
                        // Load the model.
                        // eslint-disable-next-line promise/catch-or-return

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

                        Ajax.call([request])[0].done(async function (res) {
                            if (res.warnings.length < 1) {
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

                await startup();

                return data;
            }
        };
    });
