<?php
session_start();


require_once("../../utils/dbaccess.php");

require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");

require_once('../../controllers/RolesController.php');


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$roles = new Roles();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();
//unset($_SESSION['errors']);



if (isset($_POST['updateRole'])) {

    $_SESSION['errors'] = array();

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addRole' || $key = "permissions") {
            continue;
        } else {
            if ($helpers->checkEmptyFields($value) != NULL) {
                array_push($_SESSION['errors'],   $key . " " . $helpers->checkEmptyFields($value));
            } else {
                //clean user input 
                $dbAccess->clean($value);
            }
        }
    }
    //check errors and clean

    if ($_POST['permissions'] == NULL) {
        array_push($_SESSION['errors'],   "choose at least one permission");
    }

    //die("here");



    //check session array
    if (count($_SESSION['errors'])) {

        header("Location:create.php");
    }
    //check session array
    else {
        unset($_SESSION['errors']);
        if ($roles->updateInfo($_POST)) {
            $activity->logActivity(
                $_SESSION['user'],
                "Updated Role",
                "role updated sucessfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            //redirect
            $_SESSION['success'] = "role updated Successfully";

            header("Location:index.php");
            //redirect
        } else {
            die("Oops there was an error");
        }
    }
} else {
    die("not set");
}
