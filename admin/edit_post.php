<?php
$title="Edit Post";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
include("includes/sidebar.php");
isAdmin();
?>


<?php // kiem tra pid truyen vao
if (isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
    $pid = $_GET['pid'];

    $q = "SELECT p.ptitle, p.pcontent, p.pimage, p.cid, p.porder, p.pstatus FROM posts AS p WHERE pid = {$pid}";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_num_rows($r) == 1) {
        list($ptitle, $pcontent, $pimage, $pcid, $porder, $pstatus) = mysqli_fetch_array($r, MYSQLI_NUM);
    } else {
        echo "<h4 class='text-center text-danger'>This post does not exits !</h4>";
    }
} else {
    redirect_to('admin/posts.php');
}
?>



<?php // kiem tra khi form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    $trimmed = array_map('trim', $_POST);

    // title
    $ptitle = mysqli_real_escape_string($dbc, $trimmed['ptitle']);

    // category
    if (filter_var($trimmed['cid'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $cid = $trimmed['cid'];
    } else {
        $errors[] = 'cid invalid';
    }

    //content
    if (!empty($trimmed['pcontent'])) {
        $pcontent = mysqli_real_escape_string($dbc, $trimmed['pcontent']);
    } else {
        $errors[] = 'content';
    }

    // image
    if (file_exists($_FILES['pimage']['tmp_name']) || is_uploaded_file($_FILES['pimage']['tmp_name'])) {
        // tao array dinh dang anh cho phep
        $allowed = array('image/jpeg', 'image/jpg', 'image/png');

        // kiem tra anh upload co nam trong dinh dang cho phep ko
        if (in_array(strtolower($_FILES['pimage']['type']), $allowed)) {
            // tach lay phan extension cua file
            $ext = end(explode('.', $_FILES['pimage']['name']));

            // dat lai ten cho anh
            $pimage = uniqid(rand(), true) . '.' . $ext;

            if (unlink("../uploads/images/".$trimmed['old_image'])) {
                if (!move_uploaded_file($_FILES['pimage']['tmp_name'], "../uploads/images/".$pimage)) {
                    $errors[] = "upload failed";
                }
            }
        } else {
            $errors[] = 'wrong format';
        }

        // kiem tra loi
        if ($_FILES['pimage']['error'] > 0) {
            $err = $_FILES['pimage']['error'];

            $errors = array_merge($errors, check_error_upload($err));
        } // end if error
    } else {
        $pimage = $trimmed['old_image'];
    } // end check upload image

    // order
    if (filter_var($trimmed['porder'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $porder = $trimmed['porder'];
    } else {
        $errors[] = 'porder invalid';
    }

    //status
    if (filter_var($trimmed['pstatus'], FILTER_VALIDATE_INT)) {
        $pstatus = $trimmed['pstatus'];
    } else {
        $errors[] = 'pstatus invalid';
    }

    // neu ko co loi thi submit form
    if (empty($errors)) {
        $q = "UPDATE posts SET cid = {$cid}, ptitle = '{$ptitle}', pcontent = '{$pcontent}', pimage = '{$pimage}', porder = {$porder}, pstatus = {$pstatus} WHERE pid = {$pid} LIMIT 1";

        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_affected_rows($dbc) == 1) {
            $message = "<p class='text-center text-success'>Update post successfully !</p>";
            $_POST = array();
        } else {
            $message = "<p class='text-center text-danger'>Could not update post to database due to system error !</p>";
        }
    }
}
?>


<div class="col-md-10">
    <h2 class="text-center">Edit post <small><?php echo (isset($ptitle)) ? $ptitle : '' ?></small></h2>
    <?php
        if (isset($message)) {
            echo $message;
        }
    ?>
    <br>
    <form action="" method="post" role="form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="">Post title:</label>
            <input type="text" class="form-control" name="ptitle" id="ptitle" value="<?php echo (isset($ptitle)) ? $ptitle : '' ?>" autofocus tabindex="1" maxlength="150" required>
        </div>

        <div class="form-group">
            <label for="">Category:</label>
            <?php
            if (isset($errors) && in_array('cid invalid', $errors)) {
                echo "<p class='text-danger'>Category invalid !</p>";
            }
            ?>
            <select name="cid" id="cid" class="form-control" tabindex="2">
                <?php
                $q = "SELECT cid, cname FROM categories";
                $r = mysqli_query($dbc, $q);
                confirm_query($r, $q);

                if (mysqli_num_rows($r) > 0) {
                    while (list($cid, $cname) = mysqli_fetch_array($r, MYSQLI_NUM)) {
                        if (isset($pcid) && $pcid == $cid) {
                            echo "<option selected value='{$cid}'>{$cname}</option>";
                        } else {
                            echo "<option value='{$cid}'>{$cname}</option>";
                        }
                    }
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="">Content:</label>
            <?php
                if (isset($errors) && in_array('content', $errors)) {
                    echo "<p class='text-danger'>Please enter post content !</p>";
                }
            ?>
            <textarea name="pcontent" id="pcontent" rows="30" class="form-control" tabindex="3" required><?php
                    echo (isset($pcontent)) ? $pcontent : '';
                ?></textarea>
        </div>

        <div class="form-group">
            <label for="">Post image:</label>
            <?php
                if (isset($errors)) {
                    show_error($errors);
                }
            ?>
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
            <input type="hidden" name="old_image" value="<?php echo $pimage; ?>">
            <input type="file" class="form-control" name="pimage" tabindex="4">
            <img src="../uploads/images/<?php echo $pimage; ?>" alt="" class="img-responsive" width="500" height="300">
        </div>

        <div class="form-group">
            <label for="">Post Order:</label>
            <?php
                if (isset($errors) && in_array('porder invalid', $errors)) {
                    echo "<p class='text-danger'>Post order invalid !</p>";
                }
            ?>
            <select name="porder" id="porder" class="form-control" tabindex="5">
                <?php
                    $q = "SELECT COUNT(pid) AS num FROM posts";
                    $r = mysqli_query($dbc, $q);
                    confirm_query($r, $q);

                    if (mysqli_num_rows($r) > 0) {
                        list($num) = mysqli_fetch_array($r, MYSQLI_NUM);

                        for ($i = 1; $i <= $num + 1; $i ++) {
                            if (isset($porder) && $porder == $i) {
                                echo "<option selected value='{$i}'>{$i}</option>";
                            } else {
                                echo "<option value='{$i}'>{$i}</option>";
                            }
                        }
                    }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="">Post Status:</label>
            <?php
                if (isset($errors) && in_array('pstatus invalid', $errors)) {
                    echo "<p class='text-danger'>Post status invalid !</p>";
                }
            ?>
            <select name="pstatus" id="pstatus" class="form-control" tabindex="6">
                <option <?php echo (isset($pstatus) && ($pstatus == 2)) ? 'selected' : '' ?> value="2">Show</option>
                <option <?php echo (isset($pstatus) && ($pstatus == 1)) ? 'selected' : '' ?> value="1">Hide</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
</div>



<?php
include("includes/footer.php");
?>
