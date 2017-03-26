<?php
$title = "Search Result";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
?>



<!-- main content -->
<div class="col-md-8" id="content">
    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (empty($_GET['q'])) {
                redirect_to();
            } else {

                echo "
                    <div class='breadcrumb'>
                        Keyword : <span style='color: red;'>{$_GET['q']}</span>
                    </div>
                ";

                $key = mysqli_real_escape_string($dbc, strip_tags(trim($_GET['q'])));

                $display = 4;

                $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;

                $q = "SELECT p.pid, p.ptitle, p.pcontent, p.pimage, p.uid, u.uname, DATE_FORMAT(p.pdate, '%d/%m/%Y %h:%i %p') AS date";
                $q .= " FROM posts AS p JOIN categories AS c ON p.cid = c.cid JOIN users AS u ON p.uid = u.uid";
                $q .= " WHERE p.pstatus = 2 AND (p.ptitle LIKE '%{$key}%' OR p.pcontent LIKE '%{$key}%')";
                $q .= " ORDER BY p.porder LIMIT {$start}, {$display}";

                $r = mysqli_query($dbc, $q);
                confirm_query($r, $q);

                $q1 = "SELECT pid FROM posts WHERE ptitle LIKE '%$key%' OR pcontent LIKE '%$key%'";
                $r1 = mysqli_query($dbc, $q1);
                confirm_query($r1, $q1);
                $count = mysqli_num_rows($r1);

                echo "
                    <div class='breadcrumb'>
                        Tìm thấy {$count} kết quả !
                    </div>
                ";

                if (mysqli_num_rows($r) > 0) {
                    while ($posts = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                        echo "<div class='well row'>
                                <div class='col-md-4'>
                                    <p><img src='uploads/images/{$posts['pimage']}' alt='Post image' class='img-responsive' style='width: 100%; height: 130px;'></p>
                                </div>
                                <div class='col-md-8'>
                                    <p><a href='single.php?pid={$posts['pid']}&ptitle=".urlencode($posts['ptitle'])."' class='lead'><span>{$posts['ptitle']}</span></a></p>
                                    <p>".excert_text($posts['pcontent'], 400)."</p>
                                </div>
                            </div>
                        ";
                    }
                    pagination($display, 'pid', 'posts', "ptitle LIKE '%".$key."%' OR pcontent LIKE '%".$key."%'", 'search.php?q='.$key.'&');
                }
            }
        }
    ?>
</div> <!-- end content -->



<?php
include("includes/sidebar.php");
include("includes/footer.php");
?>
