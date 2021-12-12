<?php
session_start();
include_once("../../utils/dbaccess.php");
$dbAccess = new DbAccess();

if (isset($_POST["deactivate"])) {
    $id = $_POST['id'];
    //die($id);
    //$allbodaUsers =  $dbAccess->select("bodauser", ["bodaUserName", "bodaUserPhoneNumber"], ["stageId" => $id]);

    //update stage
    if ($dbAccess->update("stage", ["stageStatus" => 0], ["stageId" => $id])) {
        //update borders of that stage
        if ($dbAccess->update("bodauser", ['bodaUserStatus' => 0], ["stageId" => $id])) {
            $_SESSION['success'] = "Stage and all the boda users of the stage have been deactivated successfully";
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
