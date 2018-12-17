<?php
session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/Profile.php');
require_once('../models/SeatMap.php');
require_once('../models/Utility.php');


$profile = new Profile();
$seatMap = new SeatMap();
$utility = new Utility();

/* Check session */
if( isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
} else {
    $utility->redirect('/seatMap/controllers/index.php');
}

/* List All Users */
$arrayAllProfile = $profile->listAllProfile();

/* List All SeatMap */
$arrayAllSeatMap = $seatMap->listAllSeatmap();

/* Parse data to smarty */
$smarty->assign('arrayAllProfile', $arrayAllProfile);
$smarty->assign('arrayAllSeatmap', $arrayAllSeatMap);

$smarty->display('dashboard.tpl');