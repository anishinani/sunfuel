<?php
session_start();


require_once("../../utils/dbaccess.php");

require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");

require_once('../../controllers/PackageController.php');


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$package = new Package();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);



if (isset($_POST['addStation'])) {

    $_SESSION['errors'] = array();

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addStation') {
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


    //die($_POST['phoneNumber']);
    //die($helpers->checkNumber($_POST['phoneNumber']));

    // if ($helpers->checkNumber($_POST['phoneNumber']) == NULL) {
    //     array_push($_SESSION['errors'], "phone number must be 10 characters long");
    // }
    //check session array
    if (count($_SESSION['errors'])) {

        header("Location:create.php");
    }
    //check session array
    else {
        unset($_SESSION['errors']);
        if ($package->store($_POST)) {
            $activity->logActivity(
                $_SESSION['user'],
                "package registration",
                "packaged added sucessfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            //redirect
            $_SESSION['success'] = "Package Added Successfully";
            header("Location:index.php");
            //redirect
        } else {
            die("Oops there was an error");
        }
    }
} else {
    die("not set");
}
