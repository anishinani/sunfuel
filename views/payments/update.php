<?php
require_once __DIR__ . '/../../utils/dbaccess.php';
require_once __DIR__ . '/../../utils/sms.php';
require_once __DIR__ . '/../../utils/SsentezoWallet.php';

try {
    $dbAccess = new DbAccess();
    $sms = new infobip();
    $wallet = new SsentezoWallet();
    // get all payments where status is pending
    $payments = $dbAccess->select("payments", [], ['status' => 'pending']);
    //for each payment
    foreach ($payments as $payment) {
        if (empty($payment['external_ref'])) {
            continue;
        }

        $result = $wallet->getTransactionStatus($payment['external_ref']);
        $data = $result['data'] ?? [];
        $status = $result['status'] ?? ($data['status'] ?? null);
        $message = $result['message'] ?? ($data['message'] ?? null);

        if ($message === 'failure') {
            $dbAccess->update("payments", ["status" => "failed"], ["id" => $payment['id']]);
            $dbAccess->update("loan", ["status" => "1"], ["loanRef" => $payment['external_ref']]);
            continue;
        }

        if ($status !== null) {
            if ($status === "SUCCEEDED") {
                $dbAccess->update("payments", ["status" => "completed"], ["id" => $payment['id']]);
                $update = $dbAccess->update("bodauser", ["bodaUserStatus" => "1"], ["bodaUserPhoneNumber" => formatPhoneNumber($payment['msisdn'])]);
                //update the loan status to paid
                $dbAccess->update("loan", ["status" => "0"], ["loanRef" => $payment['external_ref']]);

                $sms->sendsms(
                    "Dear Customer",
                    $sms->formatMobileInternational($payment['msisdn']),
                    "Your payment of shs " . $payment['amount'] . " has been received successfully"
                );

                //check if the stage is suspended and update it to active
                //
                $bodauserdetails = $dbAccess->select("bodauser", ['stageId'], ["bodaUserPhoneNumber" => formatPhoneNumber($payment['msisdn'])]);
                //get stage details
                $stageDetails = $dbAccess->select("stage", ['stageStatus'], ["stageId" => $bodauserdetails[0]['stageId']])[0]['stageStatus'];
                if ($stageDetails == 2 || $stageDetails == "2") {
                    //check no boda user has a pending loan
                    $pending_loans =  $dbAccess->select("bodauser", ['bodaUserId'], ["stageId" => $bodauserdetails[0]['stageId'], "bodaUserStatus" => "2"]);
                    if (count($pending_loans) == 0) {
                        //update the stage status to active
                        $dbAccess->update("stage", ["stageStatus" => "1"], ["stageId" => $bodauserdetails[0]['stageId']]);
                        //also activate the boda users
                        $dbAccess->update("bodauser", ["bodaUserStatus" => "1"], ["stageId" => $bodauserdetails[0]['stageId']]);
                    }
                }
            } else {
                $dbAccess->update("payments", ["status" => "pending"], ["id" => $payment['id']]);
                //update the loan status to unpaid
                $dbAccess->update("loan", ["status" => "1"], ["loanRef" => $payment['external_ref']]);
            }
        }
    }


    echo "done";
} catch (\Throwable $th) {
    //throw $th;
    var_dump($th->getMessage());
    die("There was an error");
}

//mssidn is the phone number with 13 digits i want to remove the first 3 digits and replace it with a zero
function formatPhoneNumber($msisdn)
{

    //remove the first 3 digit and replace them with a zero
    $msisdn = substr($msisdn, 3);
    $msisdn = "0" . $msisdn;
    return $msisdn;
}



//  * * * * * php /var/www/boda.creditplus.ug/public_html/sunfuel/views/payments/update.php >> /var/www/boda.creditplus.ug/public_html/sunfuel/views/payments/payment_logs.log 2>&1