<?php
session_start();


require_once("../../utils/dbaccess.php");

require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");

require_once('../../controllers/BodaUserController.php');


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$user = new BodaUser();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);



if (isset($_POST['addBodaUser'])) {

    $_SESSION['errors'] = array();

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addBodaUser' || $key == "id") {
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

        // header("Location:edit.php?update='" . $_POST['id'] . "'");
        header("Location:edit.php?update=" . $_POST['id'] . "");
    }
    //check session array
    else {
        unset($_SESSION['errors']);
        if ($user->updateInfo($_POST)) {
            $activity->logActivity(
                $_SESSION['user'],
                "Updated successfully",
                "boda user  updated  sucessfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            //redirect
            $_SESSION['success'] = "boda user  Updated  Successfully";
            header("Location:index.php");
            //redirect
        } else {
            die("Oops there was an error");
        }
    }
} else {
    die("not set");
}
