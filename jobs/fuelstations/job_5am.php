<?php

/**
 * 5am 
 * Turn on the fuel stations
 * 
 * ***/

require_once './actions.php';


$conn =  connection();


$time = date_format(new DateTime("now"), "H:i:s");

if ($time == '05:00:00') {

    $conn = connection();

    $conn->beginTransaction();

    $sql = 'select fuelStationName,fuelStationContactPerson,fuelStationContactPhone  from fuelstation where fuelStationStatus = 2 ';

    $stmt = $conn->query($sql);

    if (false == $stmt) {
        $conn->rollBack();
        $conn = null;
        die;
    }

    $stations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($stations) > 0) {

        $sql = 'update fuelstation set fuelStationStatus = 1 where fuelStationStatus = 2';

        $stmt = $conn->query($sql);

        if (false == $stmt) {
            $conn->rollBack();
            $conn = null;
            die;
        }

        if ($stmt->rowCount() > 0) {

            $conn->commit();

            foreach($stations as $stations){

                $message = 'Hello '.strtoupper($stations['fuelStationName']) . ' is now activated  to use credit.';

                $phoneNumber  = formatPhoneNumber($stations['fuelStationContactPhone']);

                sendSms($phoneNumber,$message);

            }

        } else {
            $conn->rollBack();
            $conn = null;
            die;
        }
        
    }
} else {

    echo "Timing Error";
}
