<?php
$title = "Login Page";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
if (is_logged_in()) {
    redirect_to();
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    $trimmed = array_map('trim', $_POST);

    if (preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$/', $trimmed['email'])) {
        $email = $trimmed['email'];
    } else {
        $errors[] = 'email invalid';
    }

    if (preg_match('/^[\w.-]{5,30}$/', $trimmed['password'])) {
        $password = crypt($trimmed['password'], '$5$rounds=5000$anexamplestringforsalt$');
    } else {
        $errors[] = "password invalid";
    }

    //validate captcha
    if (!validate_captcha()) {
        $errors[] = "captcha";
    }

    if (empty($errors) && count($errors) == 0) {
        $q = "SELECT uid, uname, ulevel, avatar, upassword, uactive FROM users WHERE uemail = '{$email}'";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) > 0) {
            list($uid, $uname, $ulevel, $avatar, $up, $uactive) = mysqli_fetch_array($r, MYSQLI_NUM);

            if (($up == $password) && !$uactive) {
                session_regenerate_id();

                //neu tim thay thi chuyen qua trang index va tao session luu thong tin user
                $_SESSION['uid'] = $uid;
                $_SESSION['uname'] = $uname;
                $_SESSION['ulevel'] = $ulevel;
                $_SESSION['avatar'] = $avatar;
                redirect_to();
            } else {
                $message = "<p class='text-center text-danger'>Email or password do not match. Or your account is not actived. Check your email !</p>";
            }
        } else {
            $message = "<p class='text-center text-danger'>Email or password do not match. Or your account is not actived. Check your email !</p>";
        }
        
    }
}
?>



<!-- main content -->
<div class="col-md-6 col-md-offset-3" id="content">
    <h3 class="text-center">Login to use full feature</h3>
    <?php
        if (isset($message)) {
            echo $message;
        }
    ?>
    <form action="" method="post" role="form" id="loginForm">

        <script type="text/javascript">
            function submitForm() {
                document.getElementById("loginForm").submit();
            }
        </script>

        <div class="form-group">
            <label for="">Email</label>
            <?php
                if (isset($errors) && in_array('email invalid', $errors)) {
                    echo "<p class='text-danger'>The email is invalid !</p>";
                }
            ?>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email..." required autofocus tabindex="1" maxlength="30">
        </div>

        <div class="form-group">
            <label for="">Password</label>
            <?php
            if (isset($errors) && in_array('password invalid', $errors)) {
                echo "<p class='text-danger'>The password is invalid !</p>";
            }
            ?>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password..." required tabindex="2" maxlength="30">
        </div>

        <button
            class="g-recaptcha btn btn-primary"
            data-sitekey="6Lcr6xkUAAAAABwX7sZyo-kQt8v7tDKSlCn6OUF5"
            data-callback="submitForm">
            Log in
        </button>
        <a href="retrieve_password.php" class="pull-right">Forgot password ?</a>
    </form>
</div> <!-- end content -->



<?php
include("includes/footer.php");
?>
