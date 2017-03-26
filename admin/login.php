<?php
session_start();
include("includes/connect.php");
include("includes/functions.php");
?>


<?php
if (isset($_SESSION['ulevel']) && $_SESSION['ulevel'] == 2) {
    redirect_to();
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // chay tat ca $_POST qua ham trim
    $trimmed = array_map('trim', $_POST);

    if (isset($trimmed['username']) && preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$/', $trimmed['username'])) {
        $username = mysqli_real_escape_string($dbc, $trimmed['username']);
    } else {
        $error = "<p class='text-center text-danger'>Cannot log in to system</p>";
    }

    if (isset($trimmed['password']) && preg_match('/^[\w.-]{5,30}$/', $trimmed['password'])) {
        $password = crypt($trimmed['password'], '$5$rounds=5000$anexamplestringforsalt$');
    } else {
        $error = "<p class='text-center text-danger'>Cannot log in to system</p>";
    }

    if (empty($error)) {
        $q = "SELECT uid, uname, ulevel, avatar FROM users WHERE uemail = '{$username}' AND upassword = '{$password}'";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) == 1) {
            list($uid, $uname, $ulevel, $avatar) = mysqli_fetch_array($r, MYSQLI_NUM);
            if ($ulevel == 2) {
                $_SESSION['uid'] = $uid;
                $_SESSION['uname'] = $uname;
                $_SESSION['ulevel'] = $ulevel;
                $_SESSION['avatar'] = $avatar;
                redirect_to();
            } else {
                $message = "<p class='text-center text-danger'>Cannot log in to system !</p>";
            }
        } else {
            $message = "<p class='text-center text-danger'>Wrong credentials !</p>";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyCMS - Login</title>
    <link rel="stylesheet" href="libs/css/bootstrap.css">
    <link rel="shortcut icon" href="../uploads/images/favicon.ico">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3" style="margin-top: 100px;">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center" style="text-transform: uppercase;">Login to admin dashboard</h3>
                    <?php
                        if (isset($message)) {
                            echo $message;
                        }
                        if (isset($error)) {
                            echo $error;
                        }
                    ?>
                </div>
                <div class="panel-body">
                    <form action="" method="post" role="form">
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" class="form-control" name="username" id="username" tabindex="1" maxlength="30" required>
                        </div>

                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password" id="password" tabindex="2" maxlength="30" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script src="libs/js/jquery-1.12.4.js"></script>
<script src="libs/js/bootstrap.js"></script>
</html>
