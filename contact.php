<?php
$title = "Contact Page";
require_once("includes/PHPMailer/PHPMailerAutoload.php");
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
?>


<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = array();

        $clean = array_map('clean_email', $_POST);

        if (isset($clean['name'])) {
            $name = $clean['name'];
        }

        if (isset($clean['email']) && preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$/', $clean['email'])) {
            $email = $clean['email'];
        } else {
            $errors[] = "email invalid";
        }

        if (isset($clean['mmessage'])) {
            $message = $clean['mmessage'];
        }

        if (!validate_captcha()) {
            $errors[] = "captcha";
        }

        if (empty($errors)) {
            $body = "Name: {$name} <br> Email: {$email} <br><br> Message: <br>".strip_tags($message);
            $body = wordwrap($body, 70);

            $message = sendmail('tuunguyen2795@gmail.com', $body, "Tin nhắn của bạn đã được gửi tới admin. Bạn sẽ sớm nhân đc reply !");
        }
    }
?>



<!-- main content -->
<div class="col-md-8" id="content">
    <div class="well">
        <?php
            if (isset($message)) {
                echo $message;
            }
        ?>
        <p class="page-header" style="font-size: 29px; margin-top: 10px;">Contact with Admin</p>
        <p style="font-size: 18px">Want to get in touch with me? Fill out the form below to send me a message and I will try to get back to you within 24 hours!</p>
        <form action="" method="post" role="form" novalidate id="myform">

        <script>
            function submitForm() {
                document.getElementById("myform").submit();
            }
        </script>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Name..." autofocus required tabindex="1" value="<?php echo (isset($_POST['name'])) ? $_POST['name'] : '' ?>">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <?php
                    if (isset($errors) && in_array('email invalid', $errors)) {
                        echo "<p class='text-danger'>Email is invalid !</p>";
                    }
                ?>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email Address..." required tabindex="2" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : '' ?>">
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="mmessage" id="mmessage" class="form-control" rows="6" placeholder="Message..." required tabindex="3"><?php
                    echo (isset($_POST['mmessage'])) ? htmlentities($_POST['mmessage'], ENT_COMPAT, 'UTF-8') : ''
                    ?></textarea>
            </div>

            <button
                class="g-recaptcha btn btn-primary"
                data-sitekey="6Lcr6xkUAAAAABwX7sZyo-kQt8v7tDKSlCn6OUF5"
                data-callback="submitForm">
                Send
            </button>
        </form>
    </div>
</div> <!-- end content -->



<?php
include("includes/sidebar.php");
include("includes/footer.php");
?>
