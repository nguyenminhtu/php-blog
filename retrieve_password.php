<?php
require_once("includes/PHPMailer/PHPMailerAutoload.php");
$title = "Forgot Password";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    $trimmed = array_map('trim', $_POST);

    // kiem tra current password hop le
    if (preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$/', $trimmed['email'])) {
        $email = $trimmed['email'];
    } else {
        $errors[] = 'email';
    }

    //kiem tra captcha
    if (!validate_captcha()) {
        $errors[] = 'captcha';
    }

    if (empty($errors)) {
        $q = "SELECT uid FROM users WHERE uemail = '{$email}'";
        $r = mysqli_query($dbc, $q);

        confirm_query($r, $q);

        if (mysqli_num_rows($r) == 1) {
            list($uid) = mysqli_fetch_array($r, MYSQLI_NUM);

            $temp = substr(uniqid(rand(), true), 3, 6);

            $pass = crypt($temp, '$5$rounds=5000$anexamplestringforsalt$');

            $q1 = "UPDATE users SET upassword = '{$pass}' WHERE uid = {$uid} LIMIT 1";
            $r1 = mysqli_query($dbc, $q1);

            confirm_query($r1, $q1);

            if (mysqli_affected_rows($dbc) == 1) {
                $body = "Your password has been temporarily changed to '{$temp}'. Please use this email address and the new password to login to website. You can change this password later !";
                $body = wordwrap($body, 70);

                $message = sendmail($email, $body, "Update your password successfully ! You can check your email to know your temp password !");
            }
        } else {
            $message = "<p class='text-center text-danger'>Your email is not exist on out database !</p>";
        }
    }
}
?>



<!-- main content -->
<div class="col-md-8" id="content">
    <h3 class="text-center">Retrieve Password</h3>
    <?php
    if (isset($message)) {
        echo $message;
    }
    ?>
    <hr>
    <form action="" method="post" role="form" id="retrieveForm">

        <script type="text/javascript">
            function submitForm() {
                document.getElementById("retrieveForm").submit();
            }
        </script>

        <div class="form-group">
            <label for="">Your Email:</label>
            <?php
            if (isset($errors) && in_array('email', $errors)) {
                echo "<p class='text-danger'>Email is invalid !</p>";
            }
            ?>
            <input type="email" class="form-control" name="email" id="email" placeholder="Your email..." required autofocus maxlength="40" tabindex="1">
        </div>

        <button
            class="g-recaptcha btn btn-primary"
            data-sitekey="6Lcr6xkUAAAAABwX7sZyo-kQt8v7tDKSlCn6OUF5"
            data-callback="submitForm">
            Retrieve
        </button>
    </form>
</div> <!-- end content -->



<?php
include("includes/sidebar.php");
include("includes/footer.php");
?>
