<?php
include_once '../../utils/session.php';

// if (!can('delete-fuelagent')){
//      $_SESSION['warning'] = "UnAuthorized Operation";  
//       header('Location:index.php');
//        die;
//     }
include("../../utils/dbaccess.php");
include("../../utils/activityLogger.php");

$activity = new ActivityLogger();

//die("Am here");

$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();
$user_id = $_POST['id'];

$delQuery = $dbAccess->deleteRow("fuelagent", "fuelAgentId", $user_id);
if ($delQuery == true) {
    // $activity->logActivity(
    //     $_SESSION['user'],
    //     "deleted fuel agent ",
    //     "fuel agent deleted sucessfully",
    //     $_SESSION['email'],
    //     $_SESSION['gender']
    // );
    $_SESSION["success"] = "Fuel Agent Deleted successfully";
    header("Location:index.php");
} else {
    echo "There was an error";
}
