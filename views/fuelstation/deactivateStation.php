<?php
include_once '../../utils/session.php';

// if (!can('deactivate-fuelstation')){
//      $_SESSION['warning'] = "UnAuthorized Operation";  
//       header('Location:index.php');
//        die;
//     }
include_once("../../utils/dbaccess.php");
$dbAccess = new DbAccess();

if (isset($_POST["deactivate"])) {
    $id = $_POST['id'];
   
    //update stage
    if ($dbAccess->update("fuelstation", ["fuelStationStatus" => '0'], ["fuelStationId" => $id])) {
        //update fuel agents of that stage
        $fuelAgents =  $dbAccess->select("fuelagent", ["fuelAgentName", "fuelAgentPhoneNumber"], ["stationId" => $id]);
        if(count($fuelAgents)>0){
            if ($dbAccess->update("fuelagent", ['status' => '0'], ["stationId" => $id])) {
                $_SESSION['success'] = "fuel station and all the fuel agents of the fuel station have been deactivated successfully";
                header("Location:index.php");
            } else {
                //die("There is an error please try again");
                $_SESSION['error'] = "Oops something occured please contact support or try again";
                header("Location:index.php");
            }
        }
        else{
            $_SESSION['success'] = "fuel station and all the fuel agents of the fuel station have been deactivated successfully";
            header("Location:index.php");
        }
        
    } else {
        // die("Some thing went wrong please try again");
        $_SESSION['error'] = "Oops something occured please contact support or try again";
        header("Location:index.php");
    }
} else {
    //die("not id found please contact support ")
    $_SESSION['error'] = "Oops something occured please contact support or try again";
    header("Location:index.php");
}
