<?php

/* Start session */
session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/Seatmap.php');

$seatmap = new Seatmap();

/* Check authentication by session */
if(isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: /seatmap/controllers/index.php');
    die();
}

/* Variables that will be parsed to Smarty template */
$success = true;
$message = null;
$errorGET = [];
$errorPOST = [];

/* Handle GET request */
if(isset($_GET['id'])){
    $id = htmlspecialchars($_GET['id']);

    /* Validation empty username and email */
    if(empty($_GET['id'])){
        array_push($errorGET,  'ID is required!');
    }

    /* Check ID is exist or not */
    if(!$seatmap->checkValidId($id)) {
        array_push($errorGET,  'ID is not exist!');
    }

    if(sizeof($errorGET)){
        $smarty->assign('errorGET', $errorGET);
    } else {
        /* Show data to seatmap form */
        $arraySeatmap = $seatmap->selectSeatmap($id);
        if($arraySeatmap === false){
            array_push($errorPOST,  'Query database failure.');
        }
        $smarty->assign('id', $id);
        $smarty->assign('seatmapName', $arraySeatmap[0][2]);
        $smarty->assign('seatmapPath', $arraySeatmap[0][1]);
    }
}

/* Handle POST request */
if(isset($_POST['id']) && isset($_POST['seatmapName'])){
    $id = htmlspecialchars($_POST['id']);
    $seatmapName = htmlspecialchars($_POST['seatmapName']);
    $smarty->assign('id', $id);
    $smarty->assign('seatmapName', $seatmapName);
    $oldImage = null;

    /* Validation length of seat map name */
    $len = strlen($seatmapName);
    if($len < 6){
        array_push($errorPOST,  'Seat map name must be between 6 and 25 chars!');
        $success = false;
    }
    elseif($len > 25){
        array_push($errorPOST,  'Seat map name must be between 6 and 25 chars!');
        $success = false;
    }

    /* Validation empty id and seatmap name */
    if(empty($_POST['id'])){
        array_push($errorPOST,  'ID is required!');
        $success = false;
    } else {
        $arraySeatmap = $seatmap->selectSeatmap($id);
        if($arraySeatmap === false){
            array_push($errorPOST,  'Query database failure.');
        }
        $oldImage = $arraySeatmap[0][1];
        $smarty->assign('seatmapPath', $arraySeatmap[0][1]);
    }
    if(empty($_POST['seatmapName'])){
        array_push($errorPOST,  'Seatmap name is required!');
        $success = false;
    }

    /* Check the new seatmap name is exist or not */
    if($seatmap->checkExistNameExceptId($seatmapName, $id)){
        array_push($errorPOST,  'Seatmap name is exist in database!');
        $success = false;
    }

    /* Check image is upload or not */
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

        /* No error when upload image */
        if($success === true && $uploadOk === 1){
            $smarty->assign('seatmapPath', $target_file);
            if($seatmap->updateSeatmapWithPath($id, $seatmapName, $target_file, $_FILES["fileToUpload"]["size"], $imageFileType)){
                $message = "Update seatmap seccessfully.";
                //remove old image
                if(file_exists($oldImage)) {
                    unlink($oldImage);
                }
                move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            } else {
                $message = "Something wrong when update database.";
            }
        }
    } else { /* User the old image */
        if($success === true) { /* No error. Update database */
            if($seatmap->updateSeatmapWithoutPath($id, $seatmapName)){
                $message = "Update seatmap seccessfully.";
            } else {
                $message = "Something wrong when update database.";
            }
        }
    }

    if(sizeof($errorPOST)){
        $smarty->assign('errorPOST', $errorPOST);
    } else {
        $smarty->assign('message', $message);
    }
    $smarty->assign('success', $success);
}

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.

    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

$smarty->display('updateSeatmap.tpl');
