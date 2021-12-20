<?php
session_start();


require_once("../../utils/dbaccess.php");

require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");

require_once('../../controllers/User.php');


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$helpers =  new HelperFunctions();
$user =  new User();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);



if (isset($_POST['addUser'])) {

    $_SESSION['errors'] = array();

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addUser') {
            continue;
        } else {
            if ($helpers->checkEmptyFields($value) != NULL) {
                array_push($_SESSION['errors'],   $key . " " . $helpers->checkEmptyFields($value));
            } else {
                $dbAccess->clean($value);
            }
        }
    }
    //check errors and clean



    //check session array
    if (count($_SESSION['errors'])) {

        header("Location:create.php");
    }
    //check session array
    else {
        unset($_SESSION['errors']);
        if ($user->store($_POST)) {
            $activity->logActivity(
                $_SESSION['user'],
                "Registered user ",
                "user registered in sucessfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            //redirect
            $_SESSION['success'] = "user Added Successfully";
            header("Location:index.php");
            //redirect
        } else {
            //die("Oops there was an error");
            $_SESSION['success'] = "An error occured !Please try again";
            header("Location:index.php");
        }
    }
} else {
    //die("not set");
    $_SESSION['success'] = "Something went wrong Please contact Support";
    header("Location:index.php");
}
