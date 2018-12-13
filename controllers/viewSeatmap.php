<?php

require_once('../libs/custom/smarty/smartyConfig.php');
require_once('../models/Seatmap.php');

$seatmap = new Seatmap();

/* Load all seatmaps from database */
$seatmapArray = $seatmap->listAllSeatmap();

/* Echo to client */
echo json_encode($seatmapArray);