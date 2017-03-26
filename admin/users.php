<?php
$title="Manage Users";
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
                $order_by = "uid";
                break;

            case 'name':
                $order_by = "uname";
                break;

            case 'email':
                $order_by = "uemail";
                break;

            case 'date':
                $order_by = "udate";
                break;

            default:
                $order_by = "udate";
                break;
        }
    } else {
        $order_by = "udate";
    }
?>


<div class="col-md-10">
    <h2 class="text-center">Show Users</h2>
    <br>
    <table class="table table-striped table-hover text-center">
        <thead>
        <tr>
            <th class="text-center"><a href="users.php?sort=id">ID</a></th>
            <th class="text-center"><a href="users.php?sort=name">Name</a></th>
            <th class="text-center"><a href="users.php?sort=email">E Mail</a></th>
            <th class="text-center">Avatar</th>
            <th class="text-center">Level</th>
            <th class="text-center">Active</th>
            <th class="text-center"><a href="users.php?sort=date">Joined at</a></th>
            <th class="text-center" colspan="2">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // pagination
        $display = 5;

        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;

        $q = "SELECT uid, uname, uemail, avatar, ulevel, uactive, DATE_FORMAT(udate, '%d/%m/%Y %h:%i %p') AS created_at FROM users ORDER BY {$order_by} DESC LIMIT {$start}, {$display}";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) > 0) {
            while ($users = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "
                    <tr id='{$users['uid']}'>
                        <td>{$users['uid']}</td>
                        <td>{$users['uname']}</td>
                        <td>{$users['uemail']}</td>
                        <td>
                            <img src='../uploads/avatars/{$users['avatar']}' class='img-responsive' width='100' height='96' />
                        </td>
                        <td>";
                echo ($users['ulevel'] == 2) ? "<span class='text-danger'>Admin</span>" : '<span class="text-success">User</span>';
                echo "</td>
                        <td>";
                echo (empty($users['uactive'])) ? "Active" : 'None';
                echo "</td>
                        <td>{$users['created_at']}</td>
                        <td>";
                if ($users['uemail'] != "tuunguyen2795@gmail.com") {
                    echo "<a id-delete='{$users['uid']}' class='delete-user' href=''><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                }
                echo "</td>
                        <td>";
                if ($users['uemail'] != "tuunguyen2795@gmail.com") {
                    echo "<a href='edit_user.php?uid={$users['uid']}'><span class='glyphicon glyphicon-edit text-warning'></span></a>";
                }
                echo "</td>
                    </tr>       
                ";
            }
        }
        ?>
        </tbody>
    </table>
    <?php pagination($display, 'uid', 'users'); ?>

</div>



<?php
include("includes/footer.php");
?>
