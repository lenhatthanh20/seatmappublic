<?php

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/SeatMap.php');

$seatmap = new SeatMap();

/* Load all seatmaps from database */
$seatmapArray = $seatmap->listAllSeatmap();

/* Echo to client */
echo json_encode($seatmapArray);