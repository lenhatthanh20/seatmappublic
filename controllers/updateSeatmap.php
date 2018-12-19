<?php

/* Start session */
session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../libs/custom/handle/constantMessage.php');
require_once('../models/SeatMap.php');
require_once('../models/Utility.php');
require_once('../models/basicValidation.php');
require_once('../models/handleImage.php');

/* Check authentication by session */
if (isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
} else {
    $utility->redirect('/seatMap/controllers/index.php');
}

/* Handle GET request */
if (isset($_GET['id'])) {
    $basicValidation = new BasicValidation();
    $seatMap = new SeatMap();

    $id = htmlspecialchars($_GET['id']);

    /** Validation id */
    $basicValidation->validationId($id);
    /** Get error if have any */
    $getError = $basicValidation->getError();

    /** If have any error */
    if (sizeof($getError)) {
        $smarty->assign('errorGET', $getError);
    } else {
        /** Show data to seat map form */
        $arraySeatMap = $seatMap->selectSeatmap($id);
        if ($arraySeatMap === false) {
            array_push($errorGET, 'Query database failure.');
            $smarty->assign('errorGET', $errorGET);
        }

        $smarty->assign('id', $id);
        $smarty->assign('seatmapName', $arraySeatMap[0][2]);
        $smarty->assign('seatmapPath', $arraySeatMap[0][1]);
    }
}

/* Handle POST request */
if (isset($_POST['id']) && isset($_POST['seatmapName'])) {
    $basicValidation = new BasicValidation();
    $handleImage = new HandleImage();
    $seatMap = new SeatMap();

    $uploadFile = $_FILES['fileToUpload'];
    $id = htmlspecialchars($_POST['id']);
    $seatMapName = htmlspecialchars($_POST['seatmapName']);
    $oldImage = $seatMap->getOldPathImage($id);

    /** Validation seatMap Name */
    $basicValidation->validationNameExceptId($seatMapName, $id);

    /** Validation id */
    $basicValidation->validationId($id);

    /** Handle and validation uploaded image */
    $notUseOldImage = $handleImage->uploadImageExceptOldImage($uploadFile);

    /** Get error from handleImage object, validation seat map name and ID */
    $errorPOST = array_merge($basicValidation->getError(), $handleImage->getImageError());

    /** Save database with new image and no error */
    if ($notUseOldImage && (!sizeof($errorPOST))) {
        if ($seatMap->updateSeatmapWithPath($id, $seatMapName, $handleImage->getImagePath(), $handleImage->getImageSize(), $handleImage->getImageType())) {
            /** Save new image and remove old image */
            $handleImage->removeOldFileAndMoveNewFile($uploadFile, $oldImage, $handleImage->getImagePath());
        } else {
            array_push($errorPOST, _CAN_NOT_SAVE_DATABASE);
        }

        $smarty->assign('seatmapPath', $handleImage->getImagePath());
    /** Save database with old image and no error */
    } elseif ((!$notUseOldImage) && (!sizeof($errorPOST))) {
        if (!$seatMap->updateSeatmapWithoutPath($id, $seatMapName)) {
            array_push($errorPOST, _CAN_NOT_SAVE_DATABASE);
        }
        $smarty->assign('seatmapPath', $oldImage);
    }

    /** If have any error. Parse to view */
    if (sizeof($errorPOST)) {
        $smarty->assign('errorPOST', $errorPOST);
        $smarty->assign('success', false);
        $smarty->assign('seatmapPath', $oldImage);
    } else {
        $smarty->assign('message', _UPDATE_SEAT_MAP_SUCCESS);
        $smarty->assign('success', true);
    }

    $smarty->assign('id', $id);
    $smarty->assign('seatmapName', $seatMapName);
}
$smarty->display('updateSeatmap.tpl');
