define(['jquery', 'core/ajax', 'core/notification', 'core/str'],
    function ($, Ajax, Notification, str) {

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

        let getDataUrl = (studentimg) => {
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");
            // Set width and height
            canvas.width = studentimg.width;
            canvas.height = studentimg.height;
            // Draw the image
            ctx.drawImage(studentimg, 0, 0);
            return canvas.toDataURL("image/png");
        };
        return {
            async setup (modelurl) {            
                await faceapi.nets.ssdMobilenetv1.loadFromUri(modelurl);

                $('#fitem_id_user_photo').append(
                    '<img id="cropimg" style="display:none;"/><img id="previewimg" style="display:none;" height="auto" width="auto"/>');
                
                let submitBtn = document.getElementById('id_submitbutton');
                let previewImage;
                if(submitBtn) {
                    submitBtn.style.display = 'none';
                }

                const intervalToGetImage = setInterval(getPreviewImage, 1000);
                let notificationShown = 0;
                let croppedImage = $('#cropimg');
                async function getPreviewImage() {
                       
                    let preview = document.getElementsByClassName('realpreview');
                    if(preview.length > 0) {
                       
                        previewImage = document.getElementById('previewimg');
                        let imageUrlString = preview[0].src;

                        const splitArray = imageUrlString.split("?");
                        
                        previewImage.src = splitArray[0];
                        let faceFound;
                        
                        await detectface(previewImage, croppedImage);
                        
                        console.log(previewImage.src);
                        console.log(croppedImage.src);
                        if(croppedImage.src) {
                            console.log("Face found");
                            if(submitBtn) {
                                submitBtn.style.display = 'block';
                                stopInterval();
                            }

                            let alertDangers = document.getElementsByClassName('alert-danger');
                            if(alertDangers.length > 0) {
                                alertDangers[0].style.display = 'none';
                                console.log(alertDangers[0]);
                            }

                            Notification.addNotification({
                                message: 'Face found in the uploaded image',
                                type: 'success'
                            });
                            
                        } else {
                            
                            if(notificationShown == 0) {
                                Notification.addNotification({
                                    message: 'Face not found in the uploaded image',
                                    type: 'error'
                                });
                                notificationShown = 1;
                            }
                            console.log("Face not found");
                        }
                        
                    } else {
                        if(submitBtn) {
                            submitBtn.style.display = 'none';
                        }
                    }  
                }

                function stopInterval() {
                    clearInterval(intervalToGetImage);
                }
                
                return true;
            }
        };
    });
