<?php
// Start the session
session_start();

require_once('../models/User.php');
require_once('../libs/custom/handle/passwordHash.php');
require_once('../libs/custom/smarty/smartyConfig.php');

$user = new User();
$passwordHash = new PasswordHash();

/* Variables that will be parsed to Smarty template */
$success = true;
$message = null;
$error = [];

/* Check session. If username session is valid
   => Login successfully => Redirect to dashboard.php */
if( isset($_SESSION["username"])) {
    header('Location: dashboard.php');
}

/* Handle POST request for login action */
if (isset($_POST['username']) && isset($_POST['password'])) {

    /* Validation empty username and password */
    if(empty($_POST['username'])){
        array_push($error,  'User name is required!');;
        $success = false;
    }
    if(empty($_POST['password'])){
        array_push($error, 'Password is required!');
        $success = false;
    }

    /* Prevent XSS attack - encode HTML input */
    /* And hash password */
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    if ($success === true) {

        /* Check username is exist or not */
        $isNotExist = $user->checkExistUsername($username);
        if(!$isNotExist){
            $array = $user->loginHandle($username);
            if ($username === $array[0][1] && $passwordHash->comparePassword($password, $array[0][2]) === true) {
                $message = "Login successfully";
                $success = true;
                $_SESSION["username"] = $username;
                header('Location: /seatMap/controllers/dashboard.php');
            } else {
                $message = "Password does not match";
                $success = false;
            }
        } else {
            array_push($error, 'Username is not exist!');
            $success = false;
        }
    }
    $smarty->assign('usernameInput', $username);

    /* If have any error */
    if(sizeof($error)){
        $smarty->assign('error', $error);
    } else {
        $smarty->assign('message', $message);
    }
    $smarty->assign('success', $success);
}
$smarty->display('login.tpl');

