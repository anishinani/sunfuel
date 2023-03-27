<?php
include("../../utils/dbaccess.php");

try {
        //update the payments table
        $dbAccess =  new DbAccess();
        // get all payments where status is pending
        $payments = $dbAccess->select("payments", ['id', 'msisdn', 'amount', 'external_ref'], ["status" => "pending"]);
    
        //for each payment
        foreach ($payments as $payment) {
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://wallet.ssentezo.com/api/get_status/'.$payment['external_ref'],
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
              if(isset($data->message)){
                if($data->message == "failure"){
                    $dbAccess->update("payments", ["status" => "failed"], ["id" => $payment['id']]);
                    //update the loan status to unpaid
                    $dbAccess->update("loan", ["status" => "1"], ["loanRef" => $payment['external_ref']]);
    
                }
              }
    
              if(isset($data->status)){
                if($data->status == "SUCCEEDED"){
                    $dbAccess->update("payments", ["status" => "completed"], ["id" => $payment['id']]);
                    //update the loan status to paid
                    $dbAccess->update("loan", ["status" => "0"], ["loanRef" => $payment['external_ref']]);
                    $sms->sendsms(
                        "Dear Customer",
                        $sms->formatMobileInternational($payment['msisdn']),
                        "Your payment of shs " . $payment['amount'] . " has been received successfully"
                    );
    
                }
                else{
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



//  * * * * * php /var/www/boda.creditplus.ug/public_html/creditpluswebapp/views/payments/update.php >> /var/www/boda.creditplus.ug/public_html/creditpluswebapp/views/payments/payment_logs.log 2>&1