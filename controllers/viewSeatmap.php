<?php
session_start();

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/Seatmap.php');

$seatmap = new Seatmap();

if( isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: ../index.php');
    die();
}

$seatmapArray = $seatmap->listAllSeatmap();
echo json_encode($seatmapArray);