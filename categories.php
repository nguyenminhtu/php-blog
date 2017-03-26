<?php
$title = "Category Page";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
?>


<!-- main content -->
<div class="col-md-8" id="content">
    <div class="breadcrumb">
        <a href="/"><span class="glyphicon glyphicon-home"></span> Home</a> &nbsp;&nbsp; /  &nbsp;&nbsp;<?php if (isset($_GET['cname'])) echo $_GET['cname']; ?>
    </div>

    <?php
    if (isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $cid = $_GET['cid'];

        $display = 4;

        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;

        $q = "SELECT p.pid, p.ptitle, p.pcontent, p.pimage, p.uid, u.uname, DATE_FORMAT(p.pdate, '%d/%m/%Y %h:%i %p') AS date";
        $q .= " FROM posts AS p JOIN categories AS c ON p.cid = c.cid JOIN users AS u ON p.uid = u.uid WHERE p.pstatus = 2 AND p.cid = {$cid} ORDER BY p.porder LIMIT {$start}, {$display}";

        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) > 0) {
            while ($posts = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "<div class='well row'>
                        <div class='col-md-4'>
                            <p><img src='uploads/images/{$posts['pimage']}' alt='Post image' class='img-responsive' style='width: 100%; height: 130px;'></p>
                        </div>
                        <div class='col-md-8'>
                            <p><a href='single.php?pid={$posts['pid']}&ptitle=".urlencode($posts['ptitle'])."' class='lead'>{$posts['ptitle']}</a></p>
                            <p>".excert_text($posts['pcontent'], 400)."</p>
                        </div>
                    </div>
                ";
            }

            pagination($display, 'pid', 'posts', 'cid = '.$cid, 'categories.php?cid='.$cid.'&cname='.$_GET['cname'].'&');
        } else {
            echo "<h3 class='text-center'>This category has no post to display !</h3>";
        }
    } else {
        redirect_to();
    }
    ?>
</div> <!-- end content -->


<?php
include("includes/sidebar.php");
include("includes/footer.php");
?>
