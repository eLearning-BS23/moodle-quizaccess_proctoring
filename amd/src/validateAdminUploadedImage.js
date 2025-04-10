define(['jquery', 'core/ajax', 'core/notification', 'core/str'],
    function($, Ajax, Notification, Str) {

        const loadStrings = async function() {
            const stringkeys = [
                {key: 'facefound', component: 'quizaccess_proctoring'},
                {key: 'facenotfound', component: 'quizaccess_proctoring'},
            ];
            try {
                const strings = await Str.get_strings(stringkeys);
                return {
                    facefound: strings[0],
                    facenotfound: strings[1]
                };
            } catch (error) {
                Notification.exception(error);
            }
        };

        let notificationShown = 0;

        const clearPreviousNotifications = () => {
            try {
                let alerts = document.getElementsByClassName('alert');
                if(alerts.length > 0) {
                    Array.from(alerts).forEach(alert => {
                        alert.style.display = 'none';
                    });
                    notificationShown = 0;
                }
            } catch (error) {
                Notification.exception(error);
            }
        };

        const displayNotification = (message, type) => {
            Notification.addNotification({
                message,
                type
            });
        };

        // Function to draw image from the box data. babel/no-unused-
        const extractFaceFromBox = async(imageRef, box, croppedImage) => {
            const regionsToExtract = [
                // eslint-disable-next-line
                new faceapi.Rect(box.x, box.y, box.width, box.height)
            ];
            // eslint-disable-next-line
            let faceImages = await faceapi.extractFaces(imageRef, regionsToExtract);
            if (faceImages.length > 0) {
                faceImages.forEach((cnv) => {
                    croppedImage.src = cnv.toDataURL();
                });
            }
        };

        // Function to detect face from the image.
        const detectface = async(input, croppedImage) => {
            // eslint-disable-next-line
            const output = await faceapi.detectAllFaces(input);
            if (output.length > 0) {
                let detections = output[0].box;
                await extractFaceFromBox(input, detections, croppedImage);
            }
        };

        return {
            async setup(modelurl) {
                // eslint-disable-next-line
                await faceapi.nets.ssdMobilenetv1.loadFromUri(modelurl);
                $('#fitem_id_user_photo').append(
                '<img id="cropimg" style="display:none;"/><img id="previewimg" style="display:none;" height="auto"width="auto"/>');
                let submitBtn = document.getElementById('id_submitbutton');
                let croppedImage = $('#cropimg');

                let previewImage;
                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                setInterval(getPreviewImage, 1000);
                /**
                 * Checks for the preview image in the dom
                 *
                 */
                async function getPreviewImage() {
                    let preview = document.getElementsByClassName('realpreview');
                    const strings = await loadStrings();
                    if (preview.length > 0) {
                        previewImage = document.getElementById('previewimg');
                        let imageUrlString = preview[0].src;
                        const splitArray = imageUrlString.split("?");

                        if (previewImage.src !== splitArray[0]) {
                            previewImage.src = splitArray[0];
                        } else {
                            return;
                        }

                        await detectface(previewImage, croppedImage);

                        if (croppedImage.src) {
                            if (submitBtn) {
                                submitBtn.disabled = false;
                            }

                            clearPreviousNotifications();
                            if (notificationShown == 0) {
                                displayNotification(strings.facefound, 'success');
                                notificationShown = 1;
                            }

                            let faceImageField = document.querySelector('[name="face_image"]');

                            if (faceImageField) {
                                faceImageField.setAttribute('value', croppedImage.src);
                            }
                            croppedImage.src = null;
                        } else {
                            clearPreviousNotifications();
                            if (notificationShown == 0) {
                                displayNotification(strings.facenotfound, 'error');
                                notificationShown = 1;
                                submitBtn.disabled = true;
                            }
                            croppedImage.src = null;
                            let faceImageField = document.querySelector('[name="face_image"]');
                            if (faceImageField) {
                                faceImageField.setAttribute('value', croppedImage.src);
                            }
                        }
                    } else {
                        if(submitBtn) {
                            submitBtn.disabled = true;
                        }
                    }
                }
                return true;
            }
        };
    });
