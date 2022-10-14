define(['jquery', 'core/ajax', 'core/notification', 'core/str'],
    function ($, Ajax, Notification, str) {

        let notificationShown = 0;

        const clearPreviousNotifications = () => {
            let alerts = document.getElementsByClassName('alert');
            if(alerts.length > 0) {
                alerts.forEach(alert => {
                    alert.style.display = 'none';
                });
                notificationShown = 0;
            }
        }

        const displayNotification = (message, type) => {
            Notification.addNotification({
                message,
                type
            });
        }

        // Function to draw image from the box data.
        const extractFaceFromBox = async (imageRef, box, croppedImage) => {
            const regionsToExtract = [
                new faceapi.Rect(box.x, box.y, box.width, box.height)
            ];
            let faceImages = await faceapi.extractFaces(imageRef, regionsToExtract);

            if (faceImages.length === 0) {   
                console.log('Face not found');
            } else {
                faceImages.forEach((cnv) => {
                    croppedImage.src = cnv.toDataURL();
                });
            }
        };
        const detectface = async (input, croppedImage) => {
            
            const output = await faceapi.detectAllFaces(input);
            if (output.length === 0) {
                console.log("Face not found");
            } else {
                
                let detections = output[0].box;
                await extractFaceFromBox(input, detections, croppedImage);
            }
        };

        return {
            async setup (modelurl) {            
                await faceapi.nets.ssdMobilenetv1.loadFromUri(modelurl);

                $('#fitem_id_user_photo').append(
                    '<img id="cropimg" style="display:none;"/><img id="previewimg" style="display:none;" height="auto" width="auto"/>');
                
                let submitBtn = document.getElementById('id_submitbutton');
                let croppedImage = $('#cropimg');

                let previewImage;
                if(submitBtn) {
                    submitBtn.disabled=true;
                }

                intervalToGetImage = setInterval(getPreviewImage, 1000);
                async function getPreviewImage() {
                       
                    let preview = document.getElementsByClassName('realpreview');
                    if(preview.length > 0) {
                       
                        previewImage = document.getElementById('previewimg');
                        let imageUrlString = preview[0].src;

                        const splitArray = imageUrlString.split("?");

                        if(previewImage.src !== splitArray[0]) {
                            previewImage.src = splitArray[0];
                        } else {
                            return;
                        }
                        
                        await detectface(previewImage, croppedImage);
                        
                        if(croppedImage.src) {
                            console.log("Face found");
                            if(submitBtn) {
                                submitBtn.disabled = false;
                                //stopInterval();
                            }

                            clearPreviousNotifications();

                            if(notificationShown == 0) {
                                displayNotification('Face found in the uploaded image', 'success');
                                notificationShown = 1;
                            }
                            let faceImageField = document.querySelector('[name="face_image"]');
                            
                            if(faceImageField) {
                                faceImageField.setAttribute('value', croppedImage.src);
                            }
                            
                        } else {
                            clearPreviousNotifications();
                            if(notificationShown == 0) {
                                displayNotification('Face not found in the uploaded image', 'error');
                                notificationShown = 1;
                            }
                            croppedImage.src = null;
                            console.log("Face not found");
                        }
                        
                    } else {
                        if(submitBtn) {
                            submitBtn.disabled = true;
                        }
                    }  
                }

                // function stopInterval() {
                //     clearInterval(intervalToGetImage);
                // }
                
                return true;
            }
        };
    });
