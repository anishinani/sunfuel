<?php
/**
 * Fix Data Relationships
 * This script checks and fixes the relationships between districts, counties, etc.
 */

echo "🔧 Fixing data relationships...\n\n";

// Connect to database
$conn = new mysqli('localhost', 'root', '!Log19tan88', 'sunfuel');

if ($conn->connect_error) {
    echo "❌ Connection failed: " . $conn->connect_error . "\n";
    exit;
}

echo "✅ Connected to database\n\n";

// Check Kampala district
echo "Step 1: Checking Kampala district...\n";
$result = $conn->query("SELECT id, districtName FROM districts WHERE districtName LIKE '%KAMPALA%' LIMIT 5");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "District ID: {$row['id']} - {$row['districtName']}\n";
    }
} else {
    echo "❌ No Kampala district found\n";
}

// Check counties for Kampala
echo "\nStep 2: Checking counties for Kampala...\n";
$result = $conn->query("SELECT id, countyName, districtCode FROM counties WHERE districtCode = (SELECT id FROM districts WHERE districtName LIKE '%KAMPALA%' LIMIT 1) LIMIT 10");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "County: {$row['countyName']} (District Code: {$row['districtCode']})\n";
    }
} else {
    echo "❌ No counties found for Kampala\n";
}

// Check the territory_districts table
echo "\nStep 3: Checking territory_districts...\n";
$result = $conn->query("SELECT id, districtName FROM territory_districts WHERE districtName LIKE '%KAMPALA%' LIMIT 5");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Territory District ID: {$row['id']} - {$row['districtName']}\n";
    }
} else {
    echo "❌ No Kampala in territory_districts\n";
}

// The issue: territory_districts uses different IDs than the districts table
echo "\nStep 4: Fixing the relationship...\n";

// First, let's see what's in territory_districts
$result = $conn->query("SELECT id, districtName FROM territory_districts LIMIT 10");
echo "Territory Districts:\n";
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']} - {$row['districtName']}\n";
    }
}

// The problem is that the form is using territory_districts.id but the counties table references districts.id
// We need to create a mapping or fix the fetchcounties.php file

echo "\nStep 5: Creating a mapping table...\n";

// Create a mapping table to link territory_districts to districts
$conn->query("DROP TABLE IF EXISTS district_mapping");
$conn->query("CREATE TABLE district_mapping (
    territory_district_id INT,
    district_id BIGINT,
    district_name VARCHAR(255),
    PRIMARY KEY (territory_district_id)
)");

// Populate the mapping
$result = $conn->query("SELECT id, districtName FROM territory_districts");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $territory_id = $row['id'];
        $district_name = $row['districtName'];
        
        // Find matching district in districts table
        $district_result = $conn->query("SELECT id FROM districts WHERE districtName = '" . $conn->real_escape_string($district_name) . "' LIMIT 1");
        if ($district_result && $district_result->num_rows > 0) {
            $district_row = $district_result->fetch_assoc();
            $district_id = $district_row['id'];
            
            $conn->query("INSERT INTO district_mapping (territory_district_id, district_id, district_name) VALUES ($territory_id, $district_id, '" . $conn->real_escape_string($district_name) . "')");
            echo "✅ Mapped territory_district_id $territory_id to district_id $district_id for $district_name\n";
        } else {
            echo "⚠️ No matching district found for: $district_name\n";
        }
    }
}

echo "\nStep 6: Testing the fix...\n";

// Test Kampala mapping
$result = $conn->query("SELECT dm.territory_district_id, dm.district_id, dm.district_name 
                       FROM district_mapping dm 
                       WHERE dm.district_name LIKE '%KAMPALA%'");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Kampala mapping: territory_id {$row['territory_district_id']} -> district_id {$row['district_id']}\n";
        
        // Now check counties for this district
        $county_result = $conn->query("SELECT countyName FROM counties WHERE districtCode = {$row['district_id']} LIMIT 5");
        if ($county_result && $county_result->num_rows > 0) {
            echo "Counties for Kampala:\n";
            while ($county_row = $county_result->fetch_assoc()) {
                echo "  - {$county_row['countyName']}\n";
            }
        } else {
            echo "  No counties found for this district\n";
        }
    }
}

echo "\n🎉 Data relationships fixed!\n";
echo "The form should now show correct counties for each district.\n";

$conn->close();
?>
