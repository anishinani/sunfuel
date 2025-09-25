<?php

require_once '../../utils/session.php';

if (!can('create-bodausers')){
    $_SESSION['warning'] = "UnAuthorized Operation";  
     header('Location:index.php');
      die;
}


require_once("../../utils/dbaccess.php");
require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");
require_once('../../controllers/BodaUserController.php');


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$bodauser = new BodaUser();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);



if (isset($_POST['name'])) {

    $_SESSION['errors'] = array();
    //var_dump($_FILES);

    $backPhoto = $_FILES["backPhoto"]["name"];
    $frontPhoto = $_FILES["frontPhoto"]["name"];
    $tempBackPhoto = $_FILES["backPhoto"]["tmp_name"];
    $tempFrontPhoto = $_FILES["frontPhoto"]["tmp_name"];

    $photoOne =  time() . str_replace(" ", "_", $backPhoto);
    $photoTwo =  time() . str_replace(" ", "_", $frontPhoto);

    $backUploadSuccess = move_uploaded_file($tempBackPhoto, "images/" . $photoOne);
    $frontUploadSuccess = move_uploaded_file($tempFrontPhoto, "images/" . $photoTwo);
    
    if (!$backUploadSuccess || !$frontUploadSuccess) {
        $_SESSION['error'] = "Failed to upload images. Please check file permissions and try again.";
        header("Location:create.php");
        exit();
    }





    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addBodaUser' || 'anotherNumber') {
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
        $result = $bodauser->store($_POST, $photoTwo, $photoOne);
        
        if ($result) {
            $activity->logActivity(
                $_SESSION['user'],
                "Registered Boda user",
                "boda user registered successfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            $_SESSION['success'] = "Boda User Added Successfully";
            echo "success";
        } else {
            $_SESSION['error'] = "Failed to create boda user. Please check all required fields and try again.";
            echo "error";
        }
    }
} else {
    die("not set");
}
