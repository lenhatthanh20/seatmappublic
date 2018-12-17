<?php

CONST _IMAGE_SIZE_10MB = 10000000;
CONST _MIN_LENGTH = 6;
CONST _MAX_LENGTH = 25;
CONST _IMAGE_SEAT_MAP_DIR = "../images/seatMap/";

session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../libs/custom/handle/constantMessage.php');
require_once('../models/SeatMap.php');
require_once('../models/Utility.php');
require_once('../models/imageValidation.php');
require_once('../models/basicValidation.php');

$seatMap = new SeatMap();
$utility = new Utility();
$imageValidation = new ImageValidation();
$basicValidation = new BasicValidation();

/* Check session */
if (isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
} else {
    $utility->redirect('/seatmap/controllers/index.php');
}

/* handle image */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $success = true;
    $error = [];

    /* Image is uploaded */
    if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {

        /* Local variables */
        $seatMapName = htmlspecialchars($_POST['seatMapName']);
        $uploadFile = $_FILES['fileToUpload'];
        $imageFilePath = _IMAGE_SEAT_MAP_DIR . $utility->cleanSpecialChars(basename($uploadFile["name"]));
        $imageFileType = strtolower(pathinfo($imageFilePath, PATHINFO_EXTENSION));

        /* Rename image when file name is exist */
        if (file_exists($imageFilePath)) {
            $filePath = $imageValidation->renameFileWhenExist($uploadFile["name"]);
        }

        /* Validation image */
        $error = $imageValidation->imageValidationFull($uploadFile, $imageFileType);
        if (sizeof($error)) {
            $success = false;
        }

        /* Validation length of seat map name */
        if (!$basicValidation->lengthOfName($seatMapName, _MIN_LENGTH, _MAX_LENGTH)) {
            array_push($error, _LENGTH_INVALID);
            $success = false;
        }

        /* Validation empty seatMapName */
        if (empty($_POST['seatMapName'])) {
            array_push($error, _SEAT_MAP_NAME_REQUIRED);
            $success = false;
        }

        /* Check exist seat map name */
        if ($seatMap->checkExistName($seatMapName)) {
            array_push($error, _SEAT_MAP_NAME_EXIST);
            $success = false;
        }

        /* Save database */
        if ($success) {
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $imageFilePath);
            $success = $seatMap->addSeatMap($seatMapName, $imageFileType, $uploadFile["size"], $imageFilePath);
            if (!$success) {
                array_push($error, _CAN_NOT_SAVE_DATABASE);
            } else {
                $smarty->assign('message', _ADD_SEAT_MAP_SUCCESS);
            }
        }
    } else {
        /* Image is not uploaded */
        array_push($error, _NOT_UPLOAD);
        $success = false;
    }

    $smarty->assign('seatMapName', $_POST['seatMapName']);

    /* If have any error */
    if (sizeof($error)) {
        $smarty->assign('error', $error);
    }

    /* Parse data to smarty */
    $smarty->assign('success', $success);
}

$smarty->display('addSeatMap.tpl');