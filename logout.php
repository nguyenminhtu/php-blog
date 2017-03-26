<?php
session_start();
require_once("includes/connect.php");
require_once("includes/functions.php");
if (!is_logged_in()) {
    redirect_to();
} else {
    session_destroy();
    $_SESSION = array();
    setcookie(session_name(), '', time() - 36000);
    redirect_to("login.php");
}
?>