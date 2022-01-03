<?php

session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST['action'])) {
    //echo "here";

    $array = array("invalid" => "invalidMerchantCode");

    $code =  $dbAccesss->clean($_POST['merchantCode']);

    if (empty($code)) {
        //$_SESSION['success'] = "Wrong image format not supported";
        echo json_encode($array);
    } else {
        $stations =  $dbAccesss->select("fuelstation", "", ['merchantCode' => $code]);
        //$_SESSION['success'] = "Wrong image format not supported";
        if ($stations == NULL) {
            // $_SESSION['success'] = "Wrong image format not supported";
            echo json_encode($array);
        } else {
            echo  json_encode($stations);
        }
    }
    //echo $countyCode;

    //echo "clear";
} else {
    echo "not sent";
}
