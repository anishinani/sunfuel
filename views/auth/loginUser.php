<?php
//start session
session_start();

require_once("../../utils/dbaccess.php");
require_once('../../controllers/User.php');
require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");
require_once "../../controllers/access/AccessController.php";

$user = new User();
$accessController =  new AccessController();
//helper functions
$helpers  = new HelperFunctions();
$dbAccess =  new DbAccess();
$activity = new ActivityLogger();


if (isset($_POST['setPassword'])) {


    $token =  $_POST['token'];

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
        header("location:setPassword.php?token=$token");
    }
    //check if passwords match

    //check email
    $validatedEmail    = $helpers->checkEmail($_POST['email']);
    if ($validatedEmail == NULL) {
        header('location:home.php');
    } else {
        ///$auth = $user->check_login($username, $password);

        $auth = $user->setPassword($validatedEmail, $_POST['password'], $_POST['id']);

        if ($auth  == NULL) {
            $_SESSION['message'] = 'Something went wrong';
            header("location:setPassword.php?token=$token");
        } else {
            // var_dump($auth);
            // die("here");
            $dbAccess->update("administrators", ['setPassword' => NULL], ['adminId' => $auth['adminId']]);
            $_SESSION['user'] = $auth['name'];
            $_SESSION['email'] = $auth['email'];
            $_SESSION['auth'] = $auth['adminId'];
            //get permissions
            $permissions = $accessController->getUserPermissions($auth['roleId']);
            $_SESSION['permissions'] = $permissions['permissions'];

            $_SESSION['modules'] = $permissions['modules'];
            
            $value = $activity->logActivity($_SESSION['user'], "set up ","Logged in successfully", $auth['email'], $auth['adminId']);
           
            $_SESSION['success'] = "Welcome ".$_SESSION['user'] . '!';
           
            header("location:../dashboard/");
        }
    }
}

// else{
// 	$_SESSION['message'] = 'You need to login first';
// 	header('location:index.php');
// }
