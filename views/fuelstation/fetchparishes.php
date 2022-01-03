<?php
session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST['action'])) {
    // district: district,
    // subcounty: subcounty
    $parishCode =  $dbAccesss->clean($_POST['parish']);
    $districtCode =  $dbAccesss->clean($_POST['district']);
    $subCountyCode =  $dbAccesss->clean($_POST['county']);
    $counties =  $dbAccesss->select("parishes", "", ['subCountyCode' => $parishCode, 'districtCode' => $districtCode, "countyCode" => $subCountyCode]);


    echo  json_encode($counties);
} else {
    echo "not sent";
}
