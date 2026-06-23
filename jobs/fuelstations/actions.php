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



function sendSms($to, $msg)
{
    require_once __DIR__ . '/../../utils/sms.php';

    $sms = new SmsService();
    return $sms->sendsms('SUNFUEL', $to, $msg);
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