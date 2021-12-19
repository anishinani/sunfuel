<?php
session_start();
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
    for ($i = 0; $i < count($fuelAgents); $i++) {

        $sms->sendsms(
            $fuelAgents[$i]["fuelAgentName"],
            $sms->formatMobileInternational($fuelAgents[$i]["fuelAgentPhoneNumber"]),
            "Hello " . $fuelAgents[$i]["fuelAgentName"] . " Your  have been activated on CreditPlus Dail *185*22# to get started Remember your 
            one time pin is " . $oneTymPin
        );
    }

    //update stage
    if ($dbAccess->update("fuelstation", ["fuelStationStatus" => '1'], ["fuelStationId" => $id])) {
        //update borders of that fuelStation
        if ($dbAccess->update("fuelagent", ['status' => '1', 'pin' => $hashedPin], ["stationId" => $id])) {
            $_SESSION['success'] = "fuel Station and all the fuel agents of the fuelStation have been activated successfully";
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

    //update stage


} else {
    //die("not id found please contact support ")
    $_SESSION['success'] = "Oops something occured please contact support or try again";
    header("Location:index.php");
}





//echo "am activating";
