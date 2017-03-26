<?php
$title="All Category";
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
                $order_by = "cid";
                break;

            case 'name':
                $order_by = "cname";
                break;

            case 'order':
                $order_by = "corder";
                break;

            case 'date':
                $order_by = "cdate";
                break;

            default:
                $order_by = "cdate";
                break;
        }
    } else {
        $order_by = "cdate";
    }
?>


<div class="col-md-10">
    <h2 class="text-center">Show categories</h2>
    <br>
    <table class="table table-striped table-hover text-center">
        <thead>
        <tr>
            <th class="text-center"><a href="categories.php?sort=id">ID</a></th>
            <th class="text-center"><a href="categories.php?sort=name">Category name</a></th>
            <th class="text-center"><a href="categories.php?sort=order">Category order</a></th>
            <th class="text-center">Category status</th>
            <th class="text-center"><a href="categories.php?sort=date">Created at</a></th>
            <th class="text-center" colspan="2">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // pagination
        $display = 10;

        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;

        $q = "SELECT cid, cname, corder, cstatus, DATE_FORMAT(cdate, '%d/%m/%Y %h:%i %p') AS created_at FROM categories ORDER BY {$order_by} DESC LIMIT {$start}, {$display}";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) > 0) {
            while ($cates = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "
                    <tr id='{$cates['cid']}'>
                        <td>{$cates['cid']}</td>
                        <td>{$cates['cname']}</td>
                        <td>{$cates['corder']}</td>
                        <td>";
                echo ($cates['cstatus'] == 2) ? "Show" : 'Hide';
                echo "</td>
                        <td>{$cates['created_at']}</td>
                        <td><a href='edit_category.php?cid={$cates['cid']}'><span class='glyphicon glyphicon-edit text-warning'></span></a></td>
                        <td><a id-delete='{$cates['cid']}' class='delete-cate' href=''><span class='glyphicon glyphicon-remove text-danger'></span></a></td>
                    </tr>       
                ";
            }
        }
        ?>
        </tbody>
    </table>
    <?php pagination($display, 'cid', 'categories'); ?>

</div>



<?php
include("includes/footer.php");
?>
