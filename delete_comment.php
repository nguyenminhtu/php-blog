<?php
require_once("includes/connect.php");
require_once("includes/functions.php");
?>

<?php
if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
    $id = $_POST['id'];


    $q = "DELETE FROM comments WHERE cmid = {$id} LIMIT 1";
    $r = mysqli_query($dbc, $q);

    confirm_query($r, $q);

    if (mysqli_affected_rows($dbc) == 1) {
        echo "ok";
    } else {
        echo "fail";
    }
}
?>

