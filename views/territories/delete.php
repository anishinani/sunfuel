<?php
// ensure post method

if($_SERVER['REQUEST_METHOD'] != "POST") die;


require_once '../../utils/session.php';

if (!can('delete-territories')){
    $_SESSION['warning'] = "UnAuthorized Operation";  
     header('Location:index.php');
      die;
}


require_once("../../utils/dbaccess.php");
require_once("../../controllers/TerritoryController.php");
require_once("../../utils/activityLogger.php");

$territoryController = new  TerritoryController();
$activity = new ActivityLogger();

    if ($territoryController->deleteTerritory($_POST['delete'])) {
        $activity->logActivity(
            $_SESSION['user'],
            "deleting territory ",
            "territory ". $_POST['territoryName']. " was deleted",
            $_SESSION['email'],
            $_SESSION['auth']
        );

        //redirect
        $_SESSION['success'] = "Territory was deleted successful";
        header("Location:index.php");
        echo "success";
        //redirect
    } else {
        echo "<pre>";
        
        var_dump($_POST,$territoryController);

        die;

        $_SESSION['error'] = "Something went wrong";
        header("Location:index.php");

    }
