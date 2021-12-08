<?php
session_start();
include("../../utils/dbaccess.php");

//die("Am here");

$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();
$user_id = $_POST['id'];
//die($user_id);

$sql = "DELETE FROM fuelstation WHERE fuelStationId='$user_id'";
$delQuery = mysqli_query($con, $sql);
if ($delQuery == true) {
    $_SESSION["success"] = "Deleted successfully";
    header("Location:index.php");
} else {
    echo "There was an error";
}
