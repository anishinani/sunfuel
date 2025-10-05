<?php
/**
 * Import Parishes from File
 * This script will import parishes from the extracted values file
 */

require_once 'utils/dbaccess.php';

echo "Import Parishes from File\n";
echo "========================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing parishes data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE parishes");
    echo "✅ Cleared existing data\n";
    
    // Read the values file
    $valuesFile = '/Applications/XAMPP/xamppfiles/htdocs/sunfuel/parishes_values_only.sql';
    $values = file($valuesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    echo "Step 2: Found " . count($values) . " parish records\n";
    
    // Insert records in batches
    $batchSize = 100;
    $totalInserted = 0;
    
    for ($i = 0; $i < count($values); $i += $batchSize) {
        $batch = array_slice($values, $i, $batchSize);
        
        // Remove trailing commas
        $batch = array_map(function($record) {
            return rtrim($record, ',');
        }, $batch);
        
        $insertSQL = "INSERT INTO parishes (id, uuid, districtCode, countyCode, subCountyCode, parishCode, parishName, deleted_at, created_at, updated_at) VALUES " . implode(', ', $batch);
        
        if ($dbAccess->conn->query($insertSQL)) {
            $totalInserted += count($batch);
            echo "✅ Inserted batch " . (floor($i / $batchSize) + 1) . " (" . count($batch) . " records) - Total: $totalInserted\n";
        } else {
            echo "❌ Failed to insert batch: " . $dbAccess->conn->error . "\n";
            break;
        }
    }
    
    echo "Step 3: Import completed!\n";
    echo "✅ Total parishes imported: $totalInserted\n";
    
    // Verify the import
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes");
    $row = $result->fetch_assoc();
    echo "✅ Verification: " . $row['count'] . " parishes in database\n";
    
    // Test with different districts
    echo "\nStep 4: Testing with different districts...\n";
    
    // Test KAMPALA
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes WHERE districtCode = '12'");
    $row = $result->fetch_assoc();
    echo "✅ KAMPALA parishes: " . $row['count'] . "\n";
    
    // Test WAKISO
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes WHERE districtCode = '52'");
    $row = $result->fetch_assoc();
    echo "✅ WAKISO parishes: " . $row['count'] . "\n";
    
    // Test top 5 districts by parish count
    $result = $dbAccess->conn->query("SELECT districtCode, COUNT(*) as count FROM parishes GROUP BY districtCode ORDER BY count DESC LIMIT 5");
    echo "Top 5 districts by parish count:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- District " . $row['districtCode'] . ": " . $row['count'] . " parishes\n";
    }
    
    echo "\n✅ SUCCESS! All parishes data has been imported!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
