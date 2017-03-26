<?php
$title="All Posts";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
include("includes/sidebar.php");
isAdmin();
?>

<?php
    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];

        $order_by = sort_post($sort);
    } else {
        $order_by = "pdate";
    }
?>


<div class="col-md-10">
    <h2 class="text-center">Show posts</h2>
    <br>
    <table class="table table-striped table-hover text-center">
        <thead>
        <tr>
            <th class="text-center"><a href="posts.php?sort=id">ID</a></th>
            <th class="text-center"><a href="posts.php?sort=title">Post title</a></th>
            <th class="text-center">Category</th>
            <th class="text-center">Image</th>
            <th class="text-center">Status</th>
            <th class="text-center"><a href="posts.php?sort=order">Order</a></th>
            <th class="text-center"><a href="posts.php?sort=date">Created at</a></th>
            <th class="text-center" colspan="2">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // pagination
        $display = 5;

        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;

        $q = "SELECT p.pid, p.ptitle, c.cname, p.pimage, p.porder, p.pstatus, DATE_FORMAT(p.pdate, '%d/%m/%Y %h:%i %p') AS created_at";
        $q .= " FROM posts AS p JOIN categories AS c USING (cid) ORDER BY {$order_by} DESC LIMIT {$start}, {$display}";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) > 0) {
            while ($posts = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "
                    <tr id='{$posts['pid']}'>
                        <td>{$posts['pid']}</td>
                        <td>{$posts['ptitle']}</td>
                        <td>{$posts['cname']}</td>
                        <td><img src='../uploads/images/{$posts['pimage']}' width='96' height='100' alt='' class='img-responsive'></td>
                        <td>";
                        echo ($posts['pstatus'] == 2) ? "Show" : "Hide";
                echo "    
                        </td>
                        <td>{$posts['porder']}</td>
                        <td>{$posts['created_at']}</td>
                        <td><a href='edit_post.php?pid={$posts['pid']}'><span class='glyphicon glyphicon-edit text-warning'></span></a></td>
                        <td><a name-delete='".urlencode($posts['pimage'])."' id-delete='{$posts['pid']}' class='delete-post' href=''><span class='glyphicon glyphicon-remove text-danger'></span></a></td>
                    </tr>       
                ";
            }
        }
        ?>
        </tbody>
    </table>
    <?php pagination($display, 'pid', 'posts'); ?>

</div>



<?php
include("includes/footer.php");
?>
