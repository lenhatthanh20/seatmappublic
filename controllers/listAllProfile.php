<?php
// Start the session
session_start();
require_once('../models/Profile.php');

$profile = new Profile();

/* Check authentication by session */
if(isset($_SESSION["username"])) {
    /* Nothing here */
}else {
    header('Location: /seatmap/controllers/index.php');
    die();
}


/* List All Users */
$arrayAllProfile = $profile->listAllProfile();

/* String variable to hold all data is loaded in database. */
$data = null;
foreach ($arrayAllProfile as $value) {
    $data .=
        '<div>' .
        '<img class="ml-2" src="' . $value[3] . '" height="50px" width="50px" style="border-radius:50%">' .
        '<button class="btn btn-outline-light btn-sm ml-1">' . $value[1] . '</button>';
}

/* Parse all data to client */
echo $data;