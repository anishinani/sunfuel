<?php
//echo "successfully";
include_once("../../utils/dbaccess.php");
include_once("../../utils/YoAPI.php");
include_once("../../utils/Yo.php");

$creditPlusYo =  new Yo();
$dbAccess =  new DbAccess();
$confirmedPayment =  new YoAPI($creditPlusYo->getUserName(),  $creditPlusYo->getPassword());
$successData =  $confirmedPayment->receive_payment_notification();

$dbAccess->insert(
    "sample",
    [
        'is_verified' => $successData["verification_status"],
        'date_time' => $successData['date_time'],
        'amount' => $successData['amount'],
        'narrative' => $successData['narrative'],
        'network_ref' => $successData['network_ref'],
        'external_ref' => $successData['external_ref'],
        'msisdn' => $successData['msisdn']
    ]
);
var_dump("done");
die("we are done");
