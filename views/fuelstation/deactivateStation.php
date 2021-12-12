<?php
session_start();
include_once("../../utils/dbaccess.php");
$dbAccess = new DbAccess();

if (isset($_POST["deactivate"])) {
    $id = $_POST['id'];
    //die($id);
    //$allbodaUsers =  $dbAccess->select("bodauser", ["bodaUserName", "bodaUserPhoneNumber"], ["stageId" => $id]);

    //update stage
    if ($dbAccess->update("fuelstation", ["fuelStationStatus" => '0'], ["fuelStationId" => $id])) {
        //update borders of that stage
        if ($dbAccess->update("fuelagent", ['status' => '0'], ["stationId" => $id])) {
            $_SESSION['success'] = "fuel station and all the fuel agents of the fuel station have been deactivated successfully";
            header("Location:index.php");
        } else {
            //die("There is an error please try again");
            $_SESSION['success'] = "Oops something occured please contact support or try again";
            header("Location:index.php");
        }
    } else {
        // die("Some thing went wrong please try again");
        $_SESSION['success'] = "Oops something occured please contact support or try again";
        header("Location:index.php");
    }
} else {
    //die("not id found please contact support ")
    $_SESSION['success'] = "Oops something occured please contact support or try again";
    header("Location:index.php");
}
