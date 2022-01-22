<?php
include_once '../../utils/session.php';

if (!can('create-fuelstation')){
     $_SESSION['warning'] = "UnAuthorized Operation";  
      header('Location:index.php');
       die;
    }


require_once("../../utils/dbaccess.php");

require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");

require_once('../../controllers/FuelStationController.php');


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$fuelStation = new FuelStation();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);


//var_dump($_POST);
if (isset($_POST['district'])) {
    //var_dump($_FILES);
    //Sdie("am here");

    //die("here");

    $_SESSION['errors'] = array();

    //var_dump($_POST);
    //die("here");

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
        if ($fuelStation->store($_POST, $photoTwo, $photoOne)) {
            $activity->logActivity(
                $_SESSION['user'],
                "Registered fuel station",
                "fuel station registered in sucessfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            //redirect
            $_SESSION['success'] = "Fuel Station Added Successfully";
            //header("Location:index.php");
            echo "success";
            //redirect
        } else {
            //die("Oops there was an error");
            $_SESSION['success'] = "Oops there was an error !! please try again";
            header("Location:index.php");
        }
    }
} else {
    die("not set");
}
