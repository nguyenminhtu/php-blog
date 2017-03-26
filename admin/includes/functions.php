<?php
ob_start();
//define('BASE_URL', "http://realescapestring.byethost7.com/");
define('BASE_URL', "http://mycms.dev/");

function confirm_query($r, $q) {
    global $dbc;
    if (!$r) {
        die("MySQL error: {$q} \n\n Error is: " . mysqli_error($dbc));
    }
}

function confirm_stmt($stmt) {
    global $dbc;
    if (!$stmt) {
        die("MYSQLI statement error: " . mysqli_error($dbc));
    }
}

function redirect_to($page = 'admin/admin.php') {
    $url = BASE_URL . $page;
    header("Location: {$url}");
    ob_end_flush();
    exit();
}

function check_error_upload($err) {
    $errors = array();
    switch ($err) {
        case 1:
            $errors[] = "upload_max_filesize setting in php.ini";
            break;

        case 2:
            $errors[] = "The file exceeds the MAX_FILE_SIZE in html form";
            break;

        case 3:
            $errors[] = "The file was partially uploaded";
            break;

        case 4:
            $errors[] = "No file was uploaded";
            break;

        case 6:
            $errors[] = "No temporary folder was available";
            break;

        case 7:
            $errors[] = "Unable to write the disk";
            break;

        case 8:
            $errors[] = "File upload stopped";
            break;

        default:
            $errors[] = "a system error has occured";
            break;
    }

    return $errors;
}

function show_error($errors) {
    if (isset($errors) && in_array('upload_max_filesize setting in php.ini', $errors)) {
        echo "<p class='text-danger'>The file exceeds the upload_max_filesize setting in php.ini</p>";
    } elseif (isset($errors) && in_array('The file exceeds the upload_max_filesize in html form', $errors)) {
        echo "<p class='text-danger'>The file exceeds the upload_max_filesize in html form</p>";
    } elseif (isset($errors) && in_array('The file was partially uploaded', $errors)) {
        echo "<p class='text-danger'>The file was partially uploaded</p>";
    } elseif (isset($errors) && in_array('No file was uploaded', $errors)) {
        echo "<p class='text-danger'>No file was uploaded</p>";
    } elseif (isset($errors) && in_array('No temporary folder was available', $errors)) {
        echo "<p class='text-danger'>No temporary folder was available</p>";
    } elseif (isset($errors) && in_array('Unable to write the disk', $errors)) {
        echo "<p class='text-danger'>Unable to write the disk</p>";
    } elseif (isset($errors) && in_array('File upload stopped', $errors)) {
        echo "<p class='text-danger'>File upload stopped</p>";
    } elseif (isset($errors) && in_array('a system error has occured', $errors)) {
        echo "<p class='text-danger'>A system error has occured</p>";
    } elseif (isset($errors) && in_array('upload failed', $errors)) {
        echo "<p class='text-danger'>Upload image failed !</p>";
    } elseif (isset($errors) && in_array('wrong format', $errors)) {
        echo "<p class='text-danger'>Image is wrong format !</p>";
    } elseif (isset($errors) && in_array('image', $errors)) {
        echo "<p class='text-danger'>Image not null !</p>";
    }
}

function excert_text($text, $length) {
    $safe_text = htmlentities($text, ENT_COMPAT, 'UTF-8');
    if (strlen($safe_text) > $length) {
        $cut = substr($safe_text, 0, $length);
        $cut2 = substr($safe_text, 0, strrpos($cut, '.'));
        return $cut2;
    } else {
        return $safe_text;
    }
}

function sort_post($sort) {
    switch ($sort) {
        case 'id':
            $order_by = "pid";
            break;

        case 'title':
            $order_by = "ptitle";
            break;

        case 'order':
            $order_by = "porder";
            break;

        case 'date':
            $order_by = "pdate";
            break;

        default:
            $order_by = "pdate";
            break;
    }

    return $order_by;
}

function pagination($display = 2, $id, $table) {
    global $dbc;
    global $start;

    if (isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $page = $_GET['p'];
    } else {
        $q = "SELECT COUNT({$id}) FROM {$table}";
        $r = mysqli_query($dbc, $q);

        confirm_query($r, $q);

        list($record) = mysqli_fetch_array($r, MYSQLI_NUM);

        // kiem tra so bai post co lon hon so bai trong 1 page hay ko
        if ($record > $display) {
            $page = ceil($record / $display);
        } else {
            $page = 1;
        }
    }

    $output = "<div class='row text-center'><ul class='pagination'>";
    if ($page > 1) {
        $current_page = ($start / $display) + 1;

        // neu khong phai o trang dau thi se hien thi trang truoc
        if ($current_page != 1) {
            $output .= "<li><a href='posts.php?s=".($start - $display)."&p={$page}'>Previous</a></li>";
        }

        // hien thi nhung trang con lai
        for ($i = 1; $i <= $page; $i++) {
            if ($i != $current_page) {
                $output .= "<li><a href='{$table}.php?s=".($display * ($i - 1))."&p={$page}'>{$i}</a></li>";
            } else {
                $output .= "<li><a style='cursor: pointer;font-weight: bold;color: red;'>{$i}</a></li>";
            }
        } // end for

        if ($current_page != $page) {
            $output .= "<li><a href='{$table}.php?s=".($start + $display)."&p={$page}'>Next</a></li>";
        }
    } // end section pagination
    $output .= "</ul></div>";

    echo $output;
}

function isAdmin() {
    if (!isset($_SESSION['ulevel']) || $_SESSION['ulevel'] != 2) {
        redirect_to("admin/login.php");
    }
}

function highlight($path) {
    if (basename($_SERVER['SCRIPT_NAME']) == $path) {
        echo "active";
    }
}
?>

