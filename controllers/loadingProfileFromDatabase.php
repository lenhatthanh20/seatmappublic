<?php

/* Start session */
session_start();

require_once('../models/Profile.php');

$profile = new Profile();

/* Check user session */
if( isset($_SESSION["username"])) {
    //$smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: ../index.php');
    die();
}

if(isset($_POST['flag']) && $_POST['flag'] === 'true'){
    $allProfiles = $profile->listAllProfile();
    echo json_encode($allProfiles);
}