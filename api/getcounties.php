<?php
session_start();
require_once("../utils/dbaccess.php");
$dbAccesss = new DbAccess();

if (isset($_GET['districtCode'])) {
    //die("here");
    $districtCode =  $dbAccesss->clean($_GET['districtCode']);
    $counties =  $dbAccesss->select("county", "", ['districtCode' => $districtCode]);
    if (count($counties) > 0) {
        echo  json_encode($counties);
    } else {
        $noCounties = array();
        //echo "here";
        array_push($noCounties, array("message" => "No counties available"));
        echo json_encode($noCounties);
    }
} else {

    $noDistrictCode = array();
    array_push($noDistrictCode, array("message" => "Please provide a district code"));
    echo json_encode($noDistrictCode);
}
