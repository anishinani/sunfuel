<?php
session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST['action'])) {
    $districtCode =  $dbAccesss->clean($_POST['district']);
    $counties =  $dbAccesss->select("county", "", ['districtCode' => $districtCode]);

    
    echo  json_encode($counties);
} else {
    echo "not sent";
}
