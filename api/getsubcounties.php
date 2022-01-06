<?php
session_start();
require_once("../utils/dbaccess.php");
$dbAccesss = new DbAccess();

if (isset($_GET['countyCode']) && isset($_GET['districtCode'])) {
    $noSubCounties = array();
    //echo "here";
    $countyCode =  $dbAccesss->clean($_GET['countyCode']);
    $districtCode =  $dbAccesss->clean($_GET['districtCode']);
    //echo $countyCode;
    $subCounties =  $dbAccesss->select("subcounty", "", ['countyCode' => $countyCode, "districtCode" => $districtCode]);
    if (count($subCounties) > 0) {
        echo json_encode($subCounties);
    } else {
        array_push($noSubCounties, array("message" => "No SubCounties available"));
        echo json_encode($noSubCounties);
    }
} else {
    $wrongParams =  array();
    array_push($wrongParams, array("message" => "Wrong Parameters Passed"));
    echo json_encode($wrongParams);
}
