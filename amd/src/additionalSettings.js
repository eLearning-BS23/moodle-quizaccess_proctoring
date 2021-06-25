define(['jquery', 'core/ajax', 'core/notification'],
    function($) {
        return {
            setup: function() {

                $("#select_all").click(function() {
                    // Alert("Handler for .click() called.");
                    var checkBox = document.getElementById("select_all");
                    var btn = document.getElementById("delete_select_btn");
                    if (checkBox.checked == true) {
                        btn.style.display = "block";
                        $(".reportIdChkBox").prop('checked', true);
                    } else {
                        btn.style.display = "none";
                        $(".reportIdChkBox").prop('checked', false);
                    }

                    refreshDeleteIdStringValue();
                });

                $(".reportIdChkBox").click(function() {
                    // Alert("Handler for .click() called.");
                    var btn = document.getElementById("delete_select_btn");

                    var checkBoxArray = document.getElementsByClassName('reportIdChkBox');
                    var anychecked = false;
                    for (var i = 0; i < checkBoxArray.length; i++) {
                        if (checkBoxArray[i].checked == true) {
                            anychecked = true;
                        }
                    }

                    if (anychecked) {
                        btn.style.display = "block";
                    } else {
                        btn.style.display = "none";
                    }

                    refreshDeleteIdStringValue();
                });

                function refreshDeleteIdStringValue() {
                    var idArray = [];
                    var checkBoxArray = document.getElementsByClassName('reportIdChkBox');
                    for (var i = 0; i < checkBoxArray.length; i++) {
                        if (checkBoxArray[i].checked == true) {
                            idArray.push(checkBoxArray[i].value);
                        }
                    }
                    var idString = idArray.join();
                    document.getElementById('deleteidstring').value = idString;
                }

                return true;
            }
        };
    });
