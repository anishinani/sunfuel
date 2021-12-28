<?php
session_start();


require_once("../../utils/dbaccess.php");

require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");
require_once("../../controllers/DepositController.php");



//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$deposit = new Deposit();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);



if (isset($_POST['addDeposit'])) {

    $_SESSION['errors'] = array();

    //store images

    $frontPhoto = $_FILES["receipt"]["name"];
    $tempFrontPhoto = $_FILES["receipt"]["tmp_name"];
    $photoTwo =  time() . str_replace(" ", "_", $frontPhoto);

    //die($photoTwo);

    if (move_uploaded_file($tempFrontPhoto, "images/" . $photoTwo)) {
    } else {
        //die("Failed to move image");
        $_SESSION['success'] = "Wrong image format not supported";
        header("Location:index.php");
    }
    //store images

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addDeposit') {
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

    // if ($helpers->checkEmail($_POST['email']) == NULL) {
    //     array_push($_SESSION['errors'], "invalid email format");
    // }
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
        if ($deposit->store($_POST, $photoTwo, $photoOne)) {
            $activity->logActivity(
                $_SESSION['user'],
                "Added deposit",
                "Deposit added sucessfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            //redirect
            $_SESSION['success'] = "Deposit Successfully";
            header("Location:index.php");
            //redirect
        } else {
            die("Oops there was an error");
        }
    }
} else {
    die("not set");
}
