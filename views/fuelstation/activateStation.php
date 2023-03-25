<?php
include_once '../../utils/session.php';

// if (!can('activate-fuelstation')){
//      $_SESSION['warning'] = "UnAuthorized Operation";  
//       header('Location:index.php');
//        die;
//     }
include_once("../../utils/sms.php");
include_once("../../utils/pin.php");
include_once("../../utils/dbaccess.php");



$sms =  new infobip();
$pin =  new pin();
$dbAccess = new DbAccess();

if (isset($_POST["activate"])) {
    $id = $_POST['id'];
    //die($Id);
    $oneTymPin =  $pin->randomkey(5);
    $hashedPin = $pin->hashPass($oneTymPin);
    $fuelAgents =  $dbAccess->select("fuelagent", ["fuelAgentName", "fuelAgentPhoneNumber"], ["stationId" => $id]);


    
    if(count($fuelAgents) > 0){
        for ($i = 0; $i < count($fuelAgents); $i++) {
            $message =  "Hello " . $fuelAgents[$i]["fuelAgentName"] . " Your  have been activated on CreditPlus Dail *217*212# to get started Remember your one time pin is " . $oneTymPin;
            $res = $sms->sms_faster($message , array($sms->formatMobileInternational($fuelAgents[$i]["fuelAgentPhoneNumber"])), 1);

        }
        
    }
     

    //update stage
    if ($dbAccess->update("fuelstation", ["fuelStationStatus" => '1'], ["fuelStationId" => $id])) {

        //update fuel agents of that fuelStation
      if(count($fuelAgents) > 0){
        if ($dbAccess->update("fuelagent", ['status' => '1', 'pin' => $hashedPin], ["stationId" => $id])) {
            $_SESSION['success'] = "fuel Station and all the fuel agents of the fuelStation have been activated successfully";
            header("Location:index.php");
        } else {
            //die("There is an error please try again");
            $_SESSION['error'] = "Oops something occured please contact support or try again";
            header("Location:index.php");
        }

      }
      else{
        $_SESSION['success'] = "fuel Station and all the fuel agents of the fuelStation have been activated successfully";
            header("Location:index.php");
      }
        
    } else {
        die("Some thing went wrong please try again");
        $_SESSION['error'] = "Oops something occured please contact support or try again";
        header("Location:index.php");
    }

    //update stage


} else {
    //die("not id found please contact support ")
    $_SESSION['error'] = "Oops something occured please contact support or try again";
    header("Location:index.php");
}





//echo "am activating";
