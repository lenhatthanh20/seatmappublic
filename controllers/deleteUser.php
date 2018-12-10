<?php
// Start the session
session_start();
require_once('../models/Profile.php');

$profile = new Profile();

/* Check authentication by session */
if( isset($_SESSION["username"])) {
    //$smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: ../index.php');
    die();
}

if (isset($_POST['id']) && isset($_POST['path'])) {

    $id = $_POST['id'];
    $imagePath = $_POST['path'];

    $success = $profile->deleteProfile($id);

    if($success) {
        //Delete image
        if($imagePath !== "../images/default.png"){
            unlink ($imagePath);
            $message = 'Profile is deleted successfully!';
        }
    } else {
        $message = 'Can not delete this user!';
    }

    $_SESSION["success"] = $success;
    $_SESSION["message"] = $message;
    header('Location: dashboard.php');
}
