<?php

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/Seatmap.php');

$seatmap = new Seatmap();

$seatmapArray = $seatmap->listAllSeatmap();
echo json_encode($seatmapArray);