<?php
//start session
session_start();

require_once("../../utils/dbaccess.php");
require_once('../../controllers/User.php');
require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");

$user = new User();
//helper functions
$helpers  = new HelperFunctions();
$dbAccess =  new DbAccess();
$activity = new ActivityLogger();


if (isset($_POST['setPassword'])) {

    // $email = $_POST['email'];
    // $password = $_POST['password'];
    // $confirm = $_POST['confirm'];

    // if ($helpers->checkEmptyFields($password) != NULL || $helpers->checkEmptyFields($) != NULL) {
    //     header('location:home.php?empty=required');
    // }
    $_SESSION['errors'] = array();

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'setPassword') {
            continue;
        } else {
            if ($helpers->checkEmptyFields($value) != NULL) {
                array_push($_SESSION['errors'],   $key . " " . $helpers->checkEmptyFields($value));
            } else {
                $dbAccess->clean($value);
            }
        }
    }

    //check if passwords match
    if ($helpers->confirmPassword($_POST['password'], $_POST['confirm'])) {
        //die("passwords match");

    } else {
        $_SESSION['message'] = 'passwords dont match';
        header('location:setPassword.php');
    }
    //check if passwords match

    //check email
    $validatedEmail    = $helpers->checkEmail($_POST['email']);
    if ($validatedEmail == NULL) {
        header('location:home.php');
    } else {
        ///$auth = $user->check_login($username, $password);
        $auth = $user->check_login($validatedEmail, $password);

        if ($auth  == NULL) {
            $_SESSION['message'] = 'Invalid username or password';
            header('location:index.php?invalid=true');
        } else {
            $_SESSION['user'] = $auth['name'];
            $_SESSION['userId'] = $auth['adminId'];
            $_SESSION['roleId'] = $auth['role'];
            $_SESSION['gender'] = $auth['gender'];
            $_SESSION['email'] = $auth['email'];
            $_SESSION['bool'] = false;
            $value = $activity->logActivity($_SESSION['user'], "set up ", "Logged in sucessfully", $_SESSION['email'], $_SESSION['gender']);
            //die($value);
            // if ($value) {
            // 	die("inserted");
            // } else {
            // 	die("not inserted");
            // }
            //var_dump($value);
            //die($value);
            header('location:views/home.php');
        }
    }
}

// else{
// 	$_SESSION['message'] = 'You need to login first';
// 	header('location:index.php');
// }
