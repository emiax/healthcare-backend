$(document).ready(function () {

    $('#send').click(function() {
        connection.request({
            action: $('#action').val(),
            args: JSON.parse($('#args').val()),
            callback: function(data) {
                $('#response').html('<pre>' + JSON.stringify(data) + '</pre>');
            }
        });
    });
    

});