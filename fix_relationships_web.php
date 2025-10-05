<?php
/**
 * Fix Data Relationships - Web Version
 * This script checks and fixes the relationships between districts, counties, etc.
 */

require_once 'utils/dbaccess.php';

echo "<h2>🔧 Fixing Data Relationships</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";

try {
    $dbAccess = new DbAccess();
    echo "<p class='success'>✅ Connected to database</p>";
    
    // Check Kampala district
    echo "<h3>Step 1: Checking Kampala district</h3>";
    $kampala_districts = $dbAccess->select("districts", "", ["districtName" => "KAMPALA"]);
    if (count($kampala_districts) > 0) {
        foreach ($kampala_districts as $district) {
            echo "<p>District ID: {$district['id']} - {$district['districtName']}</p>";
        }
    } else {
        echo "<p class='error'>❌ No Kampala district found</p>";
    }
    
    // Check counties for Kampala
    echo "<h3>Step 2: Checking counties for Kampala</h3>";
    if (count($kampala_districts) > 0) {
        $kampala_id = $kampala_districts[0]['id'];
        $counties = $dbAccess->select("counties", "", ["districtCode" => $kampala_id]);
        
        if (count($counties) > 0) {
            echo "<p class='success'>✅ Found " . count($counties) . " counties for Kampala:</p>";
            echo "<ul>";
            foreach (array_slice($counties, 0, 10) as $county) {
                echo "<li>{$county['countyName']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='error'>❌ No counties found for Kampala</p>";
        }
    }
    
    // Check territory_districts
    echo "<h3>Step 3: Checking territory_districts</h3>";
    $territory_districts = $dbAccess->select("territory_districts", "", ["districtName" => "KAMPALA"]);
    if (count($territory_districts) > 0) {
        foreach ($territory_districts as $td) {
            echo "<p>Territory District ID: {$td['id']} - {$td['districtName']}</p>";
        }
    } else {
        echo "<p class='error'>❌ No Kampala in territory_districts</p>";
    }
    
    // The issue: territory_districts uses different IDs than districts table
    echo "<h3>Step 4: The Problem</h3>";
    echo "<p>The form uses territory_districts.id but counties table references districts.id</p>";
    echo "<p>We need to fix the fetchcounties.php file to use the correct mapping.</p>";
    
    // Create a mapping table
    echo "<h3>Step 5: Creating mapping table</h3>";
    try {
        $dbAccess->execute("DROP TABLE IF EXISTS district_mapping");
        $dbAccess->execute("CREATE TABLE district_mapping (
            territory_district_id INT,
            district_id BIGINT,
            district_name VARCHAR(255),
            PRIMARY KEY (territory_district_id)
        )");
        echo "<p class='success'>✅ Created district_mapping table</p>";
        
        // Populate the mapping
        $all_territory_districts = $dbAccess->select("territory_districts");
        $mapped_count = 0;
        
        foreach ($all_territory_districts as $td) {
            $territory_id = $td['id'];
            $district_name = $td['districtName'];
            
            // Find matching district in districts table
            $matching_districts = $dbAccess->select("districts", "", ["districtName" => $district_name]);
            if (count($matching_districts) > 0) {
                $district_id = $matching_districts[0]['id'];
                
                $dbAccess->execute("INSERT INTO district_mapping (territory_district_id, district_id, district_name) VALUES ($territory_id, $district_id, '" . $dbAccess->clean($district_name) . "')");
                $mapped_count++;
            }
        }
        
        echo "<p class='success'>✅ Mapped $mapped_count districts</p>";
        
    } catch (Exception $e) {
        echo "<p class='error'>❌ Error creating mapping: " . $e->getMessage() . "</p>";
    }
    
    // Test the mapping
    echo "<h3>Step 6: Testing the mapping</h3>";
    $kampala_mapping = $dbAccess->select("district_mapping", "", ["district_name" => "KAMPALA"]);
    if (count($kampala_mapping) > 0) {
        $mapping = $kampala_mapping[0];
        echo "<p>Kampala mapping: territory_id {$mapping['territory_district_id']} -> district_id {$mapping['district_id']}</p>";
        
        // Check counties for this district
        $counties = $dbAccess->select("counties", "", ["districtCode" => $mapping['district_id']]);
        if (count($counties) > 0) {
            echo "<p class='success'>✅ Found " . count($counties) . " counties for Kampala:</p>";
            echo "<ul>";
            foreach (array_slice($counties, 0, 10) as $county) {
                echo "<li>{$county['countyName']}</li>";
            }
            echo "</ul>";
        }
    }
    
    echo "<h3>Step 7: Updating fetchcounties.php</h3>";
    
    // Update the fetchcounties.php file to use the mapping
    $fetchcounties_content = '<?php
session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST[\'action\'])) {
    $territory_district_id = $dbAccesss->clean($_POST[\'district\']);
    
    // Get the actual district_id from mapping
    $mapping = $dbAccesss->select("district_mapping", "", ["territory_district_id" => $territory_district_id]);
    
    if (count($mapping) > 0) {
        $district_id = $mapping[0]["district_id"];
        $counties = $dbAccesss->select("counties", "", ["districtCode" => $district_id]);
        echo json_encode($counties);
    } else {
        echo json_encode([]);
    }
} else {
    echo "not sent";
}';
    
    file_put_contents("views/fuelstation/fetchcounties.php", $fetchcounties_content);
    echo "<p class='success'>✅ Updated fetchcounties.php to use proper mapping</p>";
    
    echo "<h3>🎉 Fix Complete!</h3>";
    echo "<p class='success'>The form should now show correct counties for each district.</p>";
    echo "<p><a href='views/fuelstation/create.php' target='_blank'>Test the form now</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
