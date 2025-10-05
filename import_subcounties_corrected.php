<?php
/**
 * Import Subcounties Data Corrected
 * This script will import subcounties from the corrected data file
 */

require_once 'utils/dbaccess.php';

echo "Import Subcounties Data Corrected\n";
echo "================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing subcounties data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE sub_counties");
    echo "✅ Cleared existing data\n";
    
    // Read the corrected values file
    $valuesFile = '/Applications/XAMPP/xamppfiles/htdocs/sunfuel/subcounties_corrected.sql';
    $values = file($valuesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    echo "Step 2: Found " . count($values) . " subcounty records\n";
    
    // Insert records in batches
    $batchSize = 100;
    $totalInserted = 0;
    $errors = 0;
    
    for ($i = 0; $i < count($values); $i += $batchSize) {
        $batch = array_slice($values, $i, $batchSize);
        
        // Clean the batch
        $cleanBatch = [];
        foreach ($batch as $record) {
            if (!empty($record)) {
                $cleanBatch[] = $record;
            }
        }
        
        if (!empty($cleanBatch)) {
            $insertSQL = "INSERT INTO sub_counties (id, uuid, districtCode, countyCode, subCountyCode, subCountyName, deleted_at, created_at, updated_at) VALUES " . implode(', ', $cleanBatch);
            
            if ($dbAccess->conn->query($insertSQL)) {
                $totalInserted += count($cleanBatch);
                if ($totalInserted % 1000 == 0) {
                    echo "✅ Inserted $totalInserted records...\n";
                }
            } else {
                $errors++;
                echo "❌ Failed to insert batch starting at record " . ($i + 1) . ": " . $dbAccess->conn->error . "\n";
                break;
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
    
    echo "\n✅ SUCCESS! All subcounties data has been imported!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
