<?php

require_once '../../utils/session.php';

if (!can('create-stages')){
    $_SESSION['warning'] = "UnAuthorized Operation";  
     header('Location:index.php');
      die;
}


require_once("../../utils/dbaccess.php");

require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");

require_once('../../controllers/StageController.php');


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$stage = new  Stage();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);



if (isset($_POST['name'])) {

    $_SESSION['errors'] = array();

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addStage') {
            continue;
        } else {
            if ($helpers->checkEmptyFields($value) != NULL) {
                $_SESSION['errors'][$key]  =  $key ." ". $helpers->checkEmptyFields($value);
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
        if ($stage->store($_POST)) {
            $activity->logActivity(
                $_SESSION['user'],
                "Registered stage ",
                "stage registered in sucessfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            //redirect
            $_SESSION['success'] = "Stage Added Successfully";
            //header("Location:index.php");
            echo "success";
            //redirect
        } else {
            $_SESSION['error'] = "Stage Was not created";

            header("Location:create.php");

        }
    }
} else {
    die("not set");
}
