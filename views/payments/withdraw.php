<?php
include_once("../../utils/YoAPI.php");
include_once("../../utils/Yo.php");
include_once("../../utils/dbaccess.php");
include_once("../../utils/pin.php");

$yo = new Yo();
$pin = new pin();
$dbAccess =  new DbAccess();
//$response = $yo->withdraw("50000", "256772093837", "Withdraw for testing purposes");
//var_dump($response);
//die("we are done");
$YoApi =  new YoAPI($yo->getUserName(), $yo->getPassword());
// $localLink = "localhost/creditpluswebapp/views/payments/finished.php";
$sucessRedirectLink = "
";

// $localLinkFailure = "localhost/creditpluswebapp/views/packages/failed.php";
$failureRedirectLink = "http://appdev.creditplus.ug/creditpluswebapp/views/payments/failed.php";
//$sucessRedirectLink = "";
$failureRedirectLink = "";

// $ip_address = $_SERVER['REMOTE_ADDR'];

// if ($ip_address == '::1') {

//     $sucessRedirectLink = $localLink;
//     $failureRedirectLink = $localLinkFailure;
// } else {

//     $sucessRedirectLink = $serverLink;
//     $failureRedirectLink =  $serverLinkFailure;
// }

//$YoApi->get_instant_notification_url()

//SET YOUR URL
// $YoApi->set_URL($yo->getModel());
$YoApi->set_instant_notification_url($sucessRedirectLink);
$YoApi->set_failure_notification_url($failureRedirectLink);
$YoApi->set_nonblocking("TRUE");

$rand = $pin->randomkey(5);
$externalReference =  "256759983853" . $rand;
$hashed = $pin->hashPass($externalReference);
$YoApi->set_external_reference($hashed);

//depost money;
$results = $YoApi->ac_deposit_funds("256759983853", "1000", "Testing purposes");

$dbAccess->insert("sample", ["external_ref" => $hashed]);

var_dump($results);
//die("here");
