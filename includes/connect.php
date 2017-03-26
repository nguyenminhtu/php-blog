<?php
    //$dbc = mysqli_connect("sql104.byethost7.com", "b7_19854287", "nguyenminhtu95", "b7_19854287_tunguyen");
	$dbc = mysqli_connect("localhost", "root", "", "mblog");

    if (!$dbc) {
        trigger_error("Could not connect to database: " . mysqli_connect_error());
    } else {
        mysqli_set_charset($dbc, 'utf-8');
    }
?>