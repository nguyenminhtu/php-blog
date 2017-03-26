<?php
$title = "Single Page";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
?>




<?php
    if (isset($_GET['pid'], $_GET['ptitle']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $title = mysqli_real_escape_string($dbc, strip_tags($_GET['ptitle']));
        $pid = $_GET['pid'];
        ?>




<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = mysqli_real_escape_string($dbc, strip_tags($_POST['comment']));

    if (!validate_captcha()) {
        $error = "<p class='text-center text-danger'>Please click to validate captcha</p>";
    }

    if (!isset($error)) {
        $qq = "INSERT INTO comments (pid, uid, cmcontent, cmdate) VALUES ({$pid}, {$_SESSION['uid']}, '{$comment}', NOW())";
        $rr = mysqli_query($dbc, $qq);
        confirm_query($rr, $qq);

        if (mysqli_affected_rows($dbc) == 1) {
            $message = "<p class='text-center text-success'>Thank for your comment !</p>";
        } else {
            $message = "<p class='text-center text-danger'>Could not post your comment due to system error. Try again !</p>";
        }
    }
}
?>



        <!-- main content -->
        <div class="col-md-8" id="content">
            <div class="breadcrumb">
                <?php
                    $qq = "SELECT c.cid, c.cname FROM posts AS p JOIN categories AS c USING (cid) WHERE p.pid = {$pid}";
                    $rr = mysqli_query($dbc, $qq);
                    confirm_query($rr, $qq);

                    if (mysqli_num_rows($rr) > 0) {
                        list($id, $name) = mysqli_fetch_array($rr, MYSQLI_NUM);
                    } else {
                        list($id, $name) = NULL;
                    }
                ?>
                <a href="/"><span class='glyphicon glyphicon-home'></span> &nbsp;Homepage</a> &nbsp;&nbsp; / &nbsp;&nbsp;<a
                    href="categories.php?cid=<?php echo $id; ?>&cname=<?php echo $name; ?>"><?php echo (isset($name)) ? $name :"" ?></a>&nbsp;&nbsp; / &nbsp;&nbsp;<?php echo $title; ?>
            </div>
<?php

        count_view($pid);

        $q = "SELECT p.ptitle, p.pcontent, p.pimage, DATE_FORMAT(p.pdate, '%d/%m/%Y %h:%i %p') AS date, ";
        $q .= " c.cid, c.cname, p.uid, u.uname, pv.pvviews";
        $q .= " FROM posts AS p JOIN categories AS c ON p.cid = c.cid JOIN users AS u ON p.uid = u.uid";
        $q .= " JOIN page_views AS pv ON p.pid = pv.pid";
        $q .= " WHERE p.pid = {$pid}";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) == 1) {
            while ($post = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

                echo "
                    <p><img src='uploads/images/{$post['pimage']}' alt='{$post['ptitle']}' class='img-responsive' style='width: 100%; height: 300px;'></p>
                    <h2>{$post['ptitle']}</h2>
                    <p><span>{$post['uname']}</span> &nbsp;&nbsp; | &nbsp;&nbsp; <span>{$post['date']}</span> &nbsp;&nbsp; | &nbsp;&nbsp; Page views: {$post['pvviews']}</p>
                    <div>
                        {$post['pcontent']}
                    </div>
                ";

            }
            if (isset($_SESSION['uid'])) {
                ?>



                <!-- comment form -->
                <hr style='margin-top: 35px;'>
                <div class="row">
                    <form action="" method="post" role="form" id="commentForm">

                        <script type="text/javascript">
                            function submitForm() {
                                document.getElementById("commentForm").submit();
                            }
                        </script>

                        <div class="form-group">
                            <input type="text" name="comment" id="comment" class="form-control" placeholder="Type your comment here..." required autofocus>
                        </div>

                        <button
                            class="g-recaptcha btn btn-primary"
                            data-sitekey="6Lcr6xkUAAAAABwX7sZyo-kQt8v7tDKSlCn6OUF5"
                            data-callback="submitForm">
                            Post
                        </button>
                    </form>
                </div>
                <br>
                <?php
                    if (isset($message)) {
                        echo $message;
                    }

                    if (isset($error)) {
                        echo $error;
                    }
                ?>
                <hr>



                <?php
            } else {
                echo "<hr style='margin-top: 35px;'><button class='btn btn-primary'><a href='login.php' style='color: #fff;'>Login to post comment</a></button><br/><br/>";
            }




            $display = (isset($_GET['d']) && filter_var($_GET['d'], FILTER_VALIDATE_INT)) ? $_GET['d'] : 4;


            $q1 = "SELECT cm.cmid, cm.cmcontent, cm.uid, DATE_FORMAT(cm.cmdate, '%d/%m/%y %h:%i %p') AS date, u.uname, u.avatar FROM comments AS cm JOIN users AS u ON cm.uid = u.uid WHERE cm.pid = {$pid} ORDER BY date DESC LIMIT 0, {$display}";
            $r1 = mysqli_query($dbc, $q1);
            confirm_query($r1, $q1);

            if (mysqli_num_rows($r1) > 0) {
                while ($comments = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
                    echo "
                            <div id='{$comments['cmid']}' class='well-sm row' style='margin-bottom: 7px;'>
                                <p><span class='pull-left'><img src='uploads/avatars/{$comments['avatar']}' alt='' class='img-responsive img-rounded img-comment'></span><span class='pull-left' style='color: green; font-size: 18px;margin-top: 2px; margin-left: 6px;'>{$comments['uname']}</span>&nbsp;&nbsp;&nbsp;";
                    // neu dung la comment cua nguoi do thi cho phep xoa
                    if (isset($_SESSION['uid']) && ($comments['uid'] == $_SESSION['uid'])) {
                        echo "<span class='pull-right text-danger delete-comment btn btn-danger' id-delete='{$comments['cmid']}'>X</span>";
                    }

                    echo "</p>
                                <div class='clearfix'></div>
                                <p style='margin-top: 10px;'>".htmlentities($comments['cmcontent'], ENT_COMPAT, 'UTF-8')."</p>
                                <p class='text-info'>Posted at: {$comments['date']}</p>
                            </div>
                        ";
                }


                // dem so luong commen de xem co hien thi nut load more hay ko
                $q2 = "SELECT COUNT(cmid) AS count FROM comments WHERE pid = {$pid}";
                $r2 = mysqli_query($dbc, $q2);
                confirm_query($r2, $q2);

                list($count) = mysqli_fetch_array($r2, MYSQLI_NUM);


                if ($display < $count) {
                    if (!isset($_GET['d'])) {
                        echo "<br><hr><br><button class='btn btn-primary' id='load-more'><a style='color: #fff;' href='{$_SERVER['REQUEST_URI']}&d=".($display+4)."'>Load more comment</a></button>";
                    } else {
                        $path = substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - 4);
                        echo "<br><hr><br><button class='btn btn-primary' id='load-more'><a style='color: #fff;' href='{$path}&d=".($display+4)."'>Load more comment</a></button>";
                    }
                }

            } else {
                echo "<h4>This post has no comment. Be the first comment. </h4>";
            }



        } else {
            echo "<h3 class='text-center'>This id does not exist on the database !</h3>";
        }





    } else {
        redirect_to();
    }
?>


</div> <!-- end main content -->


<?php
include("includes/sidebar.php");
include("includes/footer.php");
?>
