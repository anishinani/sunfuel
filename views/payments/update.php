<?php
include("../../utils/dbaccess.php");

if(isset($_POST['update'])){
    //update the payments table
    $dbAccess =  new DbAccess();
    // get all payments where status is pending
    $payments = $dbAccess->select("payments", ['id', 'msisdn', 'amount', 'external_ref'], ["status" => "pending"]);

    //for each payment
    foreach ($payments as $payment) {
        //get the loan details
        $loan = $dbAccess->select("loan", ['loanRef', 'status'], ["loanRef" => $payment['external_ref']]);
        //if the loan is not paid
        if ($loan[0]['status'] == 1) {
            //update the payment status to completed
            $dbAccess->update("payments", ["status" => "completed"], ["id" => $payment['id']]);
        }
    }

}