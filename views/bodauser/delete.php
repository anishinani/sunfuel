<?php
session_start();

if (!can('delete-bodausers')) echo '<script>window.open("../Errors/unAuthorized.php" , "_self")</script>';


include("../../utils/dbaccess.php");
include("../../utils/activityLogger.php");

$activity = new ActivityLogger();

//die("Am here");

$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();
$user_id = $_POST['id'];
//die($user_id);

$sql = "DELETE FROM bodauser WHERE bodaUserId='$user_id'";
$delQuery = mysqli_query($con, $sql);
//die($delQuery);
if ($delQuery == true) {
    $activity->logActivity(
        $_SESSION['user'],
        "deleted boda user ",
        " deleted  sucessfully",
        $_SESSION['email'],
        $_SESSION['gender']
    );
    $_SESSION["success"] = "Deleted successfully";
    header("Location:index.php");
} else {
    echo "There was an error try again";
}
