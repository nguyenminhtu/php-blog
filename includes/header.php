<?php
session_start();
require_once("includes/connect.php");
require_once("includes/functions.php");
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyCMS - <?php echo (isset($title)) ? $title : 'Homepage' ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="libs/css/style.css">
    <link rel="shortcut icon" href="uploads/images/favicon.ico">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>

<div id="main">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/" style="font-size: 28px;">MyCMS</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                </ul>
                <form class="navbar-form navbar-left" role="search" method="GET" action="search.php">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search..." name="q">
                    </div>
                    <button type="submit" class="btn btn-default">Search</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <?php

                        // neu la admin
                        if (isset($_SESSION['ulevel']) && $_SESSION['ulevel'] == 2 && isset($_SESSION['avatar'])) {
                            echo "
                                <li><a href='admin/admin.php'>Admin CP</a></li>
                                <li class=\"dropdown\">
                                    <a href='' class='dropdown-toggle' data-toggle='dropdown'>
                                        <img id='avatar' src='uploads/avatars/{$_SESSION['avatar']}' alt='' class='img-responsive img-circle pull-left'> 
                                        <span class='pull-right'>
                                            {$_SESSION['uname']}
                                            <b class='caret'></b>
                                        </span> 
                                        <span class='clearfix'></span> 
                                    </a>
                                    <ul class=\"dropdown-menu\">
                                        <li><a href='profile.php'>Profile</a></li>
                                        <li><a href='change_password.php'>Change password</a></li>
                                        <li><a href='logout.php'>Log out</a></li>
                                    </ul>
                                </li>
                            ";
                        }

                        else if (isset($_SESSION['avatar'])) {  // neu la user bt
                            echo "
                                <li class='dropdown'>
                                    <a href='' class='dropdown-toggle' data-toggle='dropdown'>
                                        <img id='avatar' src='uploads/avatars/{$_SESSION['avatar']}' alt='' class='img-responsive img-circle pull-left'> 
                                        <span class='pull-right'>
                                            {$_SESSION['uname']}
                                            <b class='caret'></b>
                                        </span> 
                                        <span class='clearfix'></span> 
                                    </a>
                                    <ul class='dropdown-menu'>
                                        <li ><a href = 'profile.php' > Profile</a ></li >
                                        <li><a href='change_password.php'>Change password</a></li>
                                        <li ><a href = 'logout.php' > Log out </a ></li >
                                    </ul >
                                </li >
                            ";
                        }

                        else {  // neu chua dang ki
                            echo "
                                <li><a href='register.php'>Register</a></li>
                                <li><a href='login.php'>Log in</a></li>
                            ";
                        }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div>
    </nav>

    <div class="jumbotron">
        <img src="uploads/images/banner.jpg" alt="" class="img-responsive">
    </div>

    <div class="container">
        <div class="row">