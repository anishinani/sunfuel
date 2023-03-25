<?php
//echo "successfully";
include_once("../../utils/dbaccess.php");
include_once("../../utils/YoAPI.php");
include_once("../../utils/Yo.php");
include_once("../../utils/sms.php");


$creditPlusYo =  new Yo();
$dbAccess =  new DbAccess();
$sms =  new infobip();


    //use php input instead of post
    $data = json_decode(file_get_contents('php://input'), true);
    $externalReference = $data['externalReference'];
    $request_id = $data['request_id'];
    $status = $data['status'];
    $financialTransactionId = $data['financialTransactionId'];
    $message = $data['message'];
    $date_time = new DateTime();

$dbAccess->insert("payments", [
    'date_time' => $date_time->format('Y-m-d H:i:s'),
    'network_ref' => $financialTransactionId,
    'external_ref' => $_POST['external_ref'],
    "transactionStatus" => "1"
]);


$results = $dbAccess->update("loan", ["status" => "0"], ["loanRef" => $_POST['external_ref']]);
//var_dump($results);


$sms->sendsms(
    "Dear Customer",
    $sms->formatMobileInternational($_POST['msisdn']),
    "Your payment of shs " . $_POST['amount'] . " has been received successfully"
);
