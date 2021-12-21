<?php
//echo "successfully";
include_once("../../utils/dbaccess.php");
include_once("../../utils/YoAPI.php");
include_once("../../utils/Yo.php");

$creditPlusYo =  new Yo();
$dbAccess =  new DbAccess();
$confirmedPayment =  new YoAPI($creditPlusYo->getUserName(),  $creditPlusYo->getPassword());
$data = $confirmedPayment->receive_payment_notification();
//sprint_r($data);

//var_dump($_POST);

$result = $dbAccess->insert(
    "sample",
    $data

);
$dbAccess->update("sample", $data, ["external_ref" => $_POST['external_ref']]);
//var_dump($result);
//var_dump("done");
//die("we are done");
