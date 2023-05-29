<?php
include_once("dbaccess.php");
include_once("sms.php");

try {
    $dbAccess =  new DbAccess();
    $sms =  new infobip();
    // get all payments where status is pending
    $payments = $dbAccess->select("payments", [],['status'=>'pending']);
    //for each payment
    foreach ($payments as $payment) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wallet.ssentezo.com/api/get_status/' . $payment['external_ref'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic YjdmNDIxMWEtMjRhYS00ZGViLWE5OTAtMTRkY2MxZjZiNTE0OjVlYmI3MGUwM2Y0YjQ3YmNmYWIwZDg3NTYxYWI1Yjhm',
                'Cookie: PHPSESSID=esfgn34bf4dgcg15aeegc1eu89'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);


        if (isset($data->message)) {
            if ($data->message == "failure") {
                $dbAccess->update("payments", ["status" => "failed"], ["id" => $payment['id']]);
                //update the loan status to unpaid
                $dbAccess->update("loan", ["status" => "1"], ["loanRef" => $payment['external_ref']]);
            }
        }

        if (isset($data->status)) {
            if ($data->status == "SUCCEEDED") {
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



//  * * * * * php /var/www/boda.creditplus.ug/public_html/creditpluswebapp/views/payments/update.php >> /var/www/boda.creditplus.ug/public_html/creditpluswebapp/views/payments/payment_logs.log 2>&1