<?php

/** Start session */
session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../libs/custom/handle/constantMessage.php');
require_once('../models/SeatMap.php');
require_once('../models/Utility.php');
require_once('../models/basicValidation.php');
require_once('../models/uploadImage.php');

/** Check authentication by session */
if (isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
} else {
    $utility->redirect('/seatMap/controllers/index.php');
}

/** Handle GET request */
if (isset($_GET['id'])) {
    $errorGET = [];
    $id = htmlspecialchars($_GET['id']);

    /** Validation id */
    $basicValidation = new BasicValidation();
    $basicValidation->validationId($id);

    /** If have any error */
    if ($basicValidation->getError()) {
        array_push($errorGET, $basicValidation->getError());
        $smarty->assign('errorGET', $errorGET);
    } else {
        /** Show data to seat map form */
        $seatMap = new SeatMap();
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

/** Handle POST request */
if (isset($_POST['id']) && isset($_POST['seatmapName'])) {
    $errorPOST = [];
    $id = intval($_POST['id']);
    $seatMapName = htmlspecialchars($_POST['seatmapName']);

    /** Validation seat map name and id */
    $basicValidation = new BasicValidation();
    $basicValidation->validationId($id);
    $basicValidation->validationNameExceptId($seatMapName, $id);

    /** Validation Image */
    $uploadImage = new UploadImage('fileToUpload');
    $seatMap =  new SeatMap();
    $oldImage = $seatMap->getOldPathImage($id);
    if($uploadImage->isUploadImage()) {
        $uploadImage->setMaxFileSize(10000);
        $uploadImage->setAllowExtension(['jpg', 'png', 'jpeg', 'gif']);
        $uploadImage->userValidation();
        $uploadImage->upload();

        if (!$uploadImage->getError() && !$basicValidation->getError()) {
            $success = $seatMap->updateSeatmapWithPath(
                $id,
                $seatMapName,
                $uploadImage->getImagePath(),
                $uploadImage->getImageSize(),
                $uploadImage->getImageType()
            );
            if (!$success) {
                array_push($errorPOST, _CAN_NOT_SAVE_DATABASE);
            } else {
                $smarty->assign('seatmapPath', $uploadImage->getImagePath());
                $uploadImage->removeOldFileAndMoveNewFile($oldImage, $uploadImage->getImagePath());
            }
        } else {
            if (sizeof($basicValidation->getError())) {
                array_push($errorPOST, $basicValidation->getError());
            }
            if ($uploadImage->getError()) {
                var_dump($uploadImage->getError());
                array_push($errorPOST, $uploadImage->getErrorDescription());
            }
            $smarty->assign('seatmapPath', $oldImage);
        }
    } else {
        if (!$basicValidation->getError()) {
            $success = $seatMap->updateSeatmapWithoutPath($id, $seatMapName);
            if (!$success) {
                array_push($errorPOST, _CAN_NOT_SAVE_DATABASE);
            } else {
                $smarty->assign('seatmapPath', $oldImage);
            }
        } else {
            array_push($errorPOST, $basicValidation->getError());
            $smarty->assign('seatmapPath', $oldImage);
        }
    }

    /** If have any error. Parse to view */
    if (sizeof($errorPOST)) {
        $smarty->assign('errorPOST', $errorPOST);
        $smarty->assign('success', false);
    } else {
        $smarty->assign('message', _UPDATE_SEAT_MAP_SUCCESS);
        $smarty->assign('success', true);
    }

    $smarty->assign('id', $id);
    $smarty->assign('seatmapName', $seatMapName);
}
$smarty->display('updateSeatmap.tpl');
