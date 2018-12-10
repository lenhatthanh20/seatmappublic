<?php

/* Start session */
session_start();

require_once('../models/Profile.php');

$profile = new Profile();

/* Check user session */
if( isset($_SESSION["username"])) {
    //$smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: ../index.php');
    die();
}

if(isset($_POST['jsonObject'])){
    $jsonObject = json_decode($_POST['jsonObject']);
    $flag = 0;
    foreach ($jsonObject as $value) {
        /* $position = '{"x":"12","y":"100"}' */
        $x = $value->x;
        $y = $value->y;
        $position = '{"x":"' . $x . '","y":"' . $y . '"}'; // JSON string

        $seatmapId = $value->seatmapID;
        $id = $value->id;

        $success = $profile->updateProfileToSeatmap($id, $position, $seatmapId);
        if($success){
            continue;
        } else {
            $flag++;
            break;
        }
    }

    if($flag === 0) {
        echo true;
    } else {
        echo false;
    }
}