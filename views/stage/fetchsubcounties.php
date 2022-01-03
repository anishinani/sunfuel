<?php
session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST['action'])) {
    //echo "here";
    $countyCode =  $dbAccesss->clean($_POST['subcounty']);
    $districtCode =  $dbAccesss->clean($_POST['district']);
    //echo $countyCode;
    $counties =  $dbAccesss->select("subcounty", "", ['countyCode' => $countyCode, "districtCode" => $districtCode]);


    echo  json_encode($counties);
} else {
    echo "not sent";
}
