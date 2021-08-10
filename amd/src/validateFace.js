define(['jquery', 'core/ajax', 'core/notification'],
    function($, Ajax, Notification) {
        return {
            setup: function() {
                // Console.log('validate face called');
                $("#fgroup_id_buttonar").css("padding", "5px");

                $("#fcvalidate").click(function() {
                    event.preventDefault();
                    // Console.log('validate clicked');
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
                    // Var currentimage = document.getElementById('currentimage').value;

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

                    Ajax.call([request])[0].done(function(data) {
                        if (data.warnings.length < 1) {
                            // NO; pictureCounter++;
                            // console.log('api response', data);
                            var status = data.status;
                            if (status == 'success') {
                                $("#video").css("border", "10px solid green");
                                document.getElementById("validate_form").style.display = "none";
                                document.getElementById("accept_form").style.display = "block";
                            } else {
                                $("#video").css("border", "10px solid red");
                            }
                        } else {
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
