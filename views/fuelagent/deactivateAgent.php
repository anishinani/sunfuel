<?php
include_once '../../utils/session.php';

// if (!can('deactivate-fuelagent')){
//      $_SESSION['warning'] = "UnAuthorized Operation";  
//       header('Location:index.php');
//        die;
//     }
include_once("../../utils/dbaccess.php");
$dbAccess = new DbAccess();

if (isset($_POST["deactivate"])) {
    $id = $_POST['id'];


    //update stage
    if ($dbAccess->update("fuelagent", ['status' => 0], ["fuelAgentId" => $id])) {
        //die("There is an error please try again");
        $_SESSION['success'] = "fuel Agent  deactiavted successfully";
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
