<?php

require_once('../models/Profile.php');

$profile = new Profile();

/* Handle for POST request. List all profile to client. */
if(isset($_POST['flag']) && $_POST['flag'] === 'true'){
    $allProfiles = $profile->listAllProfile();
    echo json_encode($allProfiles);
}