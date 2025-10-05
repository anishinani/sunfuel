<?php
/**
 * Test Subcounties with Correct Logic
 */

require_once 'utils/dbaccess.php';

echo "Testing Subcounties with Correct Logic\n";
echo "=====================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Test with NAKAWA DIVISION (countyCode: 37)
    $territory_district_id = 49; // Kampala's territory_districts ID
    $county_id = 37; // NAKAWA DIVISION countyCode
    
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
            
            // Get subcounties for this county and district
            $subcounties = $dbAccess->select("sub_counties", "", ["countyCode" => $county_id, "districtCode" => $district_code]);
            echo "Step 3: Subcounties for '$district_name' (districtCode '$district_code', countyCode '$county_id'): " . count($subcounties) . "\n";
            
            if (count($subcounties) > 0) {
                echo "Subcounties found:\n";
                foreach ($subcounties as $subcounty) {
                    echo "- {$subcounty['subCountyName']} (subCountyCode: {$subcounty['subCountyCode']})\n";
                }
            } else {
                echo "❌ No subcounties found. Let me check what's in the sub_counties table...\n";
                
                // Check what subcounties exist for this district
                $all_subcounties = $dbAccess->select("sub_counties", "", ["districtCode" => $district_code]);
                echo "All subcounties for districtCode '$district_code': " . count($all_subcounties) . "\n";
                
                if (count($all_subcounties) > 0) {
                    echo "First few subcounties:\n";
                    foreach (array_slice($all_subcounties, 0, 5) as $subcounty) {
                        echo "- {$subcounty['subCountyName']} (countyCode: {$subcounty['countyCode']}, districtCode: {$subcounty['districtCode']})\n";
                    }
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
