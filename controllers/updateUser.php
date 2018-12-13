<?php
// Start the session
session_start();
require_once('../models/Profile.php');
require_once('../libs/custom/smarty/smartyConfig.php');

$profile = new Profile();

/* Variables that will be parsed to Smarty template */
$success = true;
$message = null;
$error = [];

/* Check authentication by session */
if( isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: /seatmap/controllers/index.php');
    die();
}

/* For Get Request - Show information before updating */
if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $success = $profile->checkValidId($id);
    if(!$success){
        array_push($error, "ID is not valid!");
        $success = false;
    }

    if($success === false) {
        // failure
        $smarty->assign('error', $error);
        $smarty->assign('success', $success);
    } else {
        $arrayUser = $profile->selectProfile($id);
        $userName = $arrayUser[0][1];
        $email = $arrayUser[0][2];
        $imagePath = $arrayUser[0][3];

        $smarty->assign('id', $id);
        $smarty->assign('userName', $userName);
        $smarty->assign('email', $email);
        $smarty->assign('imagePath', $imagePath);


    }

    $smarty->display('updateUser.tpl');
}

/* For POST request - Update data to database */
if(isset($_POST["id"]) && isset($_POST["username"]) && isset($_POST["email"])) {
    $id = htmlspecialchars($_POST["id"]);
    $imagePath = htmlspecialchars($_POST["path"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);

    // Parse to smarty to keep data in the form
    $smarty->assign('id', $id);
    $smarty->assign('userName', $username);
    $smarty->assign('email', $email);
    $smarty->assign('imagePath', $imagePath);

    /* Validation the length of name field */
    $len = strlen($username);
    if($len < 6){
        array_push($error,  'Your name must be between 6 and 25 chars!');
        $success = false;
    }
    elseif($len > 25){
        array_push($error,  'Your name must be between 6 and 25 chars!');
        $success = false;
    }

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
    if($profile->checkExistEmailExceptId($email, $id)){
        array_push($error, 'Email is already exist');
        $success = false;
    }

    if($success === true) {
        if ($_FILES["fileToUpload"]["tmp_name"] === "") { // if not change avatar
            $success = $profile->updateProfile($id, $username, $email, $imagePath);
            if($success === true) {
                $message = "Update user successfully!";
            }
            $smarty->assign('imagePath', $imagePath);
        } else { // if changed avatar

            $target_dir = "../images/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 0;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;  // file is select in the input field
            } else {
                array_push($error, "File is not an image.");
                $uploadOk = 0;
            }

            if (file_exists($target_file)) {
                // Rename the file when file is exist
                $temp = explode(".", $_FILES["fileToUpload"]["name"]);
                $target_file = "../images/" . round(microtime(true)) . '.' . end($temp);
            }

            if ($_FILES["fileToUpload"]["size"] > 2000000) { // > 2MB
                array_push($error, "Sorry, your file is too large.");
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif") {
                array_push($error, "Sorry, only JPG, PNG & GIF files are allowed.");
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $message = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                    $success = false;
                }
            }

            // Save to database
            if ($uploadOk == 1 && $success = true) {
                //remove old image
                if(file_exists($imagePath)) {
                    unlink($imagePath);
                    $success = $profile->updateProfile($id, $username, $email, $target_file);
                } else {
                    array_push($error, "Profile is already upload!");
                    $success = false;
                }

                if ($success) {
                    $message = "Update user successfully!";
                }
                $smarty->assign('imagePath', $target_file);
            }
        }
    }

    if($success === true) {
        $smarty->assign('message', $message);
    } else {
        $smarty->assign('error', $error);
    }
    $smarty->assign('success', $success);
    $smarty->display('updateUser.tpl');

}
