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



try {
    if (isset($_POST['addRole'])) {

        $_SESSION['errors'] = array();
    
    
        // var_dump($_POST);
        // die("here");
    
    
    
        //check errors and clean o
        foreach ($_POST as $key => $value) {
            if ($key == 'addRole' || $key == "permissions" || $key ==  "modules") {
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
        if (count($_SESSION['errors']) > 0) {
    
            header("Location:create.php");
        }
        //check session array
        else {
            unset($_SESSION['errors']);
            if ($roles->store($_POST)) {

                // $activity->logActivity(
                //     $_SESSION['user'],
                //     "Created Role",
                //     "role created sucessfully",
                //     $_SESSION['email'],
                //     $_SESSION['auth']
                // );
    
                //redirect
                $_SESSION['success'] = "role Added Successfully";
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






