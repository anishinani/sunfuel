<?php
include_once '../../utils/session.php';

if (!can('create-fuelagent')){
     $_SESSION['warning'] = "UnAuthorized Operation";  
      header('Location:index.php');
       die;
    }



require_once("../../utils/dbaccess.php");

require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");

require_once('../../controllers/StationAgent.php');


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$agent = new StationAgent();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);



if (isset($_POST['addAgent'])) {

    $_SESSION['errors'] = array();

    //store images
    $backPhoto = $_FILES["backPhoto"]["name"];
    $frontPhoto = $_FILES["frontPhoto"]["name"];
    $tempBackPhoto = $_FILES["backPhoto"]["tmp_name"];
    $tempFrontPhoto = $_FILES["frontPhoto"]["tmp_name"];

    $photoOne =  time() . str_replace(" ", "_", $backPhoto);
    $photoTwo =  time() . str_replace(" ", "_", $frontPhoto);

    // die($photoTwo);

    if (move_uploaded_file($tempBackPhoto, "images/" . $photoOne)) {
        if (move_uploaded_file($tempFrontPhoto, "images/" . $photoTwo)) {
        }
    } else {
        //die("Failed to move image");
        $_SESSION['success'] = "Wrong image format not supported";
        header("Location:index.php");
    }
    //store images

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addAgent') {
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
        if ($agent->store($_POST, $photoTwo, $photoOne)) {
            $activity->logActivity(
                $_SESSION['user'],
                "Registered Fuel Agent",
                "fuel agent registered sucessfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            //redirect
            $_SESSION['success'] = "Fuel Agent Added Successfully";
            header("Location:index.php");
            //redirect
        } else {
            die("Oops there was an error");
        }
    }
} else {
    die("not set");
}
