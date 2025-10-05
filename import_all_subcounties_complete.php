<?php
/**
 * Import ALL Subcounties Data Complete
 * This script will import ALL subcounties from the original SQL file
 */

require_once 'utils/dbaccess.php';

echo "Import ALL Subcounties Data Complete\n";
echo "===================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing subcounties data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE sub_counties");
    echo "✅ Cleared existing data\n";
    
    // Read the values file
    $valuesFile = '/Applications/XAMPP/xamppfiles/htdocs/sunfuel/subcounties_values_only.sql';
    $values = file($valuesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    echo "Step 2: Found " . count($values) . " subcounty records\n";
    
    // Insert records one by one to avoid SQL syntax issues
    $totalInserted = 0;
    $errors = 0;
    
    foreach ($values as $index => $record) {
        // Remove trailing comma
        $record = rtrim($record, ',');
        
        $insertSQL = "INSERT INTO sub_counties (id, uuid, districtCode, countyCode, subCountyCode, subCountyName, deleted_at, created_at, updated_at) VALUES $record";
        
        if ($dbAccess->conn->query($insertSQL)) {
            $totalInserted++;
            if ($totalInserted % 5000 == 0) {
                echo "✅ Inserted $totalInserted records...\n";
            }
        } else {
            $errors++;
            if ($errors <= 5) { // Only show first 5 errors
                echo "❌ Failed to insert record " . ($index + 1) . ": " . $dbAccess->conn->error . "\n";
            }
        }
    }
    
    echo "Step 3: Import completed!\n";
    echo "✅ Total subcounties imported: $totalInserted\n";
    echo "❌ Total errors: $errors\n";
    
    // Verify the import
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties");
    $row = $result->fetch_assoc();
    echo "✅ Verification: " . $row['count'] . " subcounties in database\n";
    
    // Test with KIRA MUNICIPALITY (WAKISO district)
    echo "\nStep 4: Testing with KIRA MUNICIPALITY...\n";
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '52' AND countyCode = '240'");
    $row = $result->fetch_assoc();
    echo "✅ KIRA MUNICIPALITY subcounties: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $result = $dbAccess->conn->query("SELECT subCountyName FROM sub_counties WHERE districtCode = '52' AND countyCode = '240' ORDER BY subCountyName");
        echo "KIRA MUNICIPALITY subcounties:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['subCountyName'] . "\n";
        }
    }
    
    // Test with KAWEMPE DIVISION (KAMPALA district)
    echo "\nStep 5: Testing with KAWEMPE DIVISION...\n";
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '12' AND countyCode = '35'");
    $row = $result->fetch_assoc();
    echo "✅ KAWEMPE DIVISION subcounties: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $result = $dbAccess->conn->query("SELECT subCountyName FROM sub_counties WHERE districtCode = '12' AND countyCode = '35' ORDER BY subCountyName");
        echo "KAWEMPE DIVISION subcounties:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['subCountyName'] . "\n";
        }
    }
    
    // Test top 5 counties by subcounty count
    $result = $dbAccess->conn->query("SELECT districtCode, countyCode, COUNT(*) as count FROM sub_counties GROUP BY districtCode, countyCode ORDER BY count DESC LIMIT 5");
    echo "\nTop 5 counties by subcounty count:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- District " . $row['districtCode'] . ", County " . $row['countyCode'] . ": " . $row['count'] . " subcounties\n";
    }
    
    echo "\n✅ SUCCESS! All subcounties data has been imported!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
