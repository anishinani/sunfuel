<?php
//echo "successfully";
include_once("../../utils/dbaccess.php");
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

$dbAccess->update("payments", [
    'date_time' => $date_time->format('Y-m-d H:i:s'),
    'network_ref' => $financialTransactionId,
    "transactionStatus" => "1",
    'status' => "completed",
],[
  'external_ref'=>$externalReference
]
);

$results = $dbAccess->update("loan", ["status" => "0"], ["loanRef" => $externalReference]);
$details = $dbAccess->select("payments", ['msisdn', 'amount'], ["external_ref" => $externalReference]);


$sms->sendsms(
    "Dear Customer",
    $sms->formatMobileInternational($details[0]['msisdn']),
    "Your payment of shs " . $details[0]['amount'] . " has been received successfully"
);
