$(document).ready(function () {
    $("a.delete-cate").click(function () {
        if (confirm("Are you sure want to delete this category ?")) {
            $id = $(this).attr('id-delete');

            $.ajax({
                url: "ajax_handler.php?cid=" + $id,
                type: 'GET',
                success: function (data) {
                    if (data.trim() === 'ok') {
                        $("tr#"+$id).remove();
                    }
                }
            });
        }
    });

    $("a.delete-post").click(function () {
        if (confirm("Are you sure want to delete this post ?")) {
            $id = $(this).attr('id-delete');
            $image = $(this).attr('name-delete');

            $.ajax({
                url: "ajax_handler.php?pid=" + $id + "&pimage=" + $image,
                type: 'GET',
                success: function (data) {
                    if (data.trim() === 'ok') {
                        $("tr#"+$id).remove();
                    }
                }
            });
        }
    });

    $("a.delete-user").click(function () {
        if (confirm("Are you sure want to delete this user ?")) {
            $id = $(this).attr('id-delete');

            $.ajax({
                url: "ajax_handler.php?uid=" + $id,
                type: 'GET',
                success: function (data) {
                    if (data.trim() === 'ok') {
                        $("tr#"+$id).remove();
                    }
                }
            });
        }
    });

    $("a.delete-comment").click(function () {
        if (confirm("Are you sure want to delete this comment ?")) {
            $id = $(this).attr('id-delete');

            $.ajax({
                url: "ajax_handler.php?cmid=" + $id,
                type: 'GET',
                success: function (data) {
                    if (data.trim() === 'ok') {
                        $("tr#"+$id).remove();
                    }
                }
            });
        }
    });
});