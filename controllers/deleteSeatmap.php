<?php
session_start();
require_once('../models/Seatmap.php');

$seatmap = new Seatmap();

if( isset($_SESSION["username"])) {

}else {
    header('Location: /seatmap/controllers/index.php');
    die();
}

/* Variable to parse to jquery */
$error = [];

if(isset($_POST['id']) && isset($_POST['path'])) {
    $id = htmlspecialchars($_POST['id']);
    $path = htmlspecialchars($_POST['path']);

    /* Check id is exist in database or not */
    $isExist = $seatmap->checkValidId($id);
    if(!$isExist){
        array_push($error, 'Id is not exist in database!');
        die();
    } else {
        $success = $seatmap->deleteSeatmap($id);
        if($success === true) {
            unlink($path);
            echo true;
        }
    }
}

