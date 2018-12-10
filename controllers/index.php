<?php

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/Profile.php');
require_once('../models/Seatmap.php');


$profile = new Profile();
$seatmap = new Seatmap();

/* List All Users */
$arrayAllProfile = $profile->listAllProfile();

/* List All Seatmap */
$arrayAllSeatmap = $seatmap->listAllSeatmap();
//var_dump($arrayAllUser);
$smarty->assign('arrayAllProfile', $arrayAllProfile);
$smarty->assign('arrayAllSeatmap', $arrayAllSeatmap);

$smarty->display('index.tpl');