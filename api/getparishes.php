<?php
session_start();
require_once("../utils/dbaccess.php");
$dbAccesss = new DbAccess();

if (isset($_GET['countyCode']) && isset($_GET['districtCode']) && isset($_GET['subCountyCode'])) {

    //echo "here";
    $countyCode =  $dbAccesss->clean($_GET['countyCode']);
    $districtCode =  $dbAccesss->clean($_GET['districtCode']);
    $subCountyCode =  $dbAccesss->clean($_GET['subCountyCode']);
    //echo $countyCode;
    $parishes =  $dbAccesss->select("parishes", "", ['subCountyCode' => $subCountyCode, 'districtCode' => $districtCode, "countyCode" => $countyCode]);
    if (count($parishes) > 0) {
        echo json_encode($parishes);
    } else {
        $noParishes = array();
        array_push($parishes, array("message" => "No Parishes available"));
        echo json_encode($parishes);
    }
} else {
    $wrongParams =  array();
    array_push($wrongParams, array("message" => "Wrong Parameters Passed"));
    echo json_encode($wrongParams);
}
