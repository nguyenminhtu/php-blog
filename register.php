<?php
require_once('includes/PHPMailer/PHPMailerAutoload.php');
$title = "Register Page";
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

        // trim het tat ca de loai bo dau cach
        $trimmed = array_map('trim', $_POST);

        // validate name
        if (!empty($trimmed['name'])) {
            $name = mysqli_real_escape_string($dbc, $trimmed['name']);
        }

        // validate email
        if (preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$/', $trimmed['email'])) {
            $email = $trimmed['email'];
        } else {
            $errors[] = "email invalid";
        }

        if (preg_match('/^[\w.-]{5,30}$/', $trimmed['password']) && preg_match('/^[\w.-]{5,30}$/', $trimmed['password2'])) {
            if ($trimmed['password'] == $trimmed['password2']) {
                $password = crypt($trimmed['password'], '$5$rounds=5000$anexamplestringforsalt$');
            } else {
                $errors[] = "password do not match";
            }
        } else {
            $errors[] = "password invalid";
        }

        if (!validate_captcha()) {
            $errors[] = "captcha";
        }

        // neu ko co loi thi submit form
        if (empty($errors)) {
            $q = "SELECT uid FROM users WHERE uemail = '{$email}'";
            $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);

            if (mysqli_num_rows($r) == 0) {
                // tao ra activation key
                $key = md5(uniqid(rand(), true));

                // insert user vao database cung vs key
                $q1 = "INSERT INTO users (uname, uemail, upassword, avatar, ulevel, uactive, udate) VALUES";
                $q1 .= " ('{$name}', '{$email}', '{$password}', 'no-avatar.png', 1, '{$key}', NOW())";

                $r1 = mysqli_query($dbc, $q1);
                confirm_query($r1, $q1);

                if (mysqli_affected_rows($dbc) == 1) {
                    $body = "Cảm ơn bạn đã đăng kí thành viên. Phiền bạn click vào đường link để kích hoạt tài khoản <br><br>";
                    $body .= BASE_URL . "active.php?x=" . urlencode($email) . "&y=" . $key;

                    $message = sendmail($email, $body, "Your password has been changed successfully. You will receive an email with new password !");
                } else {
                    $message = "<p class='text-center text-danger'>Cannot register due to system error. Try again !</p>";
                }
            } else {
                $message = "<p class='text-center text-danger'>The email is already used. Check again !</p>";
            }
        }
    }
?>



<!-- main content -->
<div class="col-md-6 col-md-offset-3" id="content">
    <h3 class="text-center">Register to login</h3>
    <?php
        if (isset($message)) {
            echo $message;
        }
    ?>
    <form action="" method="post" role="form" id="registerForm">

        <script type="text/javascript">
            function submitForm() {
                document.getElementById("registerForm").submit();
            }
        </script>

        <div class="form-group">
            <label for="">Full name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Full name..." required autofocus tabindex="1" maxlength="60" value="<?php echo (isset($_POST['name'])) ? $_POST['name'] : '' ?>">
        </div>

        <div class="form-group">
            <label for="">Email</label>
            <?php
            if (isset($errors) && in_array('email invalid', $errors)) {
                echo "<p class='text-danger'>The email is invalid !</p>";
            }
            ?>
            <input type="email" class="form-control" name="email" id="email_register" placeholder="Email..." required tabindex="2" maxlength="30" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : '' ?>">
            <p id="result_check"></p>
        </div>

        <div class="form-group">
            <label for="">Password</label>
            <?php
            if (isset($errors) && in_array('password invalid', $errors)) {
                echo "<p class='text-danger'>The password is between 5 and 30 character !</p>";
            }
            ?>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password..." required tabindex="3" maxlength="30">
        </div>

        <div class="form-group">
            <label for="">Confirm password</label>
            <?php
            if (isset($errors) && in_array('password do not match', $errors)) {
                echo "<p class='text-danger'>Two password is not match !</p>";
            }
            ?>
            <input type="password" class="form-control" name="password2" id="password2" placeholder="Confirm password..." required tabindex="4" maxlength="30">
        </div>

        <button
            class="g-recaptcha btn btn-primary"
            data-sitekey="6Lcr6xkUAAAAABwX7sZyo-kQt8v7tDKSlCn6OUF5"
            data-callback="submitForm">
            Register
        </button>
    </form>
</div> <!-- end content -->



<?php
include("includes/footer.php");
?>
