<?php
//echo "successfully";
include_once("../../utils/dbaccess.php");
include_once("../../utils/YoAPI.php");
include_once("../../utils/Yo.php");


$creditPlusYo =  new Yo();
$dbAccess =  new DbAccess();
$confirmedPayment =  new YoAPI($creditPlusYo->getUserName(),  $creditPlusYo->getPassword());
$data = $confirmedPayment->receive_payment_notification();

$dbAccess->insert("sample", [
    'date_time' => $_POST['date_time'],
    'amount' => $_POST['amount'],
    'narrative' => $_POST['narrative'],
    'network_ref' => $_POST['network_ref'],
    'external_ref' => $_POST['external_ref'],
    'msisdn' => $_POST['msisdn'],
    "transactionStatus" => "1"
]);


$results = $dbAccess->update("loan", ["status" => "0"], ["loanRef" => $_POST['external_ref']]);
    //var_dump($results);
