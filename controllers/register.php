<?php

/* Start session */
session_start();

/* Include User Model */
require_once('../models/User.php');
/* Include Password hashing */
require_once('../libs/custom/handle/passwordHash.php');
/* Include Smarty configuration */
require_once('../libs/custom/smarty/smartyConfig.php');

/* Create User and PasswordHash object */
$user = new User();
$passwordHash = new PasswordHash();

/* Variables that will be parsed to Smarty template */
$success = true;
$message = null;
$error = [];

/* Check session validation. If user do not login => Redirect to homepage. */
if( isset($_SESSION["username"])) {
    $smarty->assign('username', $_SESSION["username"]);
}else {
    header('Location: ../index.php');
    die();
}

/* If user logon. Handling POST request to create user account. */
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2'])) {
    /* Validation empty username and password */

    if(empty($_POST['username'])){
        array_push($error,  'User name is required!');
        $success = false;
    } else {
        if(!preg_match('/^[a-zA-Z0-9]{6,26}$/', $_POST['username'])) {
            // valid username, alphanumeric & longer than or equals 5 chars
            array_push($error,  'Username can include alphanumeric, number, greater than 5 and less than 25 chars!');
            $success = false;
        }
    }
    if(empty($_POST['password'])){
        array_push($error, 'Password is required!');
        $success = false;
    }
    if(empty($_POST['password2'])){
        array_push($error, 'Confirm password is required!');
        $success = false;
    }

    /* Check password is equal or not with password2 */
    if($_POST['password'] !== $_POST['password2']) {
        $success = false;
        array_push($error, 'Password does not match!');
    }

    /* Prevent XSS attack - encode HTML input */
    /* And hash password */
    $username = htmlspecialchars($_POST['username']);
    $password = $passwordHash->hashPassword(htmlspecialchars($_POST['password']));
    /* If there are no error, handle to database. */
    if($success === true){

        /* Check username is exist or not */
        $isNotExist = $user->checkExistUsername($username);
        if($isNotExist){

            /* Save to database */
            $success = $user->addUser($username, $password);
            if($success === false) {
                $message = "Something wrong when saving database!";
            } else {
                $message = "Create account successfully!";
            }
        } else {
            array_push($error, 'Username is already exist!');
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

$smarty->display('register.tpl');
