<?php
session_start();
include("../../utils/dbaccess.php");
include("../../utils/activityLogger.php");

$activity = new ActivityLogger();

//die("Am here");

$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();
$user_id = $_POST['id'];

$delQuery = $dbAccess->deleteRow("package", "packageId", $user_id);
if ($delQuery == true) {
    // $activity->logActivity(
    //     $_SESSION['user'],
    //     "deleted package ",
    //     "package deleted  sucessfully",
    //     $_SESSION['email'],
    //     $_SESSION['gender']
    // );
    $_SESSION["success"] = "Package Deleted successfully";
    header("Location:index.php");
} else {
    echo "There was an error";
}
