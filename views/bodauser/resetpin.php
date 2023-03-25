<?php

include_once '../../utils/session.php';

// if (!can('activate-bodauser')){
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

if (isset($_POST["reset"])) {
    $bodaUserId = $_POST['bodaUserId'];
    //die($Id);
    $stageId = $_POST['stageId'];
    //die($stageId);
    $oneTymPin =  $pin->randomkey(5);
    $hashedPin = $pin->hashPass($oneTymPin);

    $allbodaUser =  $dbAccess->select("bodauser", ["bodaUserName", "bodaUserPhoneNumber"], ["bodaUserId" => $bodaUserId]);

    $messaage = "Hello " . $allbodaUser[0]["bodaUserName"] . " Your Pin has  been reset successfully on CreditPlus Dail *217*212# to update your pin  Remember your one time pin is " . $oneTymPin;
    $phone_numbers =    array($sms->formatMobileInternational($allbodaUser[0]["bodaUserPhoneNumber"]));
    $res = $sms->sms_faster($messaage , $phone_numbers , 1); 


    if ($dbAccess->update("bodauser", ['bodaUserStatus' => '1', 'pin' => $hashedPin], ["bodaUserId" => $bodaUserId])) {
        $_SESSION['success'] = "Pin has been reset successfully";
        //redirect to the bodauser details page
        header("Location:bodauserdetails.php?bodadetails=".$bodaUserId);
        //header("Location:index.php");
    } else {
        //die("There is an error please try again");
        $_SESSION['error'] = "Oops something occurred please contact support or try again";
        header("Location:bodauserdetails.php?bodadetails=".$bodaUserId);
        
    }
} else {
    //die("not id found please contact support ")
    $_SESSION['error'] = "Oops something occurred please contact support or try again";
    header("Location:bodauserdetails.php?bodadetails=".$bodaUserId);

}






