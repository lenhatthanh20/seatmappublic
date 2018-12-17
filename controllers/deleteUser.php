<?php
// Start the session
session_start();

require_once('../models/Profile.php');
require_once('../models/Utility.php');

$profile = new Profile();
$utility = new Utility();

/* Check authentication by session */
if( !isset($_SESSION["username"])) {
    $utility->redirect('/seatMap/controllers/index.php');
}

if (isset($_POST['id']) && isset($_POST['path'])) {

    $id = htmlspecialchars($_POST['id']);
    $imagePath = htmlspecialchars($_POST['path']);

    $success = $profile->deleteProfile($id);

    if($success) {
        if($imagePath !== "../images/default.png"){
            unlink ($imagePath);
        }
        echo true;
    } else {
        echo false;
    }

    header('Location: dashboard.php');
}
