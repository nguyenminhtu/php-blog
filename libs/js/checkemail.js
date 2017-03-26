$(document).ready(function () {
	$("#email_register").blur(function () {
        $("#result_check").html("Checking availability...");
        $.ajax({
            url: 'ajax_handler.php?email=' + $(this).val(),
            type: 'GET',
            success: function (data) {
                if (data.trim() === 'match') {
                    $("#email_register").focus();
                    $("#result_check").removeClass("text-success").html("Email is already used").addClass("text-danger");
                } else {
                    $("#result_check").removeClass("text-danger").html("Email is available").addClass("text-success");
                }
            }
        });
    });
});