<?php
$title = "User Profile";
require_once("includes/connect.php");
require_once("includes/functions.php");
include("includes/header.php");
if (!is_logged_in()) {
    redirect_to("login.php");
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_SESSION['uid'];
    if (isset($_POST['image'])) {
        if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            $errors1 = array();

            // danh sach dinh dang anh hop le
            $alowwed = array('image/jpg', 'image/jpeg', 'image/png');

            //kiem tra anh dc up len co hop le ko
            if (in_array(strtolower($_FILES['avatar']['type']), $alowwed)) {
                // tach lay phan extension
                $ext = end(explode('.', $_FILES['avatar']['name']));

                $image = uniqid(rand(), true) . "." . $ext;

                if ($_FILES['avatar']['error'] == 0) {
                    if ($_FILES['avatar']['size'] > 1024000 || $_FILES['avatar']['size'] > 8388608) {
                        $errors1[] = "exceed file size";
                    } else {
                        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], "uploads/avatars/".$image)) {
                            $message = "<p class='text-center text-danger'>Cannot upload image due to system error !</p>";
                        } else {
                            if ($_POST['old_image'] != 'no-avatar.png') {
                                if (unlink("uploads/avatars/".$_POST['old_image'])) {
                                    $q1 = "UPDATE users SET avatar = '{$image}' WHERE uid = {$uid} LIMIT 1";
                                    $r1 = mysqli_query($dbc, $q1);
                                    confirm_query($r1, $q1);

                                    if (mysqli_affected_rows($dbc) == 1) {
                                        $_SESSION['avatar'] = $image;
                                        $message = "<p class='text-center text-success'>Upload avatar successfully</p>";
                                    } else {
                                        $message = "<p class='text-center text-danger'>Cannot set avatar to user due to system error !</p>";
                                    }
                                } else {
                                    $message = "<p class='text-center text-danger'>Cannot set avatar to user due to system error !</p>";
                                }
                            } else {
                                $q1 = "UPDATE users SET avatar = '{$image}' WHERE uid = {$uid} LIMIT 1";
                                $r1 = mysqli_query($dbc, $q1);
                                confirm_query($r1, $q1);

                                if (mysqli_affected_rows($dbc) == 1) {
                                    $_SESSION['avatar'] = $image;
                                    $message = "<p class='text-center text-success'>Upload avatar successfully</p>";
                                } else {
                                    $message = "<p class='text-center text-danger'>Cannot set avatar to user due to system error !</p>";
                                }
                            }
                        }
                    }
                } else {
                    $errors1 = array_merge($errors1, check_error_upload($_FILES['avatar']['error']));
                }

            } else {
                $errors1[] = "type invalid";
            }

        }
    }


    if (isset($_POST['info'])) {
        // validate form user info
        $errors = array();

        $trimmed = array_map('trim', $_POST);

        //validate name
        if (isset($trimmed['name'])) {
            $name = mysqli_real_escape_string($dbc, $trimmed['name']);
        }

        //validate email
        if (preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$/', $trimmed['email'])) {
            $email = $trimmed['email'];
        } else {
            $errors[] = "email invalid";
        }

        if (empty($errors)) {
            $old_email = $trimmed['old_email'];

            // neu nguoi dung sua email thi moi kiem tra co trung trong csdl hay ko
            if ($old_email != $email) {
                // kiem tra email dc nhap vao xem da ton tai trong database chua
                $q = "SELECT uid FROM users WHERE uemail = '{$email}'";
                $r = mysqli_query($dbc, $q);
                confirm_query($r, $q);

                if (mysqli_num_rows($r) > 0) {
                    $message1 = "<p class='text-center text-danger'>Email is already used ! Try another !</p>";
                } else {
                    $q1 = "UPDATE users SET uemail = '{$email}', uname = '{$name}' WHERE uid = '{$_SESSION['uid']}' LIMIT 1";
                    $r1 = mysqli_query($dbc, $q1);
                    confirm_query($r1, $q1);

                    if (mysqli_affected_rows($dbc) == 1) {
                        $message1 = "<p class='text-center text-success'>Update successfully !</p>";
                    } else {
                        $message1 = "<p class='text-center text-danger'>Could not update user info due to system error !</p>";
                    }
                }
            } else {
                $q1 = "UPDATE users SET uname = '{$name}' WHERE uid = '{$_SESSION['uid']}' LIMIT 1";
                $r1 = mysqli_query($dbc, $q1);
                confirm_query($r1, $q1);

                if (mysqli_affected_rows($dbc) == 1) {
                    $_SESSION['uname'] = $name;
                    $message1 = "<p class='text-center text-success'>Update successfully !</p>";
                } else {
                    $message1 = "<p class='text-center text-danger'>Could not update user info due to system error !</p>";
                }
            }

        }
    }
}
?>



<div class="col-md-8" id="content">

    <?php
    $q = "SELECT uname, uemail, avatar FROM users WHERE uid = " . $_SESSION['uid'];
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_num_rows($r) == 1) {
        list($name, $email, $avatar) = mysqli_fetch_array($r, MYSQLI_NUM);
        ?>


        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="" method="post" role="form" enctype="multipart/form-data">
                    <legend class="page-header text-center">Avatar</legend>
                    <?php
                    if (isset($message)) {
                        echo $message;
                    }
                    if (!empty($errors1)) {
                        for ($i = 0; $i < count($errors1); $i ++) {
                            echo "<p class='text-danger'>{$errors1[0]}</p>";
                        }
                    }
                    ?>

                    <div class="form-group">
                        <img src="../uploads/avatars/<?php echo $avatar; ?>" alt="" class="img-responsive" width="200" height="196">
                        <div class="">
                            <input type="file" name="avatar" class="form-control">
                            <input type="hidden" name="old_image" value="<?php echo $avatar; ?>">
                            <input type="hidden" name="MAX_FILE_SIZE" value="1024000">
                            <p style="margin-top: 5px;"><button class="btn btn-primary" type="submit" name="image">Update</button></p>
                        </div>
                    </div>

                </form>
            </div>
        </div>



        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="" method="post" role="form" enctype="multipart/form-data">
                    <legend class="page-header text-center">Info</legend>
                    <?php
                    if (isset($message1)) {
                        echo $message1;
                    }
                    ?>

                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required maxlength="50" value="<?php echo $name; ?>">
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <?php
                            if (isset($errors) && in_array('email invalid', $errors)) {
                                echo "<p class='text-danger'>Email is invalid</p>";
                            }
                        ?>
                        <input type="hidden" name="old_email" value="<?php echo $email; ?>">
                        <input type="email" class="form-control" name="email" id="email" required maxlength="50" value="<?php echo $email; ?>">
                    </div>

                    <button class="btn btn-primary" type="submit" name="info">Update</button>

                </form>
            </div>
        </div>


        <?php
    }
    ?>

</div>




<?php
include("includes/sidebar.php");
include("includes/footer.php");
?>
