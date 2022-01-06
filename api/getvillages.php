<?php
session_start();
require_once("../utils/dbaccess.php");
$dbAccesss = new DbAccess();

if (isset($_GET['countyCode']) && isset($_GET['districtCode']) && isset($_GET['subCountyCode']) && isset($_GET['parishCode'])) {

    //echo "here";
    $countyCode =  $dbAccesss->clean($_GET['countyCode']);
    $districtCode =  $dbAccesss->clean($_GET['districtCode']);
    $subCountyCode =  $dbAccesss->clean($_GET['subCountyCode']);
    $parishCode  = $dbAccesss->clean($_GET['parishCode']);

    //echo $countyCode;
    // $villages =  $dbAccesss->select("villages", "", ['subCountyCode' => $subCountyCode, 
    // 'districtCode' => $districtCode, "countyCode" => $countyCode, "parishCode" => $parishCode]);

    $villages =  $dbAccesss->select("villages", "", [
        'parishCode' => $parishCode, "districtCode" => $districtCode,
        'countyCode' => $countyCode,
        'subCountyCode' => $subCountyCode
    ]);
    //var_dump($villages);
    //die();
    if (count($villages) > 0) {
        echo json_encode($villages);
    } else {
        $novillages = array();
        array_push($novillages, array("message" => "No Villages available"));
        echo json_encode($novillages);
    }
} else {
    $wrongParams =  array();
    array_push($wrongParams, array("message" => "Wrong Parameters Passed"));
    echo json_encode($wrongParams);
}
