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

/**
 */
 require './actions.php';
 /* 
 * utilities
 * **/  



$conn = connection();

$stations_are_ = 'active';

$conn->beginTransaction();

$deactivate_fuelstations = 'update fuelstation set fuelStationStatus = 2 where fuelStationStatus not in (0 , 3)';


$deactivate = $conn->query($deactivate_fuelstations);


if(false == $deactivate){ $conn->rollBack(); $conn = null; die;}


if($deactivate->rowCount()) $stations_are_ = 'deactivated';


/**
 * get all deposits or float that was added today
 * */ 

 

 $todaysFloat = getTotalDeposit($conn,date_format(new DateTime(" Today 5pm "),"Y-m-d H:i:s"));

 $yesterDayFloat = getTotalDeposit($conn,date_format(new DateTime(" Yesterday 5pm "),"Y-m-d H:i:s"));


/**
 * get all consumed fuel or loans
 * **/  


$todayConsumption = getTotalLoans($conn,date_format(new DateTime(" Today 5pm "),"Y-m-d H:i:s"));

$YesterdaysConsumption = getTotalLoans($conn,date_format(new DateTime(" Yesterday 5pm "),"Y-m-d H:i:s"));

/**
 * get fuelStation details
 * 
 * */ 

$deactivated_stations = 'select fuelStationId , fuelStationName ,fuelStationContactPerson ,fuelStationContactPhone   from fuelStation where fuelStationStatus  = 2';

$stmt = $conn->query($deactivated_stations);

$stations = $stmt->fetchAll(PDO::FETCH_ASSOC);


foreach ($stations as $station){

    /**
     * calculations for each fuel stations
     * 
     * */ 

    $consumed = 0.00;

    $yesterday_consumed = 0.00;

    $float = 0.00;

    $yesterday_float = 0.00;

    $previousBalance = 0.00;

    $currentBalance = 0.00;

    $loans = array_filter($todayConsumption,function($item){ 
        global $station;
        return $item['fuelStationId'] == $station['fuelStationId'];
     });

    $yesterday_loans = array_filter($YesterdaysConsumption,function($item){  
        global $station;
        return $item['fuelStationId'] == $station['fuelStationId'];
     });

    $deposits = array_filter($todaysFloat,function($item ){
    global $station;
    return $item['fuelStationId'] == $station['fuelStationId'];
 });
   
    $yesterday_deposits = array_filter($yesterDayFloat,function($item ){ 
        global $station;

        return $item['fuelStationId'] == $station['fuelStationId'];
     });

    
    if(count($loans) == 1){

        $consumed = $loans[0]['total_consumed'];
    }

    if(count($yesterday_loans) == 1){

        $yesterday_consumed = $yesterday_loans[0]['total_consumed'];
    }

    if(count($deposits) == 1){

        $float = $deposits[0]['total_float'];

    }

    if(count($yesterday_deposits) == 1){

        $yesterday_float = $yesterday_deposits[0]['total_float'];

    }

    $currentBalance = $float - $consumed;

    $previousBalance = $yesterday_float - $yesterday_consumed;



    $message = $station['fuelStationName'] . ' STATEMENT AS OF '. date_format(new DateTime("Today 5pm "),"Y/M/d - h:i A");
    $message .= " Previous Balance : ". number_format($previousBalance) . " UGX, ";
    $message .= " Credited Float : ".number_format(($float - $yesterday_float)) . " UGX, ";
    $message .= " Consumed Fuel : ". number_format(($consumed - $yesterday_consumed)) . " UGX,";
    $message .= " Balance : ". number_format($currentBalance) . " UGX ";

    $phoneNumber = formatPhoneNumber($station['fuelStationContactPhone']);

    sendSms($phoneNumber,$message);

}

$conn->commit();

$conn  = null;

