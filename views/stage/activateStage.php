<?php

require_once "../../utils/session.php";

// if (!can('activate-stages')){
//     $_SESSION['warning'] = "UnAuthorized Operation";  
//      header('Location:index.php');
//       die;
// }

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
    $allbodaUsers =  $dbAccess->select("bodauser", ["bodaUserName", "bodaUserPhoneNumber"], ["stageId" => $id]);
    for ($i = 0; $i < count($allbodaUsers); $i++) {

        $sms->sendsms(
            $allbodaUsers[$i]["bodaUserName"],
            $sms->formatMobileInternational($allbodaUsers[$i]["bodaUserPhoneNumber"]),
            "Hello " . $allbodaUsers[$i]["bodaUserName"] . " Your  have been activated on CreditPlus Dail *217*212# to get started Remember your 
            one time pin is " . $oneTymPin
        );
    }

    //update stage
    if ($dbAccess->update("stage", ["stageStatus" => '1'], ["stageId" => $id])) {
        //update borders of that stage
        if ($dbAccess->update("bodauser", ['bodaUserStatus' => '1', "pin" => $hashedPin], ["stageId" => $id])) {
            $_SESSION['success'] = "Stage and all the boda Riders of the stage have been activated successfully";
            header("Location:index.php");
        } else {
            //die("There is an error please try again");
            $_SESSION['error'] = "Oops something occurred please contact support or try again";
            //$_SESSION['success'] = "failed to update";
            header("Location:index.php");
        }
    } else {
        // die("Some thing went wrong please try again");
        $_SESSION['error'] = "Oops something occurred please contact support or try again";
        //$_SESSION['success'] = "failed to send sms";
        header("Location:index.php");
    }

    //update stage


} else {
    //die("not id found please contact support ")
    $_SESSION['error'] = "Oops something occurred please contact support or try again";
    header("Location:index.php");
}





//echo "am activating";
