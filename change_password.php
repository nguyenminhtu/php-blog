<?php
$title = "Change Password";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
if (!is_logged_in()) {
    redirect_to("login.php");
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    $trimmed = array_map('trim', $_POST);

    // kiem tra current password hop le
    if (preg_match('/^[\w.-]{5,30}$/', $trimmed['current_password'])) {
        $current_password = crypt($trimmed['current_password'], '$5$rounds=5000$anexamplestringforsalt$');
    } else {
        $errors[] = 'current password';
    }

    // kiem tra new password va confirm
    if (preg_match('/^[\w.-]{5,30}$/', $trimmed['new_password'])) {
        if ($trimmed['new_password'] == $trimmed['confirm_new_password']) {
            $new_password = crypt($trimmed['new_password'], '$5$rounds=5000$anexamplestringforsalt$');
        } else {
            $errors[] = "new password do not match";
        }
    } else {
        $errors[] = 'new password';
    }

    //kiem tra captcha
    if (!validate_captcha()) {
        $errors[] = 'captcha';
    }

    if (empty($errors)) {
        $q = "SELECT uname FROM users WHERE uid = {$_SESSION['uid']} AND upassword = '{$current_password}'";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) > 0) {
            $q1 = "UPDATE users SET upassword = '{$new_password}' WHERE uid = {$_SESSION['uid']} LIMIT 1";
            $r1 = mysqli_query($dbc, $q1);
            confirm_query($r1, $q1);

            if (mysqli_affected_rows($dbc) == 1) {
                $message = "<p class='text-center text-success'>Update password successfully !</p>";
            } else {
                $message = "<p class='text-center text-danger'>Could not update your password due to system error. Sorry for inconvenience !</p>";
            }
        } else {
            $message = "<p class='text-center text-danger'>Wrong password. Try again !</p>";
        }
    }
}
?>



<!-- main content -->
<div class="col-md-8" id="content">
    <h3 class="text-center">Change Password</h3>
    <?php
        if (isset($message)) {
            echo $message;
        }
    ?>
    <hr>
    <form action="" method="post" role="form" id="changepassword">

        <script type="text/javascript">
            function submitForm() {
                document.getElementById("changepassword").submit();
            }
        </script>

        <div class="form-group">
            <label for="">Current Password:</label>
            <?php
                if (isset($errors) && in_array('current password', $errors)) {
                    echo "<p class='text-danger'>Current password is invalid. Try again !</p>";
                }
            ?>
            <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Your current password..." required autofocus maxlength="30" tabindex="1">
        </div>

        <div class="form-group">
            <label for="">New Password:</label>
            <?php
            if (isset($errors) && in_array('new password', $errors)) {
                echo "<p class='text-danger'>New password is invalid. Password is between 5 and 30 character !</p>";
            }
            ?>
            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New password..." required maxlength="30" tabindex="2">
        </div>

        <div class="form-group">
            <label for="">Confirm New Password:</label>
            <?php
            if (isset($errors) && in_array('new password do not match', $errors)) {
                echo "<p class='text-danger'>Confirm password do not match. Try again !</p>";
            }
            ?>
            <input type="password" class="form-control" name="confirm_new_password" id="confirm_new_password" placeholder="Confirm new password..." required maxlength="30" tabindex="3">
        </div>

        <button
            class="g-recaptcha btn btn-primary"
            data-sitekey="6Lcr6xkUAAAAABwX7sZyo-kQt8v7tDKSlCn6OUF5"
            data-callback="submitForm">
            Save changes
        </button>
    </form>
</div> <!-- end content -->



<?php
include("includes/sidebar.php");
include("includes/footer.php");
?>
