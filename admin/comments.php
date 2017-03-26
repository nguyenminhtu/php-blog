<?php
$title="Manage Comments";
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
include("includes/sidebar.php");
isAdmin();
?>

<?php
    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];

        switch ($sort) {
            case 'id':
                $order_by = "cmid";
                break;

            case 'post':
                $order_by = "pid";
                break;

            case 'user':
                $order_by = "uid";
                break;

            case 'date':
                $order_by = "cmdate";
                break;

            default:
                $order_by = "cmdate";
                break;
        }
    } else {
        $order_by = "cmdate";
    }
?>


<div class="col-md-10">
    <h2 class="text-center">Show comments</h2>
    <br>
    <table class="table table-striped table-hover text-center">
        <thead>
        <tr>
            <th class="text-center"><a href="comments.php?sort=id">ID</a></th>
            <th class="text-center"><a href="comments.php?sort=post">Post title</a></th>
            <th class="text-center"><a href="comments.php?sort=user">User</a></th>
            <th class="text-center">Content</th>
            <th class="text-center"><a href="comments.php?sort=date">Posted at</a></th>
            <th class="text-center">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // pagination
        $display = 10;

        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;

        $q = "SELECT cm.cmid, p.ptitle, u.uname, cm.cmcontent, DATE_FORMAT(cmdate, '%d/%m/%Y %h:%i %p') AS created_at ";
        $q .= "FROM comments AS cm";
        $q .= " JOIN posts AS p ON cm.pid = p.pid";
        $q .= " JOIN users AS u ON cm.uid = u.uid";
        $q .= " ORDER BY {$order_by} DESC LIMIT {$start}, {$display}";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) > 0) {
            while ($comments= mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "
                    <tr id='{$comments['cmid']}'>
                        <td>{$comments['cmid']}</td>
                        <td>{$comments['ptitle']}</td>
                        <td>{$comments['uname']}</td>
                        <td width='400'>";
                echo excert_text($comments['cmcontent'], 150);
                echo "</td>
                        <td>{$comments['created_at']}</td>
                        <td><a id-delete='{$comments['cmid']}' class='delete-comment' href=''><span class='glyphicon glyphicon-remove text-danger'></span></a></td>
                    </tr>       
                ";
            }
        }
        ?>
        </tbody>
    </table>
    <?php pagination($display, 'cmid', 'comments'); ?>

</div>



<?php
include("includes/footer.php");
?>
