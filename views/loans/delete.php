<?php
session_start();
include("../../utils/dbaccess.php");
include("../../utils/activityLogger.php");

$activity = new ActivityLogger();

//die("Am here");

$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();
$user_id = $_POST['id'];
//die($user_id);

$sql = "DELETE FROM loan WHERE loanId='$user_id'";
$delQuery = mysqli_query($con, $sql);
if ($delQuery == true) {
    $activity->logActivity(
        $_SESSION['user'],
        "loan deleted ",
        "loan deleted  sucessfully",
        $_SESSION['email'],
        $_SESSION['gender']
    );
    $_SESSION["success"] = "Loan Deleted successfully";
    header("Location:index.php");
} else {
    $_SESSION["success"] = "Something went wrong !Please contact support";
    header("Location:index.php");
}
