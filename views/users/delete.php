<?php
session_start();
include("../../utils/dbaccess.php");
include("../../utils/activityLogger.php");

$activity =  new ActivityLogger();

//die("Am here");

$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();
$user_id = $_POST['id'];
//die($user_id);

// $sql = "DELETE FROM  WHERE adminId='$user_id'";

$dbAccess->delete("DELETE FROM administrators WHERE adminId=$user_id");
$activity->logActivity(
    $_SESSION['user'],
    "deleted user ",
    "user deleted in sucessfully",
    $_SESSION['email'],
    $_SESSION['gender']
);
$_SESSION["success"] = " User Deleted successfully";
header("Location:index.php");

// else {
//     $_SESSION["success"] = "Something went wrong please try again";
//     header("Location:index.php");
// }
