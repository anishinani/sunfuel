<?php
session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST['action'])) {
    //echo "here";
    $countyCode =  $dbAccesss->clean($_POST['subcounty']);
    //echo $countyCode;
    $counties =  $dbAccesss->select("subcounty", "", ['countyCode' => $countyCode]);


    echo  json_encode($counties);
} else {
    echo "not sent";
}
