<?php
$title = "Admin Profile";
require_once("includes/connect.php");
require_once("includes/functions.php");
include("includes/header.php");
include("includes/sidebar.php");
isAdmin();
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['image'])) {
        if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            $errors = array();

            // danh sach dinh dang anh hop le
            $alowwed = array('image/jpg', 'image/jpeg', 'image/png');

            //kiem tra anh dc up len co hop le ko
            if (in_array(strtolower($_FILES['avatar']['type']), $alowwed)) {
                // tach lay phan extension
                $ext = end(explode('.', $_FILES['avatar']['name']));

                $image = uniqid(rand(), true) . "." . $ext;

                if (!move_uploaded_file($_FILES['avatar']['tmp_name'], "../uploads/avatars/".$image)) {
                    $message = "<p class='text-center text-danger'>Cannot upload image due to system error !</p>";
                } else {
                    if (unlink("../uploads/avatars/".$_POST['old_image'])) {
                        $q1 = "UPDATE users SET avatar = '{$image}' WHERE ulevel = 2 LIMIT 1";
                        $r1 = mysqli_query($dbc, $q1);
                        confirm_query($r1, $q1);

                        if (mysqli_affected_rows($dbc) == 1) {
                            $message = "<p class='text-center text-success'>Upload avatar successfully</p>";
                        } else {
                            $message = "<p class='text-center text-danger'>Cannot set avatar to user due to system error !</p>";
                        }
                    } else {
                        $message = "<p class='text-center text-danger'>Cannot set avatar to user due to system error !</p>";
                    }
                }
            } else {
                $errors[] = "type invalid";
            }

        }
    }


    if (isset($_POST['info'])) {
        // validate form user info
        $errors = array();

        $trimmed = array_map('trim', $_POST);

        //validate name
        if (isset($trimmed['uname'])) {
            $name = mysqli_real_escape_string($dbc, $trimmed['uname']);
        }

        //validate email
        if (preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$/', $trimmed['uemail'])) {
            $email = $trimmed['uemail'];
        } else {
            $errors[] = "email invalid";
        }

        if (empty($errors)) {
            $old_email = $trimmed['old_email'];

            // kiem tra email dc nhap vao xem da ton tai trong database chua
            $q = "SELECT uid FROM users WHERE uemail = '{$email}' AND uemail != '{$old_email}'";
            $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);

            if (mysqli_num_rows($r) > 0) {
                $message1 = "<p class='text-center text-danger'>Email is already used ! Try another !</p>";
            } else {
                $q1 = "UPDATE users SET uemail = '{$email}', uname = '{$name}' WHERE ulevel = 2 LIMIT 1";
                $r1 = mysqli_query($dbc, $q1);
                confirm_query($r1, $q1);

                if (mysqli_affected_rows($dbc) == 1) {
                    $message1 = "<p class='text-center text-success'>Update successfully !</p>";
                } else {
                    $message1 = "<p class='text-center text-danger'>Could not update user info due to system error !</p>";
                }
            }
        }
    }
}
?>



<div class="col-md-10">

<?php
$q = "SELECT uname, uemail, upassword, avatar FROM users WHERE ulevel = 2";
$r = mysqli_query($dbc, $q);
confirm_query($r, $q);

if (mysqli_num_rows($r) == 1) {
    list($uname, $uemail, $upassword, $avatar) = mysqli_fetch_array($r, MYSQLI_NUM);
?>


    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form action="" method="post" role="form" enctype="multipart/form-data">
                <legend class="page-header text-center">Avatar</legend>
                <?php
                if (isset($message)) {
                    echo $message;
                }
                ?>

                <div class="form-group">
                    <img src="../uploads/avatars/<?php echo $avatar; ?>" alt="" class="img-responsive pull-left" width="200" height="196">
                    <div class="pull-right">
                        <input type="file" name="avatar" class="form-control">
                        <input type="hidden" name="old_image" value="<?php echo $avatar; ?>">
                        <p style="margin-top: 5px;"><button class="btn btn-primary" type="submit" name="image">Update</button></p>
                    </div>
                    <div class="clearfix"></div>
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
                    <input type="text" class="form-control" name="uname" id="uname" required maxlength="50" value="<?php echo $uname; ?>">
                </div>

                <div class="form-group">
                    <label for="">Email</label>
                    <input type="hidden" name="old_email" value="<?php echo $uemail; ?>">
                    <input type="email" class="form-control" name="uemail" id="uemail" required maxlength="50" value="<?php echo $uemail; ?>">
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
include("includes/footer.php");
?>
