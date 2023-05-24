<?php
include_once '../../utils/session.php';

if (!can('edit-fuelstation')){
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



if (isset($_POST['addStation'])) {

    $_SESSION['errors'] = array();

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addStation' || $key == "id") {
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

    if (count($_SESSION['errors'])) {

        header("Location:edit.php?update=" . $_POST['id'] . "");
    }
    //check session array
    else {
        unset($_SESSION['errors']);
        if ($fuelStation->updateInfo($_POST)) {
            // $activity->logActivity(
            //     $_SESSION['user'],
            //     "Updated successfullt",
            //     "fuel station updated  sucessfully",
            //     $_SESSION['email'],
            //     $_SESSION['gender']
            // );

            //redirect
            $_SESSION['success'] = "Fuel Station Updated  Successfully";
            header("Location:index.php");
            //redirect
        } else {
            die("Oops there was an error");
        }
    }
} else {
    die("not set");
}
