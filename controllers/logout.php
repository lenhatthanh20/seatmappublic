<?php

/* Start the session */
session_start();

/* Check authenticate login by session variable */
if( isset($_SESSION["username"])) {

    /* If user is logout. Redirect to login page and clear the username session variable */
    unset($_SESSION["username"]);
    header("Location: login.php");
} else {

    /* If user is not login when click logout button. Redirect to home page */
    header('Location: /seatmap/controllers/index.php');
    die();
}