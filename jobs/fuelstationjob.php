<?php

/**
 * fuel station job
 * 1 check total consumed fuel
 * 2 deactivate all fuel stations
 * 3 calculate float balance
 * 4 email the fuel station
 * 5 sms the same to contact
 * 
 * **/ 


//connection

$dsn = 'mysql:host=localhost;port=3306;dbname=bodacredit;';

$user = 'root';

$password = $_SERVER['REMOTE_ADDR'] == "::1" ? "":"!Log10tan10";

$conn  =  new PDO($dsn,$user,$password);


// deactivate the fuel stations

$sql = 'update fuelstation set fuelStationStatus = 2 where fuelStationStatus not in (0 , 3)';

// switch off the stations
$conn->beginTransaction();

$stmt = $conn->query($sql);

if($stmt == false) $conn->rollBack(); die;

// stations are off

// calculate the account balances

$sql = 'select fuelStationId , fuelStationName ,fuelStationContactPerson ,fuelStationContactPhone   from fuelStation where fuelStationStatus not in (0,3)';

$stmt = $conn->query($sql);

if($stmt == false) $conn->rollBack(); die;

$fuelStationInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once  './utils/sms.php';
// require_once './utils/mailer/mailer.php'; when email field works

$smsApi = new infobip;


if(empty($fuelStationInfo))  die;

foreach($fuelStationInfo as $fuelStation){

    // get the float credited the previous day for the fuel station
    $sql = 'select id , amount , previousBalance , newBalance  from deposits where fuelStationId ='.$fuelStation['fuelStationId']  . ' order by created_at desc limit 1 ';
    
    $stmt = $conn->query($stmt);

    if($stmt == false) $conn->rollBack(); break;

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $float = $res[0];        

    // loans query
    
    $sql =  'select sum(loanAmount) as consumedFuel from loan where fuelSationId = '.$fuelStation['fuelSationId'];

    $stmt = $conn->query($sql);

    if($stmt == false) $conn->rollBack(); break;

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $consumed = count($res)? $res[0]['consumedFuel'] : 0;

    // to send the sms to the fuelStationContactPerson
    $phoneNumber = $smsApi->formatMobileInternational($fuelStation['fuelStationContactPhone']);

    $message = "
        Account Statement for ".date_format(new DateTime(strtotime(' Yesterday 5 pm ')),"Y/M/D H:i:s").".
        Credited Float : ". number_format($float['newBalance'])." UGX
        Consumed Fuel : ".number_format($consumed)." UGX
        Previous Balance: ".number_format($float['previousBalance'])." UGX
        Balance : ". number_format($float['newBalance'] - $consumed)." UGX
    ";
    // send the sms
    $send = $smsApi->sendsms('',$phoneNumber,$message);
    // to send email to
}

$conn->commit();

echo 'success';



