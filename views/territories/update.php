<?php
// ensure post method

if($_SERVER['REQUEST_METHOD'] != "POST") die;


require_once '../../utils/session.php';

if (!can('edit-territories')){
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
    if ($key == 'editTerritory') {
        continue;
    } else {
        if ($helpers->checkEmptyFields($value) != NULL) {
            array_push($_SESSION['errors'],   $key . " " . $helpers->checkEmptyFields($value));
        } else {
            if($key != 'territoryDistricts')  $dbAccess->clean($value);
        }
    }
}

if (count($_SESSION['errors'])) {

    header("Location:edit.php");
} else {
    unset($_SESSION['errors']);
    if ($territoryController->updateTerritory($_POST)) {
        $activity->logActivity(
            $_SESSION['user'],
            "updating territory ",
            "territory ". $_POST['territoryName']. " was updated",
            $_SESSION['email'],
            $_SESSION['auth']
        );

        //redirect
        $_SESSION['success'] = "Territory updated Successfully";
        //header("Location:index.php");
        echo "success";
        //redirect
    } else {
        $_SESSION['error'] = "Territory was not updated";
        die("Oops there was an error");
    }
}