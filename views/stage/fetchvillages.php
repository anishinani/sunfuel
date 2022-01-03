<?php
session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST['action'])) {
    // district:district,
    // subcounty:subcounty,
    // county:county
    $parishCode =  $dbAccesss->clean($_POST['parish']);
    $districtCode =  $dbAccesss->clean($_POST['district']);
    $County =  $dbAccesss->clean($_POST['county']);
    $subCountyCode =  $dbAccesss->clean($_POST['subcounty']);



    $counties =  $dbAccesss->select("villages", "", ['parishCode' => $parishCode, "districtCode" => $districtCode, 'countyCode' => $County, 'subCountyCode' => $subCountyCode]);


    echo  json_encode($counties);
} else {
    echo "not sent";
}
