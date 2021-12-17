<?php
session_start();
include_once("../../utils/sms.php");
include_once("../../utils/pin.php");
include_once("../../utils/dbaccess.php");



$sms =  new infobip();
$pin =  new pin();
$dbAccess = new DbAccess();

if (isset($_POST["activate"])) {
    $bodaUserId = $_POST['id'];
    //die($Id);
    $stageId = $_POST['stageId'];
    //die($stageId);
    $oneTymPin =  $pin->randomkey(5);
    $hashedPin = $pin->hashPass($oneTymPin);
    //check if stage is active
    $stageStatus = $dbAccess->select("stage", ["stageStatus"], ["stageId" => $stageId]);
    if (strval($stageStatus[0]['stageStatus']) == 0) {
        $_SESSION['success'] = "Cannot activate boda user because the stage is not yet active!!Please Activate the stage";
        header("Location:index.php");
    }
    //die("done");
    $allbodaUser =  $dbAccess->select("bodauser", ["bodaUserName", "bodaUserPhoneNumber"], ["bodaUserId" => $bodaUserId]);

    $sms->sendsms(
        $allbodaUser[0]["bodaUserName"],
        $sms->formatMobileInternational($allbodaUser[0]["bodaUserPhoneNumber"]),
        "Hello " . $allbodaUser[0]["bodaUserName"] . " Your  have been activated on CreditPlus Dail *185*22# to get started Remember your 
            one time pin is " . $oneTymPin
    );
    if ($dbAccess->update("bodauser", ['bodaUserStatus' => '1', 'pin' => $hashedPin], ["bodaUserId" => $bodaUserId])) {
        $_SESSION['success'] = "Boda User has been activated successfully";
        header("Location:index.php");
    } else {
        //die("There is an error please try again");
        $_SESSION['success'] = "Oops something occured please contact support or try again";
        header("Location:index.php");
    }
} else {
    //die("not id found please contact support ")
    $_SESSION['success'] = "Oops something occured please contact support or try again";
    header("Location:index.php");
}





//echo "am activating";
