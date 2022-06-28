<?php
// ensure post method

if($_SERVER['REQUEST_METHOD'] != "POST") die;


require_once '../../utils/session.php';

if (!can('create-territories')){
    $_SESSION['warning'] = "UnAuthorized Operation";  
     header('Location:index.php');
      die;
}


require_once("../../utils/dbaccess.php");
require_once("../../controllers/TerritoryController.php");
require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");




$_SESSION['errors'] = array();

$dbAccess =  new DbAccess();
$territoryController = new  TerritoryController();
$helpers =  new HelperFunctions();
$activity = new ActivityLogger();

foreach ($_POST as $key => $value) {
    if ($key == 'addTerritory') {
        continue;
    } else {
        if ($helpers->checkEmptyFields($value) != NULL) {
            array_push($_SESSION['errors'],   $key . " " . $helpers->checkEmptyFields($value));
        } else {
            if($key != 'territoryDistricts')  $dbAccess->clean($value);
        }
    }
}



if($territoryController->exists($_POST['territoryName'])) $_SESSION['errors']['territoryName'] = "Sorry Territory Already Exists!";

if (count($_SESSION['errors'])) {

    header("Location:create.php");
} else {
    unset($_SESSION['errors']);
    
    if ($territoryController->store($_POST)) {
        $activity->logActivity(
            $_SESSION['user'],
            "Create territory ",
            "territory registered in successfully",
            $_SESSION['email'],
            $_SESSION['gender']
        );

        //redirect
        $_SESSION['success'] = "Territory Added Successfully";
        //header("Location:index.php");
        echo "success";
        //redirect
    } else {
        die("am here");
        die("Oops there was an error");
    }
}