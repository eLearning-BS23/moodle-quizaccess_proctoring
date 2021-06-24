define(['jquery', 'core/ajax', 'core/notification'],
    function() {
    return {
        setup: function(props) {
            // Console.log("test delete btn js done");
            var pathName = window.location.href;
            // Console.log("pathname:", pathName);
            if (pathName.includes("/admin/settings.php?section=modsettingsquizcatproctoring")) {
                // Console.log("found right path. try adding");
                var div = document.createElement('div');
                div.id = 'deletebtndiv';
                div.className = 'row';
                div.style.marginTop = '10px';

                var labelcontainer = document.createElement('div');
                labelcontainer.className = 'form-label col-sm-3 text-sm-right';

                var deleteALLLabel = document.createTextNode(props.formlabel);
                labelcontainer.appendChild(deleteALLLabel);

                var btnContainer = document.createElement('div');
                btnContainer.className = 'form-label col-sm-3 text-sm-left';

                var confirmmsg = props.deleteconfirm;

                var confirmIt = function(e) {
                    if (!confirm(confirmmsg)) {
                     e.preventDefault();
                    }
                };

                var btntag = document.createElement("a");
                var text = document.createTextNode(props.btnlabel);
                btntag.className = 'btn btn-warning';
                btntag.href = props.pageurl;
                btntag.appendChild(text);
                btntag.addEventListener('click', confirmIt, false);


                btnContainer.appendChild(btntag);
                div.appendChild(labelcontainer);
                div.appendChild(btnContainer);
                var adminforms = document.getElementsByClassName("settingsform");
                if (adminforms.length > 0) {
                    adminforms[0].appendChild(div);
                }

                // Document.getElementById('adminsettings').appendChild(div);
                // Console.log("adding complete");
            }
            return true;
        }
    };
});
