<?php

try {
    //code...
    session_start();
include("../../utils/dbaccess.php");
include("../../utils/activityLogger.php");

$activity = new ActivityLogger();



$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();
$user_id = $_POST['id'];
//die($user_id);

$sql = "DELETE FROM roles WHERE id='$user_id'";
//$dbAccess->delete()
$delQuery = mysqli_query($con, $sql);
if ($delQuery == true) {
    // $activity->logActivity(
    //     $_SESSION['user'],
    //     "deleted fuel station ",
    //     "fuel station deleted  sucessfully",
    //     $_SESSION['email'],
    //     $_SESSION['gender']
    // );
    $_SESSION["success"] = "Deleted successfully";
    header("Location:index.php");
} else {
    echo "There was an error";
}
} catch (\Throwable $th) {
    //throw $th;
    die($th->getMessage());
}

