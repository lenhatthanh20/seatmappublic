<?php

/* Start session */
session_start();

require_once('../models/Profile.php');

$profile = new Profile();

/* Check user session */
if( isset($_SESSION["username"])) {
    /* Nothing here */
}else {
    header('Location: /seatmap/controllers/index.php');
    die();
}

/* Handle POST method */
if(isset($_POST['deleteID'])){
    $deleteID = $_POST['deleteID'];
    $success = $profile->updateProfileWhenRemoveSeatmap($deleteID);
    if($success){
        $result = $profile->selectProfile($deleteID);
        if($result) {
            echo json_encode($result);
        } else {
            echo false;
        }
    }
}