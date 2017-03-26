$(document).ready(function () {
	$(".delete-comment").click(function () {
        if (confirm("Are u sure want to delete this comment ?")) {
            var id = $(this).attr('id-delete');

            $.ajax({
                url: 'delete_comment.php',
                type: 'POST',
                data: {id: id},
                success: function (data) {
                    if (data.trim() === 'ok') {
                        $("#" + id).slideUp(300, function () {
                            $("#" + id).remove();
                        });
                    }
                }
            });
        }
    });
});