<?php

session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../libs/custom/handle/constantMessage.php');
require_once('../models/SeatMap.php');
require_once('../models/handleImage.php');
require_once('../models/basicValidation.php');

/* Check session */
if (isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
} else {
    $utility->redirect('/seatMap/controllers/index.php');
}

/** Handle POST request */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $handleImage = new HandleImage();
    $basicValidation = new BasicValidation();

    $uploadFile = $_FILES['fileToUpload'];
    $seatMapName = htmlspecialchars($_POST['seatMapName']);

    /** Validation seat map name */
    $basicValidation->validationName($seatMapName);

    /** Handle and validation uploaded image */
    $handleImage->uploadImage($uploadFile);

    /** Get error from handleImage object and validation seat map name */
    $error = array_merge($basicValidation->getError(), $handleImage->getImageError());

    /** Save database if no error is detected */
    if (!sizeof($error)) {
        $seatMap = new SeatMap();

        /** Store image */
        $handleImage->moveFileUploaded($uploadFile);

        /** Save to database */
        $success = $seatMap->addSeatMap(
            $seatMapName,
            $handleImage->getImageType(),
            $handleImage->getImageSize(),
            $handleImage->getImagePath()
        );
        if (!$success) {
            array_push($error, _CAN_NOT_SAVE_DATABASE);
        }
    }

    /** If have any error */
    if (sizeof($error)) {
        $smarty->assign('error', $error);
        $smarty->assign('success', false);
    } else {
        $smarty->assign('message', _ADD_SEAT_MAP_SUCCESS);
        $smarty->assign('success', true);
    }

    /** Parse data to smarty */
    $smarty->assign('seatMapName', $_POST['seatMapName']);
}

$smarty->display('addSeatMap.tpl');