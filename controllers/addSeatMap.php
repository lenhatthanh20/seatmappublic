<?php

session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../libs/custom/handle/constantMessage.php');
require_once('../models/SeatMap.php');
require_once('../models/uploadImage.php');
require_once('../models/basicValidation.php');

/* Check session */
if (isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
} else {
    $utility->redirect('/seatMap/controllers/index.php');
}

/** Handle POST request */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = [];

    $seatMapName = htmlspecialchars($_POST['seatMapName']);
    /** Validation seat map name */
    $basicValidation = new BasicValidation();
    $basicValidation->validationName($seatMapName);

    $uploadImage = new UploadImage('fileToUpload');
    $uploadImage->setIsRequired();
    /** Validation Image */
    $uploadImage->setMaxFileSize(10000);
    $uploadImage->setAllowExtension(['jpg', 'png', 'jpeg', 'gif']);
    $uploadImage->userValidation();
    $uploadImage->upload();

    if (!$uploadImage->getError() && !$basicValidation->getError()) {
        $seatMap = new SeatMap();
        $success = $seatMap->addSeatMap(
            $seatMapName,
            $uploadImage->getImageType(),
            $uploadImage->getImageSize(),
            $uploadImage->getImagePath()
        );
        if (!$success) {
            array_push($error, _CAN_NOT_SAVE_DATABASE);
        }
    } else {
        if(sizeof($basicValidation->getError())){
            array_push($error, $basicValidation->getError());
        }
        if($uploadImage->getError()){
            array_push($error, $uploadImage->getErrorDescription());
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