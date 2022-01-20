<?php

function getTotalLoans(PDO $conn, $upto)
{

    $loans_taken = "select sum(loanAmount) as total_consumed , fuelSationId as fuelStationId from loan where DATE(created_at) <= DATE('". $upto . "')  group by fuelStationId ";

    $stmt = $conn->query($loans_taken);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getTotalDeposit(PDO $conn, $upto)
{

    $todays_deposit = "select sum(amount) as total_float , fuelStationId from deposits where DATE(created_at) <= DATE('" . $upto . "')  group by fuelStationId ";

    echo $todays_deposit;

    $stmt = $conn->query($todays_deposit);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function connection()
{
    $dsn = 'mysql:host=localhost;dbname=bodacredit;';

    $user = 'root';

    $password = $_SERVER['REMOTE_ADDR'] == "::1" ? "" : "!Log10tan10";

    $conn =  new PDO($dsn, $user, $password);

    return $conn;
}



function sendSms($to,$msg){


    error_reporting(E_ALL);

    ini_set('display_errors', 1);

    require_once '../../utils/dbaccess.php';


    $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://apidocs.speedamobile.com/api/SendSMS?api_id=API34247417254&api_password=!Log10tan10&sms_type=P&encoding=T&sender_id=CREDITPLUS&phonenumber=" . $to . "&textmessage=" . urlencode($msg),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",

        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);
        if ($response) {

            $decodedcontent = json_decode($response);


            $data['tel'] = $to;
            $data['message'] = $msg;

            $data['message_id'] = $decodedcontent->{'message_id'};
            $data['success_code'] = $decodedcontent->{'remarks'};
            $data['status'] = $decodedcontent->{'status'};

            $db = new DbAccess();
            $table = "sms_gateway";
            $db->insert($table, $data);

        } else {
            die("not sent");
            return 0;

        }


}


function formatPhoneNumber($mobile){

    $length = strlen($mobile);
        $m = '+256';
        //format 1: +256752665888
        if ($length == 13)
            return $mobile;
        elseif ($length == 12) //format 2: 256752665888
            return "+" . $mobile;
        elseif ($length == 10) //format 3: 0752665888
            return $m .= substr($mobile, 1);
        elseif ($length == 9) //format 4: 752665888
            return $m .= $mobile;

        return $mobile;
}