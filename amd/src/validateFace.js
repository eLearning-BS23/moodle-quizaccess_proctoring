define(['jquery', 'core/ajax', 'core/notification', 'core/str'],
    function($, Ajax, Notification, Str) {
        return {
            setup: function() {

                const loadStrings = async function() {
                    const stringkeys = [
                        {key: 'wrong_during_taking_image', component: 'quizaccess_proctoring'},
                    ];
                    try {
                        const strings = await Str.get_strings(stringkeys);
                        return {
                            wrong_during_taking_image: strings[0],
                        };
                    } catch (error) {
                        Notification.exception(error);
                    }
                };

                $("#fgroup_id_buttonar").css("padding", "5px");

                $("#fcvalidate").click(async function(event) {
                    event.preventDefault();
                    const photo = document.getElementById('photo');
                    const canvas = document.getElementById('canvas');
                    const video = document.getElementById('video');
                    const context = canvas.getContext('2d');
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const data = canvas.toDataURL('image/png');
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

                    const strings = await loadStrings(); // Load localized strings.

                    Ajax.call([request])[0].done(function(res) {
                        if (res.warnings.length < 1) {
                            const status = res.status;
                            if (status === 'success') {
                                $("#video").css("border", "10px solid green");
                                document.getElementById("validate_form").style.display = "none";
                                document.getElementById("accept_form").style.display = "block";
                            } else {
                                $("#video").css("border", "10px solid red");
                            }
                        } else {
                            if (video) {
                                Notification.addNotification({
                                    message: strings.wrong_during_taking_image,
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
