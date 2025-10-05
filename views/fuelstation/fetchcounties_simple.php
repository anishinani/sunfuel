<?php
/**
 * Simple Fetch Counties - Debug Version
 * This version will help us debug the issue
 */

// Set content type to JSON
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!-- Debug: Starting fetchcounties_simple.php -->\n";

try {
    // Try to include the database access
    $dbAccess = null;
    try {
        include_once("../../utils/dbaccess.php");
        $dbAccess = new DbAccess();
        echo "<!-- Debug: DbAccess created successfully -->\n";
    } catch (Exception $e) {
        echo "<!-- Debug: DbAccess error: " . $e->getMessage() . " -->\n";
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
    
    if (isset($_POST['action'])) {
        echo "<!-- Debug: Action received: " . $_POST['action'] . " -->\n";
        
        $territory_district_id = $_POST['district'];
        echo "<!-- Debug: Territory district ID: " . $territory_district_id . " -->\n";
        
        // Get the district name from territory_districts
        $territory_district = $dbAccess->select("territory_districts", "", ["id" => $territory_district_id]);
        echo "<!-- Debug: Territory district query result count: " . count($territory_district) . " -->\n";
        
        if (count($territory_district) > 0) {
            $district_name = $territory_district[0]["districtName"];
            echo "<!-- Debug: District name: " . $district_name . " -->\n";
            
            // Find the matching district in the districts table
            $district = $dbAccess->select("districts", "", ["districtName" => $district_name]);
            echo "<!-- Debug: Districts query result count: " . count($district) . " -->\n";
            
            if (count($district) > 0) {
                $district_id = $district[0]["id"];
                echo "<!-- Debug: District ID: " . $district_id . " -->\n";
                
                // Get counties for this district
                $counties = $dbAccess->select("counties", "", ["districtCode" => $district_id]);
                echo "<!-- Debug: Counties query result count: " . count($counties) . " -->\n";
                
                if (count($counties) > 0) {
                    echo "<!-- Debug: First county: " . $counties[0]['countyName'] . " -->\n";
                }
                
                echo json_encode($counties);
            } else {
                echo "<!-- Debug: No matching district found -->\n";
                echo json_encode([]);
            }
        } else {
            echo "<!-- Debug: No territory district found -->\n";
            echo json_encode([]);
        }
    } else {
        echo "<!-- Debug: No action received -->\n";
        echo json_encode(["error" => "No action received"]);
    }
    
} catch (Exception $e) {
    echo "<!-- Debug: Exception: " . $e->getMessage() . " -->\n";
    echo json_encode(["error" => $e->getMessage()]);
}
?>
