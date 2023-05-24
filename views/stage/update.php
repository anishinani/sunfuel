<?php

 try {
    
require_once '../../utils/session.php';

// if (!can('edit-stages')){
//     $_SESSION['warning'] = "UnAuthorized Operation";  
//      header('Location:index.php');
//       die;
// }

require_once("../../utils/dbaccess.php");

require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");

require_once('../../controllers/StageController.php');


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$stage = new Stage();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);



if (isset($_POST['addStage'])) {

    $_SESSION['errors'] = array();

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addStage' || $key == "id") {
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

    if (count($_SESSION['errors'])) {

        header("Location:edit.php?update=" . $_POST['id'] . "");
    }
    //check session array
    else {
        unset($_SESSION['errors']);
        if ($stage->updateInfo($_POST)) {
            // $activity->logActivity(
            //     $_SESSION['user'],
            //     "Updated successfullt",
            //     "stage  updated  sucessfully",
            //     $_SESSION['email'],
            //     $_SESSION['gender']
            // );

            //redirect
            $_SESSION['success'] = "Stage  Updated  Successfully";
            header("Location:index.php");
            //redirect
        } else {
            die("Oops there was an error");
        }
    }
} else {
    die("not set");
}
 } catch (\Throwable $th) {
    //throw $th;
    die($th->getMessage());
 }

 
