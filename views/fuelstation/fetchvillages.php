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



    // Get the district name from territory_districts
    $territory_district = $dbAccesss->select("territory_districts", "", ["id" => $districtCode]);
    
    if (count($territory_district) > 0) {
        $district_name = $territory_district[0]["districtName"];
        
        // Find the matching district in the districts table
        $district = $dbAccesss->select("districts", "", ["districtName" => $district_name]);
        
        if (count($district) > 0) {
            $district_code = $district[0]["districtCode"];
            
            // Get villages for this parish, subcounty, county, and district
            $counties = $dbAccesss->select("villages", "", ['parishCode' => $parishCode, "districtCode" => $district_code, 'countyCode' => $County, 'subCountyCode' => $subCountyCode]);
        } else {
            $counties = [];
        }
    } else {
        $counties = [];
    }


    echo  json_encode($counties);
} else {
    echo "not sent";
}
