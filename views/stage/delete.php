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

$sql = "DELETE FROM stage WHERE stageId='$user_id'";
$delQuery = mysqli_query($con, $sql);
if ($delQuery == true) {
    $activity->logActivity(
        $_SESSION['user'],
        "deleted stage ",
        "stage deleted in sucessfully",
        $_SESSION['email'],
        $_SESSION['gender']
    );
    $_SESSION["success"] = " Stage Deleted successfully";
    header("Location:index.php");
} else {
    echo "There was an error";
}
