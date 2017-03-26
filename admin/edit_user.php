<?php
$title="Edit User";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
include("includes/sidebar.php");
isAdmin();
?>



<?php
if (isset($_GET['uid']) && filter_var($_GET['uid'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
    $uid = $_GET['uid'];

    $q = "SELECT uname, uemail, ulevel FROM users WHERE uid = {$uid}";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_num_rows($r) == 1) {
        list($uname, $uemail, $ulevel) = mysqli_fetch_array($r, MYSQLI_NUM);
    } else {
        echo "<h4 class='text-center text-danger'>This user does not exits !</h4>";
    }
} else {
    redirect_to('admin/users.php');
}
?>



<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    $trimmed = array_map('trim', $_POST);

    if (filter_var($trimmed['ulevel'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $ulevel = $trimmed['ulevel'];
    } else {
        $errors[] = "error";
    }

    // neu ko co loi thi submit form
    if (empty($errors)) {
        $q = "UPDATE users SET ulevel = {$ulevel} WHERE uid = {$uid} LIMIT 1";

        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_affected_rows($dbc) == 1) {
            $message = "<p class='text-center text-success'>Update user successfully !</p>";
            $_POST = array();
        } else {
            $message = "<p class='text-center text-danger'>Could not update user due to system error !</p>";
        }
    }
}
?>



<div class="col-md-10">
    <h3 class="text-center">Edit user <small><?php echo (isset($uname)) ? $uname : '' ?></small></h3>
    <?php
        if (isset($message)) {
            echo $message;
        }
    ?>
    <form action="" method="post" role="form">
        <div class="form-group">
            <label for="">Name:</label>
            <input type="text" disabled class="form-control" value="<?php echo (isset($uname)) ? $uname : '' ?>">
        </div>

        <div class="form-group">
            <label for="">Email:</label>
            <input type="text" disabled class="form-control" value="<?php echo (isset($uemail)) ? $uemail : '' ?>">
        </div>

        <div class="form-group">
            <label for="">User level:</label>
            <select name="ulevel" id="ulevel" class="form-control" tabindex="3">
                <option <?php echo (isset($ulevel) && ($ulevel == 2)) ? 'selected' : '' ?> value="2">Admin</option>
                <option <?php echo (isset($ulevel) && ($ulevel == 1)) ? 'selected' : '' ?> value="1">User</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>



<?php
include("includes/footer.php");
?>
