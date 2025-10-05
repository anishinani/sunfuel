<?php
/**
 * Test Kampala Counties with Correct Logic
 */

require_once 'utils/dbaccess.php';

echo "Testing Kampala Counties with Correct Logic\n";
echo "==========================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Test the corrected logic
    $territory_district_id = 49; // Kampala's territory_districts ID
    
    // Get the district name from territory_districts
    $territory_district = $dbAccess->select("territory_districts", "", ["id" => $territory_district_id]);
    
    if (count($territory_district) > 0) {
        $district_name = $territory_district[0]["districtName"];
        echo "Step 1: territory_districts ID $territory_district_id = '$district_name'\n";
        
        // Find the matching district in the districts table
        $district = $dbAccess->select("districts", "", ["districtName" => $district_name]);
        
        if (count($district) > 0) {
            $district_code = $district[0]["districtCode"];
            echo "Step 2: Found '$district_name' in districts table with districtCode: '$district_code'\n";
            
            // Get counties for this district using districtCode field
            $counties = $dbAccess->select("counties", "", ["districtCode" => $district_code]);
            echo "Step 3: Counties for '$district_name' (districtCode '$district_code'): " . count($counties) . "\n";
            
            if (count($counties) > 0) {
                echo "Counties found:\n";
                foreach ($counties as $county) {
                    echo "- {$county['countyName']} (countyCode: {$county['countyCode']})\n";
                }
            }
        } else {
            echo "❌ '$district_name' not found in districts table\n";
        }
    } else {
        echo "❌ Territory district ID $territory_district_id not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
