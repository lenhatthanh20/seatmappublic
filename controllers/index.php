<?php

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/Profile.php');
require_once('../models/SeatMap.php');

$profile = new Profile();
$seatmap = new SeatMap();

/* List All Users */
$arrayAllProfile = $profile->listAllProfile();

/* List All SeatMap */
$arrayAllSeatmap = $seatmap->listAllSeatmap();

/* Parse data to smarty */
$smarty->assign('arrayAllProfile', $arrayAllProfile);
$smarty->assign('arrayAllSeatmap', $arrayAllSeatmap);

$smarty->display('index.tpl');