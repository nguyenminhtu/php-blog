<?php
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
?>



<?php
if (isset($_GET['x'], $_GET['y']) && preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$/', $_GET['x']) && strlen($_GET['y']) == 32) {
    $email = $_GET['x'];
    $token = $_GET['y'];

    $q = "UPDATE users SET uactive = NULL WHERE uemail = '{$email}' AND uactive = '{$token}' LIMIT 1";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_affected_rows($dbc) == 1) {
        echo "<p class='text-center text-success'>Your account has been activated successfully ! <a href='login.php'>Login now</a></p>";
    } else {
        echo "<p class='text-center text-danger'>Your account could not be activated. Please try again !</p>";
    }
} else {
    redirect_to();
}
?>



<?php
include("includes/footer.php");
?>
