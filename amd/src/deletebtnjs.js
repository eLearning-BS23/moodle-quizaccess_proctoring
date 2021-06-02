define(['jquery', 'core/ajax', 'core/notification'],
    function($, Ajax, Notification) {
    return {
        setup: function(props) {
            // console.log("test delete btn js done");
            var pathName = window.location.href;
            // console.log("pathname:", pathName);
            if (pathName.includes("/admin/settings.php?section=modsettingsquizcatproctoring")) {
                // console.log("found right path. try adding");
                var div = document.createElement('div');
                div.id = 'container';
                div.className = 'deletebtndiv';

                var btntag = document.createElement("a");
                var text = document.createTextNode(props.btnlabel);
                btntag.className = 'btn btn-primary';
                btntag.href = props.pageurl;
                btntag.appendChild(text);
                div.appendChild(btntag);

                document.getElementById('adminsettings').appendChild(div);
                // console.log("adding complete");
            }
            return true;
        }
    };
});
