<?php
// Start the session
session_start();
require_once('../models/Profile.php');

$profile = new Profile();

if( isset($_SESSION["username"])) {
    //$smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: ../index.php');
    die();
}
/* Variables that will be parsed to Smarty template */
$success = true;
$message = null;
$error = [];

/* Variables to handle uploaded image */
$target_dir = "../images/";
$target_file = $target_dir . clean(basename($_FILES["fileToUpload"]["name"]));
$uploadOk = 0;
$moveImage = true;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;  // file is select in the input field
    } else {
        $error[0] = "File is not an image.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        // Rename the file when file is exist
        $temp = explode(".", $_FILES["fileToUpload"]["name"]);
        $target_file = "../images/" . round(microtime(true)) . '.' . end($temp);
    }

    if ($_FILES["fileToUpload"]["size"] > 2000000) { // > 2MB
        $error[0] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $error[0] = "Sorry, only JPG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
} else {
    $uploadOk = 1;
    $target_file = "../images/default.png";
    $moveImage = false;
}

// Check if $uploadOk is set to 0 by an error
if ($moveImage === true && $uploadOk === 1) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $message = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        $error[0] = "Sorry, there was an error uploading your file.";
        $uploadOk = 0;
    }
}

/* Save to database */
if(isset($_POST["username"]) && isset($_POST["email"])) {

    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $_SESSION["keepName"] = $username;
    $_SESSION["keepEmail"] = $email;

    /* Validation empty username and email */
    if(empty($_POST['username'])){
        array_push($error,  'Your name is required!');
        $success = false;
    }
    if(empty($_POST['email'])){
        array_push($error, 'Email is required!');
        $success = false;
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($error, 'Email is not valid format!');
            $success = false;
        }
    }
    if($profile->checkExistEmail($email)){
        array_push($error, 'Email is already exist');
        $success = false;
    }

    if($success === true && $uploadOk == 1) {
        $picture = $target_file;
        $success = $profile->addProfile($username, $email, $picture);
        if($success === false) {
            $message = "Something wrong when saving database!";
        } else {
            $message = "Create account successfully!";
        }
    }

    /* If have any error */
    if(sizeof($error)){
        $_SESSION["error"] = $error;
    } else {
        $_SESSION["message"] = $message;
    }

    $_SESSION["success"] = $success;

}

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.

    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

header('Location: user.php');
