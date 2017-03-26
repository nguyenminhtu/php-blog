<?php
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
?>



<!-- main content -->
<div class="col-md-8" id="content">
    <div class="well">
        <span class="glyphicon glyphicon-bookmark"></span> <b style="font-size: 20px;">Featured</b>
    </div>

    <?php
        $q = "SELECT p.pid, p.ptitle, c.cname, p.cid, p.pcontent, p.uid, u.uname, p.pimage, DATE_FORMAT(p.pdate, '%d/%m/%Y %h:%i %p') as date";
        $q .= " FROM posts AS p JOIN categories AS c ON p.cid = c.cid JOIN users AS u ON p.uid = u.uid WHERE p.pstatus = 2 ORDER BY porder LIMIT 4";

        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) > 0) {
            while ($posts = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "<div class='well'>
                        <img src='uploads/images/{$posts['pimage']}' alt='' class='img-responsive'>
                        <p style='font-size: 23px;margin-top: 12px;'><a href='single.php?pid={$posts['pid']}&ptitle=".urlencode($posts['ptitle'])."'>{$posts['ptitle']}</a></p>
                        <hr style='margin: 7px 0; color: #e1e1e1;'>
                        <span class='glyphicon glyphicon-user'></span>&nbsp;<a href=''>{$posts['uname']}</a> &nbsp;&nbsp;&nbsp;
                        | &nbsp;&nbsp;&nbsp; <span class='glyphicon glyphicon-folder-open'></span>&nbsp; <a href='categories.php?cid={$posts['cid']}&cname=" .urlencode($posts['cname'])."'>{$posts['cname']}</a>   &nbsp;&nbsp;&nbsp;
                        | &nbsp;&nbsp;&nbsp;  <span class='glyphicon glyphicon-time'></span>&nbsp;<a>{$posts['date']}</a>
                        <p>".substr($posts['pcontent'], 0, 500)." ...</p>
                        <a href='single.php?pid={$posts['pid']}&ptitle=".urlencode($posts['ptitle'])."' class='btn btn-primary'><span class=\"glyphicon glyphicon-arrow-right\"></span> Read more </a>
                    </div>
                ";
        ?>

    <?php
            }
        }
    ?>
</div> <!-- end content -->



<?php
include("includes/sidebar.php");
include("includes/footer.php");
?>
