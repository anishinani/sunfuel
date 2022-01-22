<?php

/**
 * Mid night cron job
 **/

$time = date_format(new DateTime("now"), "H:i:s");

if ($time == '00:00:00') {

    require_once './action.php';

    // works at exactly midnight

    $conn = connection();

    $conn->beginTransaction();

    $stages = unpaidStageBodaLoans($conn);

    if (false == $stages) {

        echo "Midnight job was unsuccessful";

        die;
    }

    $moved = MoveBodaStagesState($conn, $stages, 2);

    if ($moved) {

        echo count($stages) . "stage(s) have been moved to Pending Payment <br/>";
        echo "Midnight job was successful";
    } else {
        echo "Midnight job was successful";
    }
    $conn->commit();
    $conn = null;
} else {
    echo "Timing Error";
}
