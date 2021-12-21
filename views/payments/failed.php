<?php
include_once("../../utils/dbaccess.php");
include_once("../../utils/YoAPI.php");
include_once("../../utils/Yo.php");

$creditPlusYo =  new Yo();
$dbAccess =  new DbAccess();
$confirmedPayment =  new YoAPI($creditPlusYo->getUserName(),  $creditPlusYo->getPassword());
$data = $confirmedPayment->receive_payment_failure_notification();
//sprint_r($data);

//var_dump($_POST);

$result = $dbAccess->update(
    "sample",
    [
        'failed_transaction_reference' => $_POST['failed_transaction_reference'],
        'date_time' => $_POST['transaction_init_date'],
        'transactionStatus' => "0"
    ],
    ['external_ref' => $_POST['failed_transaction_reference']]
);
