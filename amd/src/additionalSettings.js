define(['jquery', 'core/ajax', 'core/notification'],
    function ($) {
        return {
            setup: function () {
                $("#select_all").click(function () {
                    const checkBox = document.getElementById("select_all");
                    const btn = document.getElementById("delete_select_btn");
                    if (checkBox.checked === true) {
                        btn.style.display = "block";
                        $(".reportIdChkBox").prop('checked', true);
                    } else {
                        btn.style.display = "none";
                        $(".reportIdChkBox").prop('checked', false);
                    }

                    refreshDeleteIdStringValue();
                });

                $(".reportIdChkBox").click(function () {
                    // eslint-disable-next-line no-console
                    console.log('chkbox clicked');
                    const btn = document.getElementById("delete_select_btn");

                    const checkBoxArray = document.getElementsByClassName('reportIdChkBox');
                    let anychecked = false;

                    for (var index = 0; index < checkBoxArray.length; index++) {
                        if (checkBoxArray[index].checked) {
                            anychecked = checkBoxArray[index].checked;
                        }
                    }

                    if (anychecked) {
                        btn.style.display = "block";
                    } else {
                        btn.style.display = "none";
                    }

                    refreshDeleteIdStringValue();
                });

                /**
                 * refreshDeleteIdStringValue
                 */
                function refreshDeleteIdStringValue() {
                    const idArray = [];
                    const checkBoxArray = document.getElementsByClassName('reportIdChkBox');

                    for (var index = 0; index < checkBoxArray.length; index++) {
                        if (checkBoxArray[index].checked) {
                            idArray.push(checkBoxArray[index].value);
                        }
                    }

                    document.getElementById('deleteidstring').value = idArray.join();
                }

                return true;
            }
        };
    });
