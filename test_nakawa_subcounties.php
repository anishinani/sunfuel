<?php
/**
 * Test NAKAWA Subcounties
 */

require_once 'utils/dbaccess.php';

echo "Testing NAKAWA Subcounties\n";
echo "=========================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Test with NAKAWA DIVISION (countyCode: 37)
    $district_code = '12'; // Kampala's districtCode
    $county_id = 37; // NAKAWA DIVISION countyCode
    
    echo "Looking for subcounties with districtCode: '$district_code' and countyCode: '$county_id'\n";
    
    // Get subcounties for this county and district
    $subcounties = $dbAccess->select("sub_counties", "", ["countyCode" => $county_id, "districtCode" => $district_code]);
    echo "Subcounties found: " . count($subcounties) . "\n";
    
    if (count($subcounties) > 0) {
        echo "Subcounties:\n";
        foreach ($subcounties as $subcounty) {
            echo "- {$subcounty['subCountyName']} (subCountyCode: {$subcounty['subCountyCode']})\n";
        }
    } else {
        echo "❌ No subcounties found. Let me check what subcounties exist for districtCode '$district_code'...\n";
        
        // Check what subcounties exist for this district
        $all_subcounties = $dbAccess->select("sub_counties", "", ["districtCode" => $district_code]);
        echo "All subcounties for districtCode '$district_code': " . count($all_subcounties) . "\n";
        
        if (count($all_subcounties) > 0) {
            echo "First few subcounties:\n";
            foreach (array_slice($all_subcounties, 0, 10) as $subcounty) {
                echo "- {$subcounty['subCountyName']} (countyCode: {$subcounty['countyCode']}, districtCode: {$subcounty['districtCode']})\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
