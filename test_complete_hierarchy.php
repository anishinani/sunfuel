<?php
/**
 * Test Complete Geographic Hierarchy
 * This script will test the complete hierarchy: KAMPALA → KAWEMPE DIVISION → KAWEMPE DIVISION → MAKERERE II → ZONE A
 */

require_once 'utils/dbaccess.php';

echo "Test Complete Geographic Hierarchy\n";
echo "==================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Test the complete hierarchy
    echo "Testing: KAMPALA → KAWEMPE DIVISION → KAWEMPE DIVISION → MAKERERE II → ZONE A\n\n";
    
    // Step 1: Get KAMPALA district
    $territory_district_id = 49; // KAMPALA's territory_districts ID
    $territory_district = $dbAccess->select("territory_districts", "", ["id" => $territory_district_id]);
    
    if (count($territory_district) > 0) {
        $district_name = $territory_district[0]["districtName"];
        echo "Step 1: territory_districts ID $territory_district_id = '$district_name'\n";
        
        // Find the matching district in the districts table
        $district = $dbAccess->select("districts", "", ["districtName" => $district_name]);
        
        if (count($district) > 0) {
            $district_code = $district[0]["districtCode"];
            echo "Step 2: Found '$district_name' in districts table with districtCode: '$district_code'\n";
            
            // Step 3: Get counties for KAMPALA
            $counties = $dbAccess->select("counties", "", ["districtCode" => $district_code]);
            echo "Step 3: Counties for '$district_name': " . count($counties) . "\n";
            
            // Find KAWEMPE DIVISION
            $kawempe_county = null;
            foreach ($counties as $county) {
                if ($county["countyName"] === "KAWEMPE DIVISION") {
                    $kawempe_county = $county;
                    break;
                }
            }
            
            if ($kawempe_county) {
                echo "Step 4: Found KAWEMPE DIVISION (countyCode: " . $kawempe_county["countyCode"] . ")\n";
                
                // Step 5: Get subcounties for KAWEMPE DIVISION
                $subcounties = $dbAccess->select("sub_counties", "", ["countyCode" => $kawempe_county["countyCode"], "districtCode" => $district_code]);
                echo "Step 5: Subcounties for KAWEMPE DIVISION: " . count($subcounties) . "\n";
                
                if (count($subcounties) > 0) {
                    foreach ($subcounties as $subcounty) {
                        echo "  - " . $subcounty["subCountyName"] . " (subCountyCode: " . $subcounty["subCountyCode"] . ")\n";
                    }
                    
                    // Step 6: Get parishes for KAWEMPE DIVISION subcounty
                    $parishes = $dbAccess->select("parishes", "", ["countyCode" => $kawempe_county["countyCode"], "districtCode" => $district_code, "subCountyCode" => "1"]);
                    echo "Step 6: Parishes for KAWEMPE DIVISION subcounty: " . count($parishes) . "\n";
                    
                    if (count($parishes) > 0) {
                        foreach ($parishes as $parish) {
                            echo "  - " . $parish["parishName"] . " (parishCode: " . $parish["parishCode"] . ")\n";
                        }
                        
                        // Step 7: Get villages for MAKERERE II
                        $villages = $dbAccess->select("villages", "", ["countyCode" => $kawempe_county["countyCode"], "districtCode" => $district_code, "subCountyCode" => "1", "parishCode" => "8"]);
                        echo "Step 7: Villages for MAKERERE II: " . count($villages) . "\n";
                        
                        if (count($villages) > 0) {
                            foreach ($villages as $village) {
                                echo "  - " . $village["villageName"] . " (villageCode: " . $village["villageCode"] . ")\n";
                            }
                            
                            echo "\n✅ SUCCESS! Complete hierarchy is working:\n";
                            echo "KAMPALA → KAWEMPE DIVISION → KAWEMPE DIVISION → MAKERERE II → ZONE A\n";
                        } else {
                            echo "❌ No villages found for MAKERERE II\n";
                        }
                    } else {
                        echo "❌ No parishes found for KAWEMPE DIVISION subcounty\n";
                    }
                } else {
                    echo "❌ No subcounties found for KAWEMPE DIVISION\n";
                }
            } else {
                echo "❌ KAWEMPE DIVISION not found in counties\n";
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
