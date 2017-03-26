<?php
$title="Add Category";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
include("includes/sidebar.php");
isAdmin();
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    $trimmed = array_map('trim', $_POST);

    if (!empty($trimmed['cname'])) {
        $cname = $trimmed['cname'];
    } else {
        $errors[] = "name";
    }

    if (filter_var($trimmed['corder'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $corder = $trimmed['corder'];
    } else {
        $errors[] = 'corder invalid';
    }

    if (filter_var($trimmed['cstatus'], FILTER_VALIDATE_INT)) {
        $cstatus = $trimmed['cstatus'];
    } else {
        $errors[] = 'cstatus invalid';
    }

    // neu ko co loi thi submit form
    if (empty($errors)) {
        $q = "INSERT INTO categories (cname, corder, cstatus, cdate) VALUES (?, ?, ?, NOW())";

        $stmt = mysqli_prepare($dbc, $q);

        confirm_stmt($stmt);

        mysqli_stmt_bind_param($stmt, 'sii', $cname, $corder, $cstatus);

        mysqli_stmt_execute($stmt) or die("MySQLI error: {$q} \n\n Error is: " . mysqli_stmt_error($stmt));

        if (mysqli_stmt_affected_rows($stmt) == 1) {
            $message = "<p class='text-center text-success'>Insert category successfully !</p>";
            $_POST = array();
        } else {
            $message = "<p class='text-center text-danger'>Could not insert to database due to system error !</p>";
        }
    }
}
?>


<div class="col-md-10">
    <h3 class="text-center">Add category</h3>
    <?php
        if (isset($message)) {
            echo $message;
        }
    ?>
    <form action="" method="post" role="form">
        <div class="form-group">
            <label for="">Category name:</label>
            <?php
                if (isset($errors) && in_array('name', $errors)) {
                    echo "<p class='text-danger'>Please enter your catery name !</p>";
                }
            ?>
            <input type="text" class="form-control" name="cname" id="cname" placeholder="Category name..." autofocus tabindex="1" maxlength="30" required>
        </div>

        <div class="form-group">
            <label for="">Category Order:</label>
            <?php
                if (isset($errors) && in_array('corder invalid', $errors)) {
                    echo "<p class='text-danger'>Corder invalid !</p>";
                }
            ?>
            <select name="corder" id="corder" class="form-control" tabindex="2">
                <?php
                    $q = "SELECT COUNT(cid) AS num FROM categories";
                    $r = mysqli_query($dbc, $q);
                    confirm_query($r, $q);

                    if (mysqli_num_rows($r) > 0) {
                        list($num) = mysqli_fetch_array($r, MYSQLI_NUM);

                        for ($i = 1; $i <= $num + 1; $i ++) {
                            if (isset($_POST['corder']) && $_POST['corder'] == $i) {
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
            <label for="">Category Status:</label>
            <?php
                if (isset($errors) && in_array('cstatus invalid', $errors)) {
                    echo "<p class='text-danger'>Cstatus invalid !</p>";
                }
            ?>
            <select name="cstatus" id="cstatus" class="form-control" tabindex="3">
                <option <?php echo (isset($_POST['cstatus']) && ($_POST['cstatus'] == 2)) ? 'selected' : '' ?> value="2">Show</option>
                <option <?php echo (isset($_POST['cstatus']) && ($_POST['cstatus'] == 1)) ? 'selected' : '' ?> value="1">Hide</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>



<?php
include("includes/footer.php");
?>
