$(document).ready(function () {
    $("#link").click(function () {
        $.ajax({

            url: "http://testzf/default/test/list/page/1",
            type: "POST",
            data: "format=json",
            async: false,
            success: function(response) {

                alert(JSON.stringify(response));
            }
        });
    });
});