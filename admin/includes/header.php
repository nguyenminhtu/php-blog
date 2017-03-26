<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyCMS - <?php echo (isset($title)) ? $title : "Admin Page" ?></title>
    <link rel="stylesheet" href="libs/css/bootstrap.css">
    <link rel="stylesheet" href="libs/css/styles.css">
    <link rel="shortcut icon" href="../uploads/images/favicon.ico">
</head>
<body>

<nav class="navbar navbar-inverse" role="navigation">
    <div class="navbar-header">
        <a style="font-size: 23px;" class="navbar-brand" href="/admin/admin.php">MyCMS</a>
    </div>

    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav navbar-right">
            <li><a href="/" target="_blank">Home Site</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">User Profile</a></li>
                    <li><a href="logout.php">Log out</a></li>
                </ul>
            </li>
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>