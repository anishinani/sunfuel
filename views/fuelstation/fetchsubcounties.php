<?php
session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST['action'])) {
    $county_id = $dbAccesss->clean($_POST['subcounty']);
    $territory_district_id = $dbAccesss->clean($_POST['district']);
    
    // Get the district name from territory_districts
    $territory_district = $dbAccesss->select("territory_districts", "", ["id" => $territory_district_id]);
    
        if (count($territory_district) > 0) {
            $district_name = $territory_district[0]["districtName"];
            
            // Find the matching district in the districts table
            $district = $dbAccesss->select("districts", "", ["districtName" => $district_name]);
            
            if (count($district) > 0) {
                $district_code = $district[0]["districtCode"];
                
                // Get subcounties for this county and district
                $subcounties = $dbAccesss->select("sub_counties", "", ["countyCode" => $county_id, "districtCode" => $district_code]);
                
                // If no subcounties found, return empty array
                if (count($subcounties) == 0) {
                    // Return empty array - subcounties data may not be available for this county
                    echo json_encode([]);
                } else {
                    echo json_encode($subcounties);
                }
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode([]);
    }
} else {
    echo "not sent";
}
