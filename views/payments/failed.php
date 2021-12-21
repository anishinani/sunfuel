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
        'date_time' => $_POST['transaction_init_date'],
        'transactionStatus' => "0"
    ],
    ['external_ref' => $_POST['failed_transaction_reference']]
);

// 'failed_transaction_reference' => $_POST['failed_transaction_reference'],

// var_dump($_POST);

// $result = $dbAccess->insert("failedTransaction", [
//     'is_verified' => "1",
//     'failed_transaction_reference' => $_POST['failed'],
//     'transaction_init_date' => $_POST['trans']
// ]);

// var_dump($result);
