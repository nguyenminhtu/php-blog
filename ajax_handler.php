<?php
require_once("includes/connect.php");
require_once("includes/functions.php");

if (isset($_GET['email']) && preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$/', $_GET['email'])) {
    $email = $_GET['email'];
    $q = "SELECT uid FROM users WHERE uemail = '{$email}'";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_num_rows($r) == 1) {
        echo "match";
    } else {
        echo "not match";
    }
} else {
    echo "match";
}
?>