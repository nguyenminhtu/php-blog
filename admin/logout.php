<?php
session_start();
require_once("includes/connect.php");
require_once("includes/functions.php");

isAdmin();

session_destroy();
$_SESSION = array();
setcookie(session_name(), '', time() - 36000);
redirect_to("");
?>