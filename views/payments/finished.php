<?php
//echo "successfully";
include_once("dbaccess.php");
include_once("sms.php");

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
$dbAccess->update("bodauser", ["bodaUserStatus" => "1"], ["bodaUserPhoneNumber" => formatPhoneNumber($details[0]['msisdn'])]);


$sms->sendsms(
    "Dear Customer",
    $sms->formatMobileInternational($details[0]['msisdn']),
    "Your payment of shs " . $details[0]['amount'] . " has been received successfully"
);


//mssidn is the phone number with 13 digits i want to remove the first 3 digits and replace it with a zero
function formatPhoneNumber($msisdn){
    
    //remove the first 3 digit and replace them with a zero
    $msisdn = substr($msisdn, 3);
    $msisdn = "0".$msisdn;
    return $msisdn;
}
