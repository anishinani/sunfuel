<?php
session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST['action'])) {
    $territory_district_id = $dbAccesss->clean($_POST['district']);
    
    // Get the district name from territory_districts
    $territory_district = $dbAccesss->select("territory_districts", "", ["id" => $territory_district_id]);
    
    if (count($territory_district) > 0) {
        $district_name = $territory_district[0]["districtName"];
        
        // Find the matching district in the districts table
        $district = $dbAccesss->select("districts", "", ["districtName" => $district_name]);
        
        if (count($district) > 0) {
            $district_code = $district[0]["districtCode"];
            
            // Get counties for this district using districtCode field
            $counties = $dbAccesss->select("counties", "", ["districtCode" => $district_code]);
            echo json_encode($counties);
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode(["error" => "No action"]);
}
