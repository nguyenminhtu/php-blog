<?php
include("includes/header.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
include("includes/sidebar.php");
isAdmin();
?>


<div class="col-md-10">
    <h2 class="text-center">Welcome to Admin Dashboard</h2>
    <?php
        $q = "SELECT COUNT(c.cid) AS cate FROM categories AS c";

        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);

        if (mysqli_num_rows($r) > 0) {
            $q1 = "SELECT COUNT(uid) AS user FROM users";
            $r1 = mysqli_query($dbc, $q1);
            confirm_query($r1, $q1);

            if (mysqli_num_rows($r1) > 0) {
                list($user) = mysqli_fetch_array($r1, MYSQLI_NUM);
                $q2 = "SELECT COUNT(pid) AS post FROM posts";
                $r2 = mysqli_query($dbc, $q2);
                confirm_query($r2, $q2);

                list($post) = mysqli_fetch_array($r2, MYSQLI_NUM);

                $q3 = "SELECT COUNT(cmid) AS post FROM comments";
                $r3 = mysqli_query($dbc, $q3);
                confirm_query($r3, $q3);

                list($cm) = mysqli_fetch_array($r3, MYSQLI_NUM);

                list($cate) = mysqli_fetch_array($r, MYSQLI_NUM);
                echo "<p class='text-center'>There is: {$cate} category.</p>";
                echo "<p class='text-center'>There is: {$post} post.</p>";
                echo "<p class='text-center'>There is: {$user} user.</p>";
                echo "<p class='text-center'>There is: {$cm} comments.</p>";
            }
        }
    ?>
</div>



<?php
include("includes/footer.php");
?>
    

