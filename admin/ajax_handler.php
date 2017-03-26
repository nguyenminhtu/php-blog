<?php
include("includes/connect.php");
include("includes/functions.php");
//isAdmin();

if (isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT)) {
    $cid = $_GET['cid'];

    $q = "DELETE FROM categories WHERE cid = {$cid} LIMIT 1";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_affected_rows($dbc) == 1) {
        $q1 = "DELETE FROM posts WHERE cid = {$cid}";
        $r1 = mysqli_query($dbc, $q1);
        confirm_query($r1, $q1);

        if (mysqli_affected_rows($dbc) > 0) {
            $q2 = "SELECT pid FROM posts WHERE cid = {$cid}";
            $r2 = mysqli_query($dbc, $q2);
            confirm_query($r2, $q1);

            if (mysqli_num_rows($r2) > 0) {
                while ($posts = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                    $q3 = "DELETE FROM comments WHERE pid = {$posts['pid']}";
                    $r3 = mysqli_query($dbc, $q3);
                    confirm_query($r3, $q3);

                    if (mysqli_affected_rows($dbc) > 0) {
                        echo 'ok';
                    } else {
                        echo 'fail';
                    }
                }
            }
        } else {
            echo 'fail';
        }
    } else {
        echo 'fail';
    }
}

if (isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT)) {
    $pid = $_GET['pid'];
    $pimage = $_GET['pimage'];

    $q = "DELETE FROM posts WHERE pid = {$pid} LIMIT 1";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_affected_rows($dbc) == 1) {
        if (unlink("../uploads/images/".$pimage)) {
            $q1 = "DELETE FROM comments WHERE pid = {$pid}";
            $r1 = mysqli_query($dbc, $q1);
            confirm_query($r1, $q1);

            if (mysqli_affected_rows($dbc) > 0) {
                echo 'ok';
            } else {
                echo 'fail';
            }
        } else {
            echo 'failed';
        }
    } else {
        echo 'fail';
    }
}

if (isset($_GET['uid']) && filter_var($_GET['uid'], FILTER_VALIDATE_INT)) {
    $uid = $_GET['uid'];

    $q = "DELETE FROM users WHERE uid = {$uid} LIMIT 1";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_affected_rows($dbc) == 1) {
        $q1 = "DELETE FROM comments WHERE uid = {$uid}";
        $r1 = mysqli_query($dbc, $q1);
        confirm_query($r1, $q1);

        if (mysqli_affected_rows($dbc) > 0) {
            echo 'ok';
        } else {
            echo 'fail';
        }
    } else {
        echo 'fail';
    }
}

if (isset($_GET['cmid']) && filter_var($_GET['cmid'], FILTER_VALIDATE_INT)) {
    $cmid = $_GET['cmid'];

    $q = "DELETE FROM comments WHERE cmid = {$cmid} LIMIT 1";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);

    if (mysqli_affected_rows($dbc) == 1) {
        echo 'ok';
    } else {
        echo 'fail';
    }
}
?>