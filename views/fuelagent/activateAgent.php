<?php
include_once '../../utils/session.php';

// if (!can('activate-fuelagent')){
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
    $agent = $_POST['id'];
    //die($Id);
    $fuelstation = $_POST['fuelStationId'];
    //die($fuelstation);
    $oneTymPin =  $pin->randomkey(5);
    $hashedPin = $pin->hashPass($oneTymPin);
    //check if stage is active
    $fuelStationStatus = $dbAccess->select("fuelstation", ["fuelStationStatus"], ["fuelStationId" => $fuelstation]);
    //die($fuelStationStatus[0]['fuelStationStatus']);

    if (strval($fuelStationStatus[0]['fuelStationStatus']) == 0) {
        $_SESSION['success'] = "Cannot activate fuel agent  because the fuel station is not yet active!!Please Activate the fuel station";
        header("Location:index.php");
    }
    //die("done");
    else {
        $fuelAgent =  $dbAccess->select("fuelagent", ["fuelAgentName", "fuelAgentPhoneNumber"], ["fuelAgentId" => $agent]);
        $mesage =  "Hello " . $fuelAgent[0]["fuelAgentName"] . " Your  have been activated on CreditPlus Dail *217*212# to get started Remember your 
        one time pin is " . $oneTymPin;
        // $sms->sendsms(
        //     $fuelAgent[0]["fuelAgentName"],
        //     $sms->formatMobileInternational($fuelAgent[0]["fuelAgentPhoneNumber"]),
            // "Hello " . $fuelAgent[0]["fuelAgentName"] . " Your  have been activated on CreditPlus Dail *217*212# to get started Remember your 
            //     one time pin is " . $oneTymPin
        // );
        $res = $sms->sms_faster($message , array($sms->formatMobileInternational($fuelAgent[0]["fuelAgentPhoneNumber"])), 1);
        if ($dbAccess->update("fuelagent", ['status' => '1', 'pin' => $hashedPin], ["fuelAgentId" => $agent])) {
            $_SESSION['success'] = "Fuel Agent has been activated successfully";
            header("Location:index.php");
        } else {
            //die("There is an error please try again");
            $_SESSION['success'] = "Oops something occured please contact support or try again";
            header("Location:index.php");
        }
    }
} else {
    //die("not id found please contact support ")
    $_SESSION['success'] = "Oops something occured please contact support or try again";
    header("Location:index.php");
}





//echo "am activating";
