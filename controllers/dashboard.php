<?php
session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/Profile.php');
require_once('../models/SeatMap.php');


$profile = new Profile();
$seatmap = new SeatMap();

global $_SESSION;

/* Check session */
if( isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: /seatmap/controllers/index.php');
    die();
}


/* Handling the message is parsed in deleteUser.php */
if( isset($_SESSION["success"]) && isset($_SESSION["message"]) ) {
    $smarty->assign('success', $_SESSION["success"]);
    $smarty->assign('message', $_SESSION["message"]);
    unset($_SESSION["success"]);
    unset($_SESSION["message"]);
}

/* List All Users */
$arrayAllProfile = $profile->listAllProfile();

/* List All SeatMap */
$arrayAllSeatmap = $seatmap->listAllSeatmap();

/* Parse data to smarty */
$smarty->assign('arrayAllProfile', $arrayAllProfile);
$smarty->assign('arrayAllSeatmap', $arrayAllSeatmap);

$smarty->display('dashboard.tpl');