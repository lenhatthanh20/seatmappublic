<?php

/* Start session */
session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../libs/custom/handle/constantMessage.php');
require_once('../models/SeatMap.php');
require_once('../models/Utility.php');
require_once('../models/basicValidation.php');
require_once('../models/imageValidation.php');

$seatMap = new SeatMap();
$utility = new Utility();
$imageValidation = new ImageValidation();
$basicValidation = new BasicValidation();

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
        array_push($errorGET,  _ID_REQUIRED);
    }

    /* Check ID is exist or not */
    if(!$seatMap->checkValidId($id)) {
        array_push($errorGET,  _ID_NOT_EXIST);
    }

    if(sizeof($errorGET)){
        $smarty->assign('errorGET', $errorGET);
    } else {
        /* Show data to seat map form */
        $arraySeatMap = $seatMap->selectSeatmap($id);
        if($arraySeatMap === false){
            array_push($errorPOST,  'Query database failure.');
        }
        $smarty->assign('id', $id);
        $smarty->assign('seatmapName', $arraySeatMap[0][2]);
        $smarty->assign('seatmapPath', $arraySeatMap[0][1]);
    }
}

/* Handle POST request */
if(isset($_POST['id']) && isset($_POST['seatmapName'])){
    $id = htmlspecialchars($_POST['id']);
    $seatMapName = htmlspecialchars($_POST['seatmapName']);
    $oldImage = null;

    /* Validation length of seat map name */
    if (!$basicValidation->lengthOfName($seatMapName, _MIN_LENGTH, _MAX_LENGTH)) {
        array_push($errorPOST, _LENGTH_INVALID);
        $success = false;
    }

    /* Validation empty id and seat map name */
    if(empty($_POST['id'])){
        array_push($errorPOST,  _ID_REQUIRED);
        $success = false;
    } else {
        $arraySeatMap = $seatMap->selectSeatmap($id);
        if($arraySeatMap === false){
            array_push($errorPOST,  _QUERY_FAILURE);
        }
        $oldImage = $arraySeatMap[0][1];
        $smarty->assign('seatmapPath', $oldImage);
    }

    /* Validation empty seatMap Name */
    if(empty($seatMapName)){
        array_push($errorPOST,  _SEAT_MAP_NAME_REQUIRED);
        $success = false;
    }

    /* Check the new seat map name is exist or not */
    if($seatMap->checkExistNameExceptId($seatMapName, $id)){
        array_push($errorPOST,  _SEAT_MAP_NAME_EXIST);
        $success = false;
    }

    /* Check image is upload or not */
    if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {

        $uploadFile = $_FILES['fileToUpload'];
        $filePath = _IMAGE_SEAT_MAP_DIR . $utility->cleanSpecialChars(basename($uploadFile['name']));
        $imageFileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        /* Rename image when file name is exist */
        if (file_exists($filePath)) {
            $filePath = $imageValidation->renameFileWhenExist($uploadFile['name']);
        }

        /* Validation image */
        $errorPOST = $imageValidation->imageValidationFull($uploadFile, $imageFileType);
        if (sizeof($errorPOST)) {
            $success = false;
        }

        /* No error when upload image. Save database with new image */
        if($success === true){
            if($seatMap->updateSeatmapWithPath($id, $seatMapName, $filePath, $uploadFile['size'], $imageFileType)){
                $smarty->assign('message', _UPDATE_SEAT_MAP_SUCCESS);
                //remove old image
                if(file_exists($oldImage)) {
                    unlink($oldImage);
                }
                move_uploaded_file($uploadFile['tmp_name'], $filePath);
            } else {
                array_push($errorPOST, _CAN_NOT_SAVE_DATABASE);
            }

            $smarty->assign('seatmapPath', $filePath);
        }
    } else { /* Save database with old image */
        if($success === true) {
            if($seatMap->updateSeatmapWithoutPath($id, $seatMapName)){
                $smarty->assign('message', _UPDATE_SEAT_MAP_SUCCESS);
            } else {
                array_push($errorPOST, _CAN_NOT_SAVE_DATABASE);
            }
        }
    }

    if(sizeof($errorPOST)){
        $smarty->assign('errorPOST', $errorPOST);
    }

    $smarty->assign('id', $id);
    $smarty->assign('seatmapName', $seatMapName);

    $smarty->assign('success', $success);
}

$smarty->display('updateSeatmap.tpl');
