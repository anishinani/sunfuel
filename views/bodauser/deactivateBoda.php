<?php
session_start();
include_once("../../utils/dbaccess.php");
$dbAccess = new DbAccess();

if (isset($_POST["deactivate"])) {
    $id = $_POST['id'];


    //update stage
    if ($dbAccess->update("bodauser", ['bodaUserStatus' => '0'], ["bodaUserId" => $id])) {
        //die("There is an error please try again");
        $_SESSION['success'] = "Boda user deactiavted successfully";
        header("Location:index.php");
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
