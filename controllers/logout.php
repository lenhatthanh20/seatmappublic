<?php
// Start the session
session_start();

if( isset($_SESSION["username"])) {
    unset($_SESSION["username"]);
    header("Location: login.php");
} else {
    header('Location: /seatmap/controllers/index.php');
    die();
}