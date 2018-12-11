<?php

session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/Seatmap.php');

$seatmap = new Seatmap();

if( isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: /seatmap/controllers/index.php');
    die();
}
/* default variable */
$seatmapName = null;
$seatmapType = null;
$seatmapSize = null;
$seatmapPath = null;
$target_file = null;
$imageFileType = null;
$uploadOk = 0;
$success = true;
$message = null;
$error = [];

/* handle image */
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {

        $target_dir = "../images/seatmap/";
        $target_file = $target_dir . clean(basename($_FILES["fileToUpload"]["name"]));

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;  // file is select in the input field
        } else {
            $error[0] = "File is not an image.";
            $uploadOk = 0;
            $success = false;
        }

        if (file_exists($target_file)) {
            // Rename the file when file is exist
            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
            $target_file = "../images/seatmap/" . round(microtime(true)) . '.' . end($temp);
        }

        if ($_FILES["fileToUpload"]["size"] > 2000000) { // > 2MB
            $error[0] = "Sorry, your file is too large.";
            $uploadOk = 0;
            $success = false;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $error[0] = "Sorry, only JPG, PNG & GIF files are allowed.";
            $uploadOk = 0;
            $success = false;
        }
    } else {
        $error[0] = "Please upload seatmap image";
        $uploadOk = 0;
        $success = false;
    }

    $smarty->assign('seatmapName', $_POST['seatmapName']);
}
/* Handle POST request */
if(isset($_POST['seatmapName']) && $uploadOk === 1) {

    /* Set variables for database */
    $seatmapName = htmlspecialchars($_POST['seatmapName']);
    $seatmapType = $imageFileType;
    $seatmapSize = $_FILES["fileToUpload"]["size"];
    $seatmapPath = $target_file;
    $smarty->assign('imagePath', $seatmapPath);


    /* Validation length of seat map name */
    $len = strlen($seatmapName);
    if($len < 6){
        array_push($error,  'Seat map name must be between 6 and 25 chars!');
        $success = false;
    }
    elseif($len > 25){
        array_push($error,  'Seat map name must be between 6 and 25 chars!');
        $success = false;
    }
    /* Validation empty seatmapName */
    if (empty($_POST['seatmapName'])) {
        array_push($error, 'Seatmap name is required!');
        $success = false;
    }

    /* Check exist seat map name */
    if($seatmap->checkExistName($seatmapName)){
        array_push($error, 'Seatmap name is exist.');
        $success = false;
    }
    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $seatmapPath)) {
        $error[0] = "Sorry, there was an error uploading your file.";
        $success = false;
    }
    if ($success) {
        $success = $seatmap->addSeatmap($seatmapName, $seatmapType, $seatmapSize, $seatmapPath);
        if ($success) {
            $message = 'Add seatmap successfully!';
        } else {
            array_push($error, 'Can not save to database.');
        }
    }
}
/* If have any error */
if(sizeof($error)){
    $smarty->assign('error', $error);

} else {
    $smarty->assign('message', $message);
}

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.

    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

/* Parse data to smarty */
$smarty->assign('success', $success);
$smarty->display('addSeatmap.tpl');