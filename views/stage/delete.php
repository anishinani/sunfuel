<?php
include_once '../../utils/session.php';

// if (!can('delete-stages')){
//      $_SESSION['warning'] = "UnAuthorized Operation";  
//       header('Location:index.php');
//        die;
//     }
include("../../utils/dbaccess.php");
include("../../utils/activityLogger.php");

$activity =  new ActivityLogger();


$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();
$user_id = $_POST['id'];


$sql = "DELETE FROM stage WHERE stageId='$user_id'";
$delQuery = mysqli_query($con, $sql);
if ($delQuery == true) {
    // $activity->logActivity(
    //     $_SESSION['user'],
    //     "deleted stage ",
    //     "stage deleted in sucessfully",
    //     $_SESSION['email'],
    //     $_SESSION['gender']
    // );
    $_SESSION["success"] = " Stage Deleted successfully";
    header("Location:index.php");
} else {
    $_SESSION["error"] = "Opps! Something went wrong int the previous operation";
    header("Location:index.php");
}
