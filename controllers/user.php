<?php

//$_FILES;

//move_uploaded_file()
session_start();

require_once('../libs/custom/smarty/smartyConfig.php');

if( isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: ../index.php');
    die();
}

if(isset($_SESSION["message"]) && isset($_SESSION["success"])) {
    $smarty->assign('message', $_SESSION["message"]);
    $smarty->assign('success', $_SESSION["success"]);
    unset($_SESSION["message"]);
    unset($_SESSION["success"]);
}

if(isset($_SESSION["error"]) && isset($_SESSION["success"])) {
    $smarty->assign('error', $_SESSION["error"]);
    $smarty->assign('success', $_SESSION["success"]);
    unset($_SESSION["error"]);
    unset($_SESSION["success"]);
}

if(isset($_SESSION["keepName"]) && isset($_SESSION["keepEmail"])) {
    $smarty->assign('keepName', $_SESSION["keepName"]);
    $smarty->assign('keepEmail', $_SESSION["keepEmail"]);
    unset($_SESSION["keepName"]);
    unset($_SESSION["keepEmail"]);
}

$smarty->display('user.tpl');