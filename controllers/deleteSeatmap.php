<?php
session_start();
require_once('../models/SeatMap.php');
require_once('../models/Utility.php');

$seatMap = new SeatMap();
$utility = new Utility();

/* Check session */
if(!isset($_SESSION["username"])) {
    $utility->redirect('/seatMap/controllers/index.php');
}

if(isset($_POST['id']) && isset($_POST['path'])) {
    $id = htmlspecialchars($_POST['id']);
    $path = htmlspecialchars($_POST['path']);

    /* Check id is exist in database or not */
    $isExist = $seatMap->checkValidId($id);
    if(!$isExist){
        echo false;
    } else {
        $success = $seatMap->deleteSeatmap($id);
        if($success === true) {
            unlink($path);
            echo true;
        }
    }
}

