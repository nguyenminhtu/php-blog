<?php
ob_start();
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

function redirect_to($page = '') {
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

function pagination($display = 2, $id, $table, $q = NULL, $file) {
    global $dbc;
    global $start;

    if (isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $page = $_GET['p'];
    } else {
        if ($q) {
            $q = "SELECT COUNT({$id}) FROM {$table} WHERE {$q}";
        } else {
            $q = "SELECT COUNT({$id}) FROM {$table}";
        }
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

    $output = "<div class='text-center'><ul class='pagination'>";
    if ($page > 1) {
        $current_page = ($start / $display) + 1;

        // neu khong phai o trang dau thi se hien thi trang truoc
        if ($current_page != 1) {
            $output .= "<li><a href='{$file}s=".($start - $display)."&p={$page}'>Previous</a></li>";
        }

        // hien thi nhung trang con lai
        for ($i = 1; $i <= $page; $i++) {
            if ($i != $current_page) {
                $output .= "<li><a href='{$file}s=".($display * ($i - 1))."&p={$page}'>{$i}</a></li>";
            } else {
                $output .= "<li><a style='cursor: pointer;font-weight: bold;color: red;'>{$i}</a></li>";
            }
        } // end for

        if ($current_page != $page) {
            $output .= "<li><a href='{$file}s=".($start + $display)."&p={$page}'>Next</a></li>";
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

function hightlight_sidebar($name) {
    if (isset($_GET['cname']) && $_GET['cname'] == $name) {
        echo "active";
    }
}

function count_view($pid) {
    global $dbc;
    $ip = $_SERVER['REMOTE_ADDR'];

    //truy van csdl de xem page view hien tai
    $q = "SELECT pvviews, pvip FROM page_views WHERE pid = {$pid}";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_num_rows($r) > 0) {
        list($num, $dbip) = mysqli_fetch_array($r, MYSQLI_NUM);

        if ($dbip != $ip) {
            $q1 = "UPDATE page_views SET pvviews = (pvviews + 1) WHERE pid = {$pid}";
            $r1 = mysqli_query($dbc, $q1);
            confirm_query($r1, $q1);
        }
    } else {
        $q2 = "INSERT INTO page_views (pid, pvviews, pvip) VALUES ({$pid}, 1, '{$ip}')";
        $r2 = mysqli_query($dbc, $q2);
        confirm_query($r2, $q2);
    }
}

function validate_captcha() {
    $check = true;
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    // $data = array(
    //     'secret' => '6Lf6YhkUAAAAAM-zmQC9GUHLrCz-GaqITe-c_zDz',
    //     'response' => $_POST["g-recaptcha-response"]
    // );
    $data = array(
        'secret' => '6Lcr6xkUAAAAAIaiIjxFny7QluldG9NcPbEJ7EFT',
        'response' => $_POST["g-recaptcha-response"]
    );
    $query = http_build_query($data);
    $options = array(
        'http' => array(
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                "Content-Length: ".strlen($query)."\r\n".
                "User-Agent:MyAgent/1.0\r\n",
            'method'  => "POST",
            'content' => $query,
        ),
    );
    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context, -1, 40000);
    $captcha_success=json_decode($verify);
    if ($captcha_success->success==false) {
        $check = false;
    } else if ($captcha_success->success==true) {
        $check = true;
    }
    return $check;
}

function sendmail($receive, $body, $success) {
    global $_POST;
    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com;';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'tuunguyen2795@gmail.com';                 // SMTP username
    $mail->Password = 'Tunguyen02071995';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to

    $mail->setFrom('localhost@localhost', 'izCMS submission');
    $mail->addAddress($receive, 'User');     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'izCMS email';
    $mail->Body    = $body;

    if(!$mail->send()) {
        $result = "<p class='text-center text-danger'>Message could not be sent. <br>";
        $result .= 'Mailer Error: ' . $mail->ErrorInfo . "</p>";
    } else {
        $result = "<p class='text-center text-success'>{$success}</p>";
        $_POST = array();
    }

    return $result;
}

function is_logged_in() {
    return (isset($_SESSION['uid']));
}

function clean_email($email) {
    $suspect = array('to:', 'bbc:', 'cc:', 'content-type', 'mime-version:', 'multipart-mixed:', 'content-transfer-encoding:');

    foreach ($suspect as $s) {
        if (strpos($email, $s) !== FALSE) {
            return '';
        }
    }

    //tra ve gia tri cho dau xuong hang
    $email = str_replace(array('\n', '\r', '%0a', '%0d'), '', $email);

    return $email;
}

function highlight_keyword($key, $text) {
    echo str_replace($key, "<span style='color: red;'>{$key}</span>", $text);
}

?>

