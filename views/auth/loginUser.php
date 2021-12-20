<?php
//start session
session_start();

require_once("../../utils/dbaccess.php");
require_once('../../controllers/User.php');
require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");
require_once("../../controllers/RolesController.php");

$user = new User();
$roles =  new Roles();
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
            $_SESSION['userId'] = $auth['adminId'];
            $_SESSION['roleId'] = $auth['roleId'];
            $_SESSION['gender'] = $auth['gender'];
            $_SESSION['email'] = $auth['email'];
            //get permissions
            $permissions = $roles->getSpecificPermissions($auth['roleId']);
            $_SESSION['roles'] = $permissions;
            $value = $activity->logActivity($_SESSION['user'], "set up ", "Logged in sucessfully", $_SESSION['email'], $_SESSION['gender']);

            header("location:../home.php");
        }
    }
}

// else{
// 	$_SESSION['message'] = 'You need to login first';
// 	header('location:index.php');
// }
