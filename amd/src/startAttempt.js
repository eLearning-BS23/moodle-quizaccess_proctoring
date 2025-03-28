define(['jquery', 'core/ajax', 'core/notification'],
    function($, Ajax, Notification) {
        // Function to draw image from the box data.
        const extractFaceFromBox = async(imageRef, box, croppedImage) => {
            const regionsToExtract = [
                // eslint-disable-next-line no-undef
                new faceapi.Rect(box.x, box.y, box.width, box.height)
            ];
            // eslint-disable-next-line no-undef
            let faceImages = await faceapi.extractFaces(imageRef, regionsToExtract);

            if (faceImages.length === 0) {
                // eslint-disable-next-line no-console
               // console.log('Face not found');
            } else {
                // eslint-disable-next-line no-console
                faceImages.forEach((cnv) => {
                    croppedImage.src = cnv.toDataURL();
                });
            }
        };
        const detectface = async(input, croppedImage) => {
            // eslint-disable-next-line no-undef
            const output = await faceapi.detectAllFaces(input);
            if (output.length === 0) {
                // eslint-disable-next-line no-console
            } else {
                // eslint-disable-next-line no-console
                let detections = output[0].box;
                await extractFaceFromBox(input, detections, croppedImage);
            }
        };
        return {
            setup: async function(props, modelurl) {
                if (modelurl != null) {
                    await faceapi.nets.ssdMobilenetv1.loadFromUri(modelurl);
                }

                $('#fcvalidate').append('<img id="validate-cropimg" style="display: none;" src="" alt=""/>');
                $("#fcvalidate").click(async function(event) {

                    event.preventDefault();
                    const photo = document.getElementById('photo');
                    const canvas = document.getElementById('canvas');
                    const video = document.getElementById('video');
                    const context = canvas.getContext('2d');
                    canvas.width = props.imagewidth;

                    canvas.height = canvas.width / (4/3);
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    var data = canvas.toDataURL('image/png');
                    photo.setAttribute('src', data);

                    const courseid = document.getElementById('courseidval').value;
                    const cmid = document.getElementById('cmidval').value;
                    const profileimage = document.getElementById('profileimage').value;

                    // Getting the face image from screenshot.
                    let croppedImage = $('#validate-cropimg');
                    if (modelurl != null) {
                        await detectface(photo, croppedImage);
                    }

                    let faceFound;
                    let faceImage;
                    if (croppedImage.src) {
                        // eslint-disable-next-line no-console
                        // console.log("Face found");
                        faceFound = 1;
                        faceImage = croppedImage.src;
                    } else {
                        // eslint-disable-next-line no-console
                        // console.log("Face not found");
                        faceFound = 0;
                        faceImage = "";
                    }
                    const wsfunction = 'quizaccess_proctoring_validate_face';
                    const params = {
                        'courseid': courseid,
                        'cmid': cmid,
                        'profileimage': profileimage,
                        'webcampicture': data,
                        'parenttype': 'camshot_image',
                        'faceimage': faceImage,
                        'facefound': faceFound,
                    };

                    const request = {
                        methodname: wsfunction,
                        args: params
                    };
                    document.getElementById('loading_spinner').style.display = 'block';
                    Ajax.call([request])[0].done(function(res) {
                        if (res.warnings.length < 1) {
                            document.getElementById('loading_spinner').style.display = 'none';
                            var status = res.status;
                            if (status === 'success') {
                                $("#video").css("border", "10px solid green");
                                $("#face_validation_result").html('<span style="color: green">Face Matched</span>');
                                document.getElementById("fcvalidate").style.display = "none";
                                $("#form_activate").css("visibility", "visible");
                            } else if (status === 'photonotuploaded') {
                                $("#video").css("border", "10px solid red");
                                // eslint-disable-next-line max-len
                                $("#face_validation_result").html('<span style="color: red">Photo not uploaded.Please contact to the admin</span>');
                            } else if(status === 'invalidApi') {
                                $("#video").css("border", "10px solid red");
                                $("#face_validation_result").html('<span style="color: red">Invalid API.Please contact to the admin.</span>');     
                            } else {
                                $("#video").css("border", "10px solid red");
                                $("#face_validation_result").html('<span style="color: red">Face not matched</span>');
                            }
                        }  else {
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
